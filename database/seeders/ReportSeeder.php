<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\DailyRestaurantStat;
use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create 5 additional restaurants if they don't exist
        for ($i = 1; $i <= 5; $i++) {
            $name = "Flavors of Nepal {$i}";
            $slug = Str::slug($name);

            // Skip if exists
            if (Restaurant::where('slug', $slug)->exists()) continue;

            $res = Restaurant::create([
                'name' => $name,
                'slug' => $slug,
                'email' => "restaurant{$i}@example.com",
                'phone' => "984123456{$i}",
                'address' => "City Center, Kathmandu",
                'settings' => ['currency' => 'NPR', 'tax_percentage' => 13],
                'is_active' => true,
            ]);

            Admin::create([
                'name' => "Admin {$res->name}",
                'email' => "owner{$i}@example.com",
                'password' => bcrypt('password'),
                'role' => 'admin',
                'restaurant_id' => $res->id,
                'is_active' => true,
            ]);
        }

        // 2. Clear existing stats to avoid duplicates if re-running
        // DailyRestaurantStat::truncate(); // Optional: uncomment if you want fresh stats every time

        // 3. Generate 60 days of historical stats for EVERY restaurant
        $restaurants = Restaurant::all();
        $startDate = Carbon::now()->subDays(60);

        foreach ($restaurants as $restaurant) {
            $this->command->info("Seeding stats for: {$restaurant->name}");
            
            for ($day = 0; $day <= 60; $day++) {
                $date = (clone $startDate)->addDays($day);
                
                // Use updateOrCreate to prevent unique constraint issues if date/restaurant_id is unique
                DailyRestaurantStat::updateOrCreate(
                    [
                        'restaurant_id' => $restaurant->id,
                        'date' => $date->toDateString(),
                    ],
                    [
                        'menu_views' => rand(100, 800),
                        'total_orders' => rand(10, 60),
                        'total_revenue' => rand(2500, 20000),
                    ]
                );
            }
        }

        $this->command->info('Reports dummy data seeded successfully!');
    }
}
