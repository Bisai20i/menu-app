<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\DailyRestaurantStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of reports for all restaurants.
     */
    public function index(Request $request)
    {
        // Ensure only super admins can access
        if (!auth('admin')->user()->is_super_admin) {
            abort(403, 'Unauthorized action.');
        }

        $search = $request->input('search');
        $page = $request->input('page', 1);

        // Caching key based on search and page
        $cacheKey = "reports_index_page_{$page}_search_" . md5($search);

        $restaurants = Cache::remember($cacheKey, 3600, function () use ($search) {
            $query = Restaurant::with(['admin']);

            if ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('admin', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                }
                );
            }

            $paginated = $query->paginate(10);

            // Calculate averages for each restaurant in the current page
            $paginated->getCollection()->transform(function ($restaurant) {
                    // Get stats summary via DB aggregate for efficiency within the cache closure
                    $statsSummary = DailyRestaurantStat::where('restaurant_id', $restaurant->id)
                        ->selectRaw('COUNT(*) as days_count, SUM(total_orders) as total_orders, SUM(total_revenue) as total_revenue, SUM(menu_views) as total_views')
                        ->first();

                    $days = $statsSummary->days_count ?: 1;

                    $restaurant->avg_orders = $statsSummary->total_orders / $days;
                    $restaurant->avg_revenue = $statsSummary->total_revenue / $days;
                    $restaurant->avg_views = $statsSummary->total_views / $days;

                    return $restaurant;
                }
                );

                return $paginated;
            });

        if ($request->ajax()) {
            return view('admin.reports.partials.table', ['restaurants' => $restaurants])->render();
        }

        return view('admin.reports.index', ['restaurants' => $restaurants]);
    }

    /**
     * Display detailed report with charts for a specific restaurant.
     */
    public function show($id)
    {
        if (!auth('admin')->user()->is_super_admin) {
            abort(403, 'Unauthorized action.');
        }

        $restaurant = Restaurant::with('admin')->findOrFail($id);

        $cacheKey = "report_detail_restaurant_{$id}";

        $statsData = Cache::remember($cacheKey, 3600, function () use ($id) {
            // Get last 30 days of stats for charts
            $stats = DailyRestaurantStat::where('restaurant_id', $id)
                ->orderBy('date', 'desc')
                ->limit(30)
                ->get()
                ->reverse()
                ->values();

            $totals = DailyRestaurantStat::where('restaurant_id', $id)
                ->selectRaw('SUM(total_orders) as total_orders, SUM(total_revenue) as total_revenue, SUM(menu_views) as total_views')
                ->first();

            return [
            'chart_stats' => $stats,
            'totals' => $totals
            ];
        });

        return view('admin.reports.show', [
            'restaurant' => $restaurant,
            'stats' => $statsData['chart_stats'],
            'totals' => $statsData['totals']
        ]);
    }
}
