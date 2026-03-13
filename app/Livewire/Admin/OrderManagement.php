<?php

namespace App\Livewire\Admin;

use App\Models\Order;
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

    // ── Lifecycle ────────────────────────────────────────────────────

    public function mount(): void
    {
        // Default to the pending tab so staff see urgent orders first
        $this->activeTab = 'pending';
    }

    // ── Actions ──────────────────────────────────────────────────────

    /**
     * Confirm a pending order.
     */
    public function confirmOrder(int $orderId): void
    {
        $order = $this->findOwnedOrder($orderId);
        if (! $order || ! $order->isPending()) return;

        $order->update([
            'status'       => 'confirmed',
            'confirmed_by' => Auth::guard('admin')->id(),
        ]);

        $this->dispatch('order-updated', id: $orderId);
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

        $this->dispatch('order-updated', id: $orderId);
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
            ->when($this->filterTableId, fn ($q) => $q->where('restaurant_table_id', $this->filterTableId))
            ->whereIn('status', ['pending', 'confirmed', 'served'])
            ->where('is_paid', false)
            ->orderBy('created_at', 'asc')
            ->get();

        // Separate for each lane — done in PHP, single DB hit
        $pending   = $allOrders->where('status', 'pending')->values();
        $confirmed = $allOrders->where('status', 'confirmed')->values();
        $served    = $allOrders->where('status', 'served')->values();

        // Counts incl. paid history for "Paid" tab badge
        $paidOrders = Order::with(['items.menuItem', 'table', 'session'])
            ->where('restaurant_id', $restaurantId)
            ->where('is_paid', true)
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
            'paidOrders' => $paidOrders,
            'tables'     => $tables,
        ]);
    }
}
