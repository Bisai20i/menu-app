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
            'restaurant_email'=> 'nullable|email|max:255',
            'restaurant_phone'=> 'nullable|string|max:20',
            'restaurant_address'=> 'nullable|string|max:500',
            'restaurant_description'=> 'nullable|string|max:1000',
            'restaurant_logo' => 'nullable|image|max:2048',
            
            'currency'        => 'required|string|max:10',
            'tax_percentage'  => 'required|numeric|min:0|max:100',
            'primary_color'   => 'required|string|max:7',
            'payment_qr_image'=> 'nullable|image|max:2048',
            // Backward compatible: allow old forms to paste a URL/data-url.
            'payment_qr'      => 'nullable|string|max:200000',
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
            $existingSettings = is_array($restaurant?->settings) ? $restaurant->settings : [];
            $paymentQrSetting = $existingSettings['payment_qr'] ?? null;

            if ($request->hasFile('payment_qr_image')) {
                // Delete previous file only if it looks like a local `storage/app/public/...` path.
                $existingPaymentQr = $existingSettings['payment_qr'] ?? null;
                $isLocalStoredPath = is_string($existingPaymentQr)
                    && ! \Illuminate\Support\Str::startsWith($existingPaymentQr, ['data:', 'http://', 'https://'])
                    && ! \Illuminate\Support\Str::startsWith($existingPaymentQr, '/');

                if ($isLocalStoredPath && Storage::disk('public')->exists($existingPaymentQr)) {
                    Storage::disk('public')->delete($existingPaymentQr);
                }

                $paymentQrPath = $request->file('payment_qr_image')->store('payment_qrs', 'public');
                $paymentQrSetting = $paymentQrPath ?: null;
            } elseif ($request->filled('payment_qr')) {
                $paymentQrRaw = $request->input('payment_qr');
                $paymentQrSetting = is_string($paymentQrRaw) ? trim($paymentQrRaw) : null;
                $paymentQrSetting = $paymentQrSetting !== '' ? $paymentQrSetting : null;
            }

            $restaurantData = [
                'name'        => $request->restaurant_name,
                'email'       => $request->restaurant_email,
                'phone'       => $request->restaurant_phone,
                'address'     => $request->restaurant_address,
                'description' => $request->restaurant_description,
                'settings'    => array_merge($existingSettings, [
                    'currency'       => $request->currency,
                    'tax_percentage' => (float) $request->tax_percentage,
                    'primary_color'  => $request->primary_color,
                    // Only changes when an image is uploaded (or a legacy `payment_qr` string is provided).
                    'payment_qr'     => $paymentQrSetting,
                ]),
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

    public function notifications()
    {
        return view('admin.all-notifications');
    }
}
