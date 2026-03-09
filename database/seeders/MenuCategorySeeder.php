<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class MenuCategorySeeder extends Seeder
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

        $categories = [
            [
                'name' => 'Appetizers',
                'slug' => 'appetizers',
                'description' => 'Perfect starters to kick off your meal',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Main Course',
                'slug' => 'main-course',
                'description' => 'Hearty and delicious entrees',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Desserts',
                'slug' => 'desserts',
                'description' => 'Sweet treats to end your dinner',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Beverages',
                'slug' => 'beverages',
                'description' => 'Refreshing drinks and cocktails',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            MenuCategory::create(array_merge($category, [
                'restaurant_id' => $restaurant->id,
            ]));
        }
    }
}
