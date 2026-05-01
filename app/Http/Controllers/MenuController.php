<?php
namespace App\Http\Controllers;

use App\Events\OrderStatusUpdated;
use App\Helpers\ColorHelper;
use App\Models\Admin;
use App\Models\DailyRestaurantStat;
use App\Models\MenuCategory;
use App\Models\MenuImage;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use App\Models\TableSession;
use App\Models\WaiterCall;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index(){
        return view('app');
    }
    public function show($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $palette = ColorHelper::generatePalette($restaurant->settings['primary_color'] ?? '#b8912a');

        $viewSimpleMenu = false;
        $admin          = Admin::where('restaurant_id', $restaurant->id)
            ->whereNot('role', 'superadmin')
            ->where('is_active', true) //false for superadmin and true for restaurant admin
            ->first();
        if ($admin) {
            $viewSimpleMenu = $admin && $admin->hasActiveSubscription() ? false : true;
        }

        $galleryImages = MenuImage::where('restaurant_id', $restaurant->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        /**
         * increment the menu views for the restaurant
         */
        DailyRestaurantStat::updateOrCreate(
            ['restaurant_id' => $restaurant->id, 'date' => now()->toDateString()],
            []
        )->increment('menu_views');

        if ($viewSimpleMenu) {
            return view('menus.simple-menu', compact('restaurant', 'galleryImages', 'palette'));

        }
        $categories = MenuCategory::where('restaurant_id', $restaurant->id)
            ->where('is_active', true)
            ->with(['menuItems' => function ($query) {
                $query->where('is_available', true);
            }])
            ->orderBy('sort_order')
            ->get();

        return view('menus.detailed-menu', compact('restaurant', 'categories', 'galleryImages', 'palette'));
    }

    /**
     * ---------------------------------------------------------------
     * PUBLIC API — Fetch full menu for QR-scanned table
     *
     * GET /api/menu/{restaurant_slug}/{table_uuid}
     * ---------------------------------------------------------------
     */
    public function apiShow(string $restaurantSlug, string $tableUuid): JsonResponse
    {
        // 1. Find the restaurant by slug
        $restaurant = Restaurant::where('slug', $restaurantSlug)
            ->where('is_active', true)
            ->first();

        if (! $restaurant) {
            return response()->json([
                'message' => 'Restaurant not found or is currently inactive.',
            ], 404);
        }

        // 2. Validate the table UUID belongs to this restaurant
        $table = RestaurantTable::where('uuid', $tableUuid)
            ->where('restaurant_id', $restaurant->id)
            ->where('is_active', true)
            ->first();

        if (! $table) {
            return response()->json([
                'message'    => 'Table not found or is inactive.',
                'restaurant' => $restaurant,
                'uuid'       => $tableUuid,
                'details'    => 'Please ensure you are scanning the QR code at a valid table.',
            ], 404);
        }

        // 3. Fetch active categories for this restaurant, ordered by sort_order
        $categories = MenuCategory::where('restaurant_id', $restaurant->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'description', 'image', 'sort_order']);

        // 4. Fetch all menu items for this restaurant (available only)
        $items = MenuItem::where('restaurant_id', $restaurant->id)
            ->where('is_available', true)
            ->orderBy('name')
            ->get([
                'id',
                'menu_category_id',
                'name',
                'description',
                'price',
                'image',
                'is_available',
                'is_featured',
                'dietary_info',
            ]);

        // 5. Check if there is an active session for this device on this table
        $deviceId          = request()->query('device_id');
        $activeSessionUuid = null;

        if ($deviceId) {
            $existingSession = $table->activeSessions()
                ->where('device_id', $deviceId)
                ->first();

            if ($existingSession) {
                $activeSessionUuid = $existingSession->uuid;
            }
        }
        /**
         * increment the menu views for the restaurant
         */
        DailyRestaurantStat::updateOrCreate(
            ['restaurant_id' => $restaurant->id, 'date' => now()->toDateString()],
            []
        )->increment('menu_views');

        return response()->json([
            'restaurant' => [
                'id'                 => $restaurant->id,
                'name'               => $restaurant->name,
                'description'        => $restaurant->description,
                'logo'               => $restaurant->logo_path,
                'logo_url'           => $restaurant->logo_url,
                'address'            => $restaurant->address,
                'phone'              => $restaurant->phone,
                'email'              => $restaurant->email,
                'google_review_link' => $restaurant->google_review_link,
                'reviews'            => $restaurant->reviews()
                    ->where('is_public', true)
                    ->latest()
                    ->take(20)
                    ->get(['id', 'rating', 'comment', 'created_at']),
            ],
            'table'      => [
                'id'                  => $table->id,
                'uuid'                => $table->uuid,
                'table_number'        => $table->table_number,
                'section'             => $table->section,
                'capacity'            => $table->capacity,
                'status'              => $table->status,
                'active_session_uuid' => $activeSessionUuid,
            ],
            'categories' => $categories,
            'items'      => $items,
        ]);
    }

    /**
     * ---------------------------------------------------------------
     * PUBLIC API — Call waiter to the table
     *
     * POST /api/waiter/call
     * Body: { restaurant_id, table_uuid }
     * ---------------------------------------------------------------
     */
    public function callWaiter(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'restaurant_id' => ['required', 'integer', 'exists:restaurants,id'],
            'table_uuid'    => ['required', 'string'],
        ]);

        $table = RestaurantTable::where('uuid', $validated['table_uuid'])
            ->where('restaurant_id', $validated['restaurant_id'])
            ->where('is_active', true)
            ->first();

        if (! $table) {
            return response()->json(['message' => 'Table not found.'], 404);
        }

        // Create WaiterCall record, which triggers WaiterCallObserver
        WaiterCall::create([
            'restaurant_id'       => $validated['restaurant_id'],
            'restaurant_table_id' => $table->id,
            'table_session_id'    => $table->activeSession->id ?? null,
            'status'              => 'pending',
        ]);

        return response()->json([
            'message'      => 'Waiter has been notified.',
            'table_number' => $table->table_number,
        ]);
    }

    /**
     * ---------------------------------------------------------------
     * PUBLIC API — Place an order
     *
     * POST /api/orders
     * Body: {
     *   restaurant_id,
     *   table_uuid,
     *   note (optional),
     *   items: [{ menu_item_id, quantity, price }]
     * }
     * ---------------------------------------------------------------
     */
    public function placeOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'restaurant_id'           => ['required', 'integer', 'exists:restaurants,id'],
            'table_uuid'              => ['required', 'string'],
            'device_id'               => ['required', 'string', 'max:64'],
            'note'                    => ['nullable', 'string', 'max:500'],
            'items'                   => ['required', 'array', 'min:1'],
            'items.*.menu_item_id'    => ['required', 'integer'],
            'items.*.quantity'        => ['required', 'integer', 'min:1', 'max:99'],
            'items.*.price'           => ['required', 'numeric', 'min:0'],
            'items.*.special_request' => ['nullable', 'string', 'max:255'],
        ]);

        // Verify the table belongs to this restaurant and is active
        $table = RestaurantTable::where('uuid', $validated['table_uuid'])
            ->where('restaurant_id', $validated['restaurant_id'])
            ->where('is_active', true)
            ->first();

        if (! $table) {
            return response()->json(['message' => 'Table not found or is inactive.'], 404);
        }

        // Verify all menu items exist, are available, and belong to this restaurant
        $incomingIds = collect($validated['items'])->pluck('menu_item_id');

        $validItems = MenuItem::whereIn('id', $incomingIds)
            ->where('restaurant_id', $validated['restaurant_id'])
            ->where('is_available', true)
            ->get()
            ->keyBy('id'); // key by id for fast lookup below

        $invalidIds = $incomingIds->diff($validItems->keys());
        if ($invalidIds->isNotEmpty()) {
            return response()->json([
                'message'          => 'One or more items are unavailable or invalid.',
                'invalid_item_ids' => $invalidIds->values(),
            ], 422);
        }

        // Resolve or create the active session for this table + device
        //    - Look for an active session matching this device_id on this table
        //    - If none, open a new session automatically (customer self-seated via QR)
        DB::beginTransaction();

        try {
            $deviceId = $validated['device_id'];

            // Find an active session for this specific device on this table
            $session = $table->activeSessions()
                ->where('device_id', $deviceId)
                ->first();

            // If no session for this device, create a new one
            if (! $session) {
                $session = $table->openSession(
                    $validated['restaurant_id'],
                    null, // openedByAdminId
                    null, // guestCount
                    $deviceId
                );
            }

            // [SECURITY CHK] If any order in this session needs user re-confirmation, block new ones.
            $hasPendingReconfirmation = Order::where('table_session_id', $session->id)
                ->where('needs_user_confirmation', true)
                ->exists();

            if ($hasPendingReconfirmation) {
                return response()->json([
                    'message' => 'Please resolve your pending order reconfirmation before placing new orders.',
                ], 403);
            }

            // Build order total from server-side prices (never trust client price)
            $totalAmount = collect($validated['items'])->sum(function ($item) use ($validItems) {
                return $validItems[$item['menu_item_id']]->price * $item['quantity'];
            });

            // Create the order
            $order = Order::create([
                'table_session_id'    => $session->id,
                'restaurant_table_id' => $table->id,
                'restaurant_id'       => $validated['restaurant_id'],
                'note'                => $validated['note'] ?? null,
                'total_amount'        => $totalAmount,
                'status'              => 'pending',
                'is_paid'             => false,
            ]);

            // Create order items using server-side price snapshots
            foreach ($validated['items'] as $item) {
                $menuItem = $validItems[$item['menu_item_id']];

                OrderItem::create([
                    'order_id'        => $order->id,
                    'menu_item_id'    => $menuItem->id,
                    'restaurant_id'   => $validated['restaurant_id'],
                    'quantity'        => $item['quantity'],
                    'unit_price'      => $menuItem->price, // server snapshot, not client price
                    'subtotal'        => $menuItem->price * $item['quantity'],
                    'special_request' => $item['special_request'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'message'      => 'Order placed successfully!',
                'order_uuid'   => $order->uuid,
                'session_uuid' => $session->uuid,
                'total'        => number_format($totalAmount, 2),
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to place order. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * PUBLIC API — Fetch all orders for a given table session.
     *
     * GET /api/sessions/{session_uuid}/orders
     *
     * Returns orders most-recent-first, each with their items and menu item name.
     * Only active sessions are accessible — prevents fishing for other tables' data.
     */
    public function getSessionOrders(string $sessionUuid): JsonResponse
    {
        $session = TableSession::where('uuid', $sessionUuid)
            ->where('status', 'active')
            ->with('restaurant')
            ->first();

        if (! $session) {
            return response()->json(['message' => 'Session not found or already closed.'], 404);
        }

        $orders = Order::where('table_session_id', $session->id)
            ->with([
                'items' => function ($query) {
                    $query->select(
                        'id', 'order_id', 'menu_item_id',
                        'quantity', 'unit_price', 'subtotal',
                        'is_cancelled', 'cancellation_note'
                    );
                },
                'items.menuItem:id,name,image',
            ])
            ->orderByDesc('created_at')
            ->get([
                'id', 'uuid', 'status', 'is_paid', 'paid_at',
                'total_amount', 'note', 'created_at', 'needs_user_confirmation',
            ]);

        $orders->each(function ($order) {
            $order->items->each(function ($item) {
                // Ensure cancellation fields are loaded
                $item->makeVisible(['is_cancelled', 'cancellation_note']);
            });
        });

        return response()->json([
            'restaurant' => [
                'id'                 => $session->restaurant->id,
                'name'               => $session->restaurant->name,
                'logo'               => $session->restaurant->logo_path,
                'google_review_link' => $session->restaurant->google_review_link,
            ],
            'payment_qr' => $session->restaurant?->payment_qr,
            'session' => [
                'uuid'        => $session->uuid,
                'status'      => $session->status,
                'opened_at'   => $session->opened_at,
                'grand_total' => number_format($session->grandTotal(), 2),
            ],
            'orders'  => $orders,
        ]);
    }

    /**
     * PUBLIC API — Cancel a pending order (within 2 minutes of creation).
     *
     * POST /api/orders/{orderUuid}/cancel
     */
    public function cancelOrder(string $orderUuid): JsonResponse
    {
        $order = Order::where('uuid', $orderUuid)
            ->where('status', 'pending')
            ->first();

        if (! $order) {
            return response()->json(['message' => 'Order not found or cannot be cancelled.'], 404);
        }

        // Check if order was created within the last 2 minutes
        if ($order->created_at->diffInMinutes(now()) >= 2) {
            return response()->json([
                'message' => 'Orders can only be cancelled within 2 minutes of being placed.',
            ], 422);
        }

        $order->update(['status' => 'cancelled']);

        // Notify client and staff
        OrderStatusUpdated::dispatch($order);

        return response()->json(['message' => 'Order cancelled successfully.']);
    }

    /**
     * Admin requests user to reconfirm the order.
     */
    public function adminRequestConfirmation(string $orderUuid): JsonResponse
    {
        $order = Order::where('uuid', $orderUuid)->firstOrFail();
        
        $order->update(['needs_user_confirmation' => true]);

        // Notify client
        \App\Events\OrderStatusUpdated::dispatch($order);

        return response()->json(['message' => 'Confirmation requested from user.']);
    }

    /**
     * User confirms the order.
     */
    public function userConfirmOrder(string $orderUuid): JsonResponse
    {
        $order = Order::where('uuid', $orderUuid)->firstOrFail();
        
        $order->update(['needs_user_confirmation' => false]);

        // Notify staff
        \App\Events\OrderStatusUpdated::dispatch($order);

        return response()->json(['message' => 'Order confirmed successfully.']);
    }

    /**
     * Store a customer review.
     * POST /api/reviews
     */
    public function storeReview(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'restaurant_id'        => ['required', 'integer', 'exists:restaurants,id'],
            'rating'               => ['required', 'integer', 'min:1', 'max:5'],
            'comment'              => ['nullable', 'string', 'max:1000'],
            'redirected_to_google' => ['required', 'boolean'],
        ]);

        \App\Models\Review::create([
            'restaurant_id'        => $validated['restaurant_id'],
            'rating'               => $validated['rating'],
            'comment'              => $validated['comment'] ?? null,
            'source'               => $validated['redirected_to_google'] ? 'google_redirect' : 'internal',
            'redirected_to_google' => $validated['redirected_to_google'],
            'ip_address'           => $request->ip(),
            'user_agent'           => $request->userAgent(),
            'is_public'            => false,
        ]);

        return response()->json(['message' => 'Review submitted successfully.']);
    }
}
