<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Restaurant
        $restaurant = \App\Models\Restaurant::create([
            'name' => 'The Grand Bistro',
            'slug' => 'the-grand-bistro',
            'email' => 'bistro@example.com',
            'phone' => '9800000000',
            'address' => 'Kathmandu, Nepal',
            'settings' => [
                'currency' => 'NPR',
                'tax_percentage' => 13,
                'primary_color' => '#4e73df',
            ],
            'is_active' => true,
        ]);

        // 2. Create Super Admin linked to restaurant
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
            'restaurant_id' => $restaurant->id,
            'is_active' => true,
        ]);

        // 3. Call Menu Seeders
        $this->call([
            MenuCategorySeeder::class,
            MenuItemSeeder::class,
        ]);
    }
}
