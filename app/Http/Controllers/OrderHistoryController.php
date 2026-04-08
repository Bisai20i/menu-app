<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderHistoryController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth('admin')->user();
        if (!$admin || !$admin->restaurant_id) {
            abort(403, 'Unauthorized restaurant access.');
        }

        $query = Order::with(['items.menuItem', 'table', 'session'])
            ->where('restaurant_id', $admin->restaurant_id);

        // Filter by Search Query (ID or Table Number)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('table', function($t) use ($search) {
                      $t->where('table_number', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        $tables = RestaurantTable::where('restaurant_id', $admin->restaurant_id)
            ->where('is_active', true)
            ->orderBy('table_number')
            ->get();

        return view('admin.order-history.index', compact('orders', 'tables'));
    }
}
