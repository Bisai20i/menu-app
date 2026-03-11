<?php
namespace App\Http\Controllers;

use App\Helpers\ColorHelper;
use App\Models\Admin;
use App\Models\MenuCategory;
use App\Models\MenuImage;
use App\Models\Restaurant;

class MenuController extends Controller
{
    public function show($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $palette = ColorHelper::generatePalette($restaurant->settings['primary_color'] ?? '#b8912a');

        $viewSimpleMenu = false;
        $admin = Admin::where('restaurant_id', $restaurant->id)
            ->whereNot('role', 'superadmin')
            ->where('is_active', true) //false for superadmin and true for restaurant admin
            ->first();
        if($admin)
            $viewSimpleMenu = $admin && $admin->hasActiveSubscription() ? false : true; 

        $galleryImages = MenuImage::where('restaurant_id', $restaurant->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

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
}
