<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $restaurant = Restaurant::first();
        
        if (!$restaurant) {
            return;
        }

        $appetizers = MenuCategory::where('name', 'Appetizers')->first();
        $mainCourse = MenuCategory::where('name', 'Main Course')->first();
        $desserts = MenuCategory::where('name', 'Desserts')->first();
        $beverages = MenuCategory::where('name', 'Beverages')->first();

        $items = [
            // Appetizers
            [
                'menu_category_id' => $appetizers->id,
                'name' => 'Crispy Chicken Wings',
                'description' => 'Spicy buffalo wings served with ranch dip',
                'price' => 450.00,
                'is_available' => true,
                'is_featured' => true,
                'dietary_info' => ['spicy'],
            ],
            [
                'menu_category_id' => $appetizers->id,
                'name' => 'Garlic Bread sticks',
                'description' => 'Freshly baked with garlic butter and herbs',
                'price' => 250.00,
                'is_available' => true,
                'is_featured' => false,
                'dietary_info' => ['vegetarian'],
            ],
            // Main Course
            [
                'menu_category_id' => $mainCourse->id,
                'name' => 'Grilled Chicken Steak',
                'description' => 'Served with mashed potatoes and seasonal vegetables',
                'price' => 750.00,
                'is_available' => true,
                'is_featured' => true,
                'dietary_info' => ['high-protein'],
            ],
            [
                'menu_category_id' => $mainCourse->id,
                'name' => 'Paneer Butter Masala',
                'description' => 'Cottage cheese in a rich tomato-based gravy',
                'price' => 550.00,
                'is_available' => true,
                'is_featured' => false,
                'dietary_info' => ['vegetarian'],
            ],
            // Desserts
            [
                'menu_category_id' => $desserts->id,
                'name' => 'Chocolate Lave Cake',
                'description' => 'Warm cake with a gooey chocolate center',
                'price' => 350.00,
                'is_available' => true,
                'is_featured' => true,
                'dietary_info' => [],
            ],
            // Beverages
            [
                'menu_category_id' => $beverages->id,
                'name' => 'Fresh Lemonade',
                'description' => 'Refreshing house-made lemonade',
                'price' => 150.00,
                'is_available' => true,
                'is_featured' => false,
                'dietary_info' => ['vegan'],
            ],
        ];

        foreach ($items as $item) {
            MenuItem::create(array_merge($item, [
                'restaurant_id' => $restaurant->id,
            ]));
        }
    }
}
