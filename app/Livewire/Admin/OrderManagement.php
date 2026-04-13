<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Events\OrderStatusUpdated;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::order-management-layout')]
#[Title("Order Manageemnt")]
class OrderManagement extends Component
{
    // Currently active status tab: pending | confirmed | served | paid
    public string $activeTab = 'pending';

    // Track which table/section we're filtering by (0 = all)
    public int $filterTableId = 0;

    // Cancellation Modal State
    public ?int $cancelModalOrderId = null;
    public array $selectedOrderItemIds = [];
    public string $cancellationNote = '';

    // ── Lifecycle ────────────────────────────────────────────────────

    public function mount(): void
    {
        // Default to the pending tab so staff see urgent orders first
        $this->activeTab = 'pending';
    }

    public function getListeners()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) return [];

        return [
            // Listen for all order changes for the current restaurant, including brand-new orders.
            "echo-private:restaurant.{$admin->restaurant_id},OrderStatusUpdated" => '$refresh',
        ];
    }

    // ── Actions ──────────────────────────────────────────────────────

    /**
     * Confirm a pending order.
     */
    public function confirmOrder(int $orderId): void
    {
        $order = $this->findOwnedOrder($orderId);
        if (! $order || ! $order->isPending()) return;

        // Prevent confirmation if all items are cancelled
        if ($order->items()->where('is_cancelled', false)->count() === 0) {
            $this->dispatch('alert', type: 'error', message: 'Cannot confirm an order with no active items. Please cancel the order instead.');
            return;
        }

        $order->update([
            'status'       => 'confirmed',
            'confirmed_by' => Auth::guard('admin')->id(),
        ]);

        OrderStatusUpdated::dispatch($order);

        $this->dispatch('order-updated', id: $orderId);
    }

    /**
     * Request user to reconfirm the order.
     */
    public function requestUserConfirmation(int $orderId): void
    {
        $order = $this->findOwnedOrder($orderId);
        if (!$order) return;

        $order->update(['needs_user_confirmation' => true]);

        OrderStatusUpdated::dispatch($order);

        $this->dispatch('order-updated', id: $orderId);
        $this->dispatch('alert', type: 'success', message: 'Confirmation requested from user.');
    }

    /**
     * Open the item cancellation modal.
     */
    public function openCancelItemsModal(int $orderId): void
    {
        $order = $this->findOwnedOrder($orderId);
        if (!$order) return;

        // Restriction: Only allow cancellation in pending state
        if (!$order->isPending()) {
            $this->dispatch('alert', type: 'error', message: 'Cancellations are only allowed while the order is in the Pending state.');
            return;
        }

        $this->cancelModalOrderId = $orderId;
        $this->selectedOrderItemIds = [];
        $this->cancellationNote = '';

        $this->dispatch('open-cancel-modal');
    }

    /**
     * Submit the item cancellation.
     */
    public function submitCancelItems(): void
    {
        if (empty($this->selectedOrderItemIds)) {
            $this->dispatch('alert', type: 'error', message: 'Please select at least one item to cancel.');
            return;
        }

        $order = $this->findOwnedOrder($this->cancelModalOrderId);
        if (!$order) return;

        \App\Models\OrderItem::whereIn('id', $this->selectedOrderItemIds)
            ->where('order_id', $order->id)
            ->update([
                'is_cancelled'      => true,
                'cancellation_note' => $this->cancellationNote,
            ]);

        // Recalculate order total
        $activeItemsCount = $order->items()->where('is_cancelled', false)->count();
        $newTotal = $order->items()->where('is_cancelled', false)->sum('subtotal');
        
        $updateData = ['total_amount' => $newTotal];
        $isOrderCancelled = false;

        // Auto-cancel order if no items left
        if ($activeItemsCount === 0) {
            $updateData['status'] = 'cancelled';
            $isOrderCancelled = true;
        }

        $order->update($updateData);

        OrderStatusUpdated::dispatch($order);

        $this->cancelModalOrderId = null;
        $this->selectedOrderItemIds = [];
        $this->cancellationNote = '';

        $this->dispatch('close-cancel-modal');
        $this->dispatch('order-updated', id: $order->id);
        
        if ($isOrderCancelled) {
            $this->dispatch('alert', type: 'warning', message: 'All items cancelled. Order has been automatically cancelled.');
        } else {
            $this->dispatch('alert', type: 'success', message: 'Items cancelled successfully. Order total updated.');
        }
    }

    /**
     * Mark a confirmed order as served.
     */
    public function serveOrder(int $orderId): void
    {
        $order = $this->findOwnedOrder($orderId);
        if (! $order || ! $order->isConfirmed()) return;

        $order->update([
            'status'    => 'served',
            'served_by' => Auth::guard('admin')->id(),
        ]);

        OrderStatusUpdated::dispatch($order);

        $this->dispatch('order-updated', id: $orderId);
    }

    /**
     * Mark an order as paid.
     */
    public function markAsPaid(int $orderId): void
    {
        $order = $this->findOwnedOrder($orderId);
        if (! $order || $order->is_paid) return;

        $order->markAsPaid();

        OrderStatusUpdated::dispatch($order);

        $this->dispatch('order-updated', id: $orderId);
    }

    /**
     * Undo a cancellation and restore the order to pending.
     * Allowed only within 30 minutes of order creation.
     */
    public function undoOrderCancellation(int $orderId): void
    {
        $order = $this->findOwnedOrder($orderId);
        
        if (!$order || $order->status !== 'cancelled') {
            $this->dispatch('alert', type: 'error', message: 'Only cancelled orders can be restored.');
            return;
        }

        // 30 minute window check
        if ($order->created_at->diffInMinutes(now()) > 30) {
            $this->dispatch('alert', type: 'error', message: 'Restore period (30 mins) has expired for this order.');
            return;
        }

        // Restore everything to active state
        \Illuminate\Support\Facades\DB::transaction(function() use ($order) {
            $order->items()->update(['is_cancelled' => false, 'cancellation_note' => null]);
            
            // Recalculate total
            $newTotal = $order->items()->sum('subtotal');
            
            $order->update([
                'status' => 'pending',
                'total_amount' => $newTotal,
                'needs_user_confirmation' => true, // Force re-verification after restoration
            ]);
        });

        OrderStatusUpdated::dispatch($order);

        $this->dispatch('order-updated', id: $orderId);
        $this->dispatch('alert', type: 'success', message: 'Order has been restored to Pending status.');
    }

    /**
     * Close the active table session.
     */
    public function closeTableSession(int $sessionId): void
    {
        $session = \App\Models\TableSession::where('restaurant_id', Auth::guard('admin')->user()->restaurant_id)
            ->where('id', $sessionId)
            ->where('status', 'active')
            ->first();

        if ($session) {
            $session->close(Auth::guard('admin')->id(), 'paid');
            $this->dispatch('session-closed');
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────

    /**
     * Find an order that belongs to the authenticated admin's restaurant.
     */
    protected function findOwnedOrder(int $orderId): ?Order
    {
        $admin = Auth::guard('admin')->user();
        return Order::with('items.menuItem', 'table')
            ->where('id', $orderId)
            ->where('restaurant_id', $admin->restaurant_id)
            ->first();
    }

    // ── Render ────────────────────────────────────────────────────────

    public function render()
    {
        $admin        = Auth::guard('admin')->user();
        $restaurantId = $admin->restaurant_id;

        // Fetch all orders for the current restaurant, eager-loaded.
        // We split them by status in the view to avoid multiple DB queries.
        $allOrders = Order::with(['items.menuItem', 'table', 'session'])
            ->where('restaurant_id', $restaurantId)
            ->whereDate('created_at', today())
            ->when($this->filterTableId, fn ($q) => $q->where('restaurant_table_id', $this->filterTableId))
            ->whereIn('status', ['pending', 'confirmed', 'served'])
            ->where('is_paid', false)
            ->orderBy('created_at', 'asc')
            ->get();

        // Separate for each lane — done in PHP, single DB hit
        $pending   = $allOrders->where('status', 'pending')->values();
        $confirmed = $allOrders->where('status', 'confirmed')->values();
        $served    = $allOrders->where('status', 'served')->values();

        // Cancelled orders (recent)
        $cancelled = Order::with(['items.menuItem', 'table'])
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'cancelled')
            ->whereDate('created_at', today())
            ->when($this->filterTableId, fn ($q) => $q->where('restaurant_table_id', $this->filterTableId))
            ->latest()
            ->take(20)
            ->get();

        // Counts incl. paid history for "Paid" tab badge
        $paidOrders = Order::with(['items.menuItem', 'table', 'session'])
            ->where('restaurant_id', $restaurantId)
            ->where('is_paid', true)
            ->whereDate('created_at', today())
            ->latest('paid_at')
            ->take(50)
            ->get();

        // Restaurant tables for the filter dropdown
        $tables = \App\Models\RestaurantTable::where('restaurant_id', $restaurantId)
            ->where('is_active', true)
            ->orderBy('table_number')
            ->get();

        return view('livewire.admin.order-management', [
            'pending'    => $pending,
            'confirmed'  => $confirmed,
            'served'     => $served,
            'cancelled'  => $cancelled,
            'paidOrders' => $paidOrders,
            'tables'     => $tables,
        ]);
    }
}
