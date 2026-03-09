<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Restaurant;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of the admins.
     */
    public function index()
    {
        return view('admin.admin-management.index', [
            'model'       => 'Admin',
            'routePrefix' => 'master.admin-management',
        ]);
    }

    /**
     * Show the form for creating a new admin and restaurant.
     */
    public function create()
    {
        return view('admin.admin-management.create');
    }

    /**
     * Show the form for editing the specified admin.
     */
    public function edit($id)
    {
        return view('admin.admin-management.edit', compact('id'));
    }

    /**
     * Show the profile of the currently logged in admin.
     */
    public function profile()
    {
        $admin = auth()->guard('admin')->user()->load('restaurant');
        return view('admin.profile', compact('admin'));
    }

    /**
     * Update the profile and restaurant settings of the currently logged in admin.
     */
    public function updateProfile(Request $request)
    {
        $admin      = auth()->guard('admin')->user();
        $restaurant = $admin->restaurant;

        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:admins,email,' . $admin->id,
            'password'        => 'nullable|min:8|confirmed',
            'image'           => 'nullable|image|max:2048',
            
            'restaurant_name' => 'required|string|max:255',
            'restaurant_slug' => 'required|string|max:255|unique:restaurants,slug,' . ($restaurant->id ?? 0),
            'restaurant_email'=> 'nullable|email|max:255',
            'restaurant_phone'=> 'nullable|string|max:20',
            'restaurant_address'=> 'nullable|string|max:500',
            'restaurant_description'=> 'nullable|string|max:1000',
            'restaurant_logo' => 'nullable|image|max:2048',
            
            'currency'        => 'required|string|max:10',
            'tax_percentage'  => 'required|numeric|min:0|max:100',
            'primary_color'   => 'required|string|max:7',
        ]);

        // Update Admin
        $adminData = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $adminData['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            if ($admin->image) {
                Storage::disk('public')->delete($admin->image);
            }
            $adminData['image'] = $request->file('image')->store('admins', 'public');
        }

        try {
            DB::beginTransaction();
            $admin->update($adminData);

            // Restaurant Data
            $restaurantData = [
                'name'        => $request->restaurant_name,
                'slug'        => $request->restaurant_slug,
                'email'       => $request->restaurant_email,
                'phone'       => $request->restaurant_phone,
                'address'     => $request->restaurant_address,
                'description' => $request->restaurant_description,
                'settings'    => [
                    'currency'       => $request->currency,
                    'tax_percentage' => (float) $request->tax_percentage,
                    'primary_color'  => $request->primary_color,
                ],
            ];

            if ($request->hasFile('restaurant_logo')) {
                if ($restaurant && $restaurant->logo_path) {
                    Storage::disk('public')->delete($restaurant->logo_path);
                }
                $restaurantData['logo_path'] = $request->file('restaurant_logo')->store('restaurants', 'public');
            }

            if ($restaurant) {
                $restaurant->update($restaurantData);
            } else {
                $newRestaurant = Restaurant::create($restaurantData);
                $admin->update(['restaurant_id' => $newRestaurant->id]);
            }

            DB::commit();
            return back()->with('success', 'Profile and Restaurant information updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
