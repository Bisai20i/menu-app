<?php
namespace App\Http\Controllers;

use App\Events\TableStatusUpdated;
use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use App\Models\TableSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TableViewController extends Controller
{
    public function index()
    {
        $admin      = Auth::guard('admin')->user();
        $restaurant = $admin->restaurant;

        $tables = RestaurantTable::with([
            'activeSessions.orders.items.menuItem',
            'activeSessions.openedBy',
        ])
            ->orderBy('section')
            ->orderBy('table_number')
            ->get();

        return view('pages.admin.restaurant-table', compact('tables', 'restaurant'));
    }

    public function closeSession(string $uuid): JsonResponse
    {
        $admin   = Auth::guard('admin')->user();
        $session = TableSession::where('uuid', $uuid)
            ->where('restaurant_id', $admin->restaurant_id)
            ->where('status', 'active')
            ->firstOrFail();

        $table = $session->table;
        $table->closeSession($session, $admin->id);

        // Reload table status after close
        $table->refresh();

        broadcast(new TableStatusUpdated($table, $session->fresh()));

        return response()->json([
            'success'      => true,
            'table_status' => $table->status,
            'message'      => "Session for Table {$table->table_number} closed successfully.",
        ]);
    }

    public function showSession(string $uuid): JsonResponse
    {
        $admin = Auth::guard('admin')->user();
        $table = RestaurantTable::where('uuid', $uuid)
            ->where('restaurant_id', $admin->restaurant_id)
            ->firstOrFail();

        $sessions = $table->activeSessions()->with([
            'orders.items.menuItem',
            'openedBy',
        ])->get();

        $formattedSessions = $sessions->map(function ($session) {
            $orders = $session->orders->map(function ($order) {
                return [
                    'uuid'         => $order->uuid,
                    'status'       => $order->status,
                    'is_paid'      => $order->is_paid,
                    'total_amount' => $order->total_amount,
                    'note'         => $order->note,
                    'items'        => $order->items->map(fn($item) => [
                        'name'            => $item->menuItem?->name ?? 'Item',
                        'quantity'        => $item->quantity,
                        'unit_price'      => $item->unit_price,
                        'subtotal'        => $item->subtotal,
                        'special_request' => $item->special_request,
                    ]),
                ];
            });

            return [
                'id'          => $session->id,
                'uuid'        => $session->uuid,
                'status'      => $session->status,
                'opened_at'   => $session->opened_at?->toISOString(),
                'guest_count' => $session->guest_count,
                'opened_by'   => $session->openedBy?->name ?? 'Customer',
                'grand_total' => $session->grandTotal(),
                'orders'      => $orders,
            ];
        });

        return response()->json([
            'sessions' => $formattedSessions,
        ]);
    }
}
