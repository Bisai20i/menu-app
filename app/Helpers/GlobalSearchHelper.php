<?php

namespace App\Helpers;

use App\Models\Admin;

class GlobalSearchHelper
{
    private static function hasRestaurant(Admin $admin): bool
    {
        return isset($admin->restaurant_id) && !empty($admin->restaurant_id);
    }

    public static function isSearchEnabled(?Admin $admin): bool
    {
        if (! $admin) {
            return false;
        }

        // Free-plan admins should not see the global search UI.
        return $admin->is_super_admin || $admin->hasActiveSubscription();
    }

    private static function mk(string $label, string $routeName, string $keywords = ''): array
    {
        return [
            'label' => $label,
            'url' => route($routeName),
            // Lowercase to simplify matching in the frontend.
            'keywords' => strtolower(trim($keywords !== '' ? $keywords : $label)),
        ];
    }

    /**
     * Build searchable navigation destinations for the given admin.
     *
     * IMPORTANT: This only returns routes (no data), and super-admin-only
     * destinations are only included when the admin is actually a super admin.
     */
    public static function itemsForAdmin(?Admin $admin): array
    {
        if (! self::isSearchEnabled($admin)) {
            return [];
        }

        if (! $admin) {
            return [];
        }

        $hasRestaurant = self::hasRestaurant($admin);

        $canManageOrder = ($admin->is_super_admin || $admin->hasActiveSubscription()) && $hasRestaurant;
        $canCreateMenu = ($admin->is_super_admin || $admin->hasActiveSubscription()) && $hasRestaurant;
        $canCreateRestaurantTables = ($admin->is_super_admin || $admin->hasActiveSubscription()) && $hasRestaurant;

        $items = [];

        // Always available within the subscribed/super-admin global search scope.
        $items[] = self::mk('Dashboard', 'master.dashboard', 'dashboard home');
        $items[] = self::mk('Menu Gallery', 'master.menu-gallery.index', 'gallery menu images');
        $items[] = self::mk('Billing', 'master.billing', 'billing subscription payment');
        $items[] = self::mk('Profile', 'master.profile', 'profile manage account');
        $items[] = self::mk('Notifications', 'master.notifications.index', 'notifications bell');

        // Menu categories/items are subscription gated (and require restaurant_id).
        if ($canCreateMenu) {
            $items[] = self::mk(
                'Menu Categories',
                'master.menu-categories.index',
                'categories menu menu-categories'
            );
            $items[] = self::mk(
                'Menu Items',
                'master.menu-items.index',
                'items menu menu-items dishes drinks'
            );
        }

        // Tables and QR codes are subscription gated.
        if ($canCreateRestaurantTables) {
            $items[] = self::mk(
                'Restaurant Tables',
                'master.restaurant-tables.index',
                'tables restaurant tables dining section capacity'
            );
            $items[] = self::mk(
                'Table QR Codes',
                'master.table-qr',
                'qr codes table restaurant tables'
            );
        }

        // Orders + sessions are subscription gated.
        if ($canManageOrder) {
            $items[] = self::mk('Order Management', 'master.orders.index', 'orders order management receipts');
            $items[] = self::mk('Table Sessions', 'master.table-sessions', 'table sessions open session');
        }

        // Super-admin-only system pages.
        if ($admin->is_super_admin) {
            $items[] = self::mk('Reports', 'master.reports.index', 'reports statistics charts');
            $items[] = self::mk('Testimonials', 'master.testimonials.index', 'testimonials reviews feedback');
            $items[] = self::mk('Faqs', 'master.faqs.index', 'faqs help questions');
            $items[] = self::mk('Subscription Plans', 'master.subscription-plans.index', 'plans subscription pricing');
            $items[] = self::mk('Admin Management', 'master.admin-management.index', 'admin management accounts');
            $items[] = self::mk('Admin Subscriptions', 'master.admin-subscriptions.index', 'admin subscriptions billing plans');
        }

        return $items;
    }
}

