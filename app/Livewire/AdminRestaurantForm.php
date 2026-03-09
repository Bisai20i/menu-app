<?php

namespace App\Livewire;

use App\Models\Admin;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class AdminRestaurantForm extends Component
{
    public $adminId; // If editing

    // Admin Fields
    public $name;
    public $email;
    public $password;
    public $role = 'admin';

    // Restaurant Fields
    public $restaurant_name;
    public $currency = 'NPR';
    public $tax_percentage = 0;
    public $primary_color = '#000000';

    protected function rules()
    {
        return [
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:admins,email,' . $this->adminId,
            'password'        => $this->adminId ? 'nullable|min:8' : 'required|min:8',
            'restaurant_name' => 'required|string|max:255',
            'currency'        => 'required|string|max:10',
            'tax_percentage'  => 'required|numeric|min:0|max:100',
            'primary_color'   => 'required|string|max:7',
        ];
    }

    public function mount($id = null)
    {
        if ($id) {
            $admin = Admin::with('restaurant')->findOrFail($id);
            $this->adminId         = $admin->id;
            $this->name            = $admin->name;
            $this->email           = $admin->email;
            $this->role            = $admin->role;

            if ($admin->restaurant) {
                $this->restaurant_name = $admin->restaurant->name;
                $this->currency        = $admin->restaurant->settings['currency'] ?? 'NPR';
                $this->tax_percentage  = $admin->restaurant->settings['tax_percentage'] ?? 0;
                $this->primary_color   = $admin->restaurant->settings['primary_color'] ?? '#000000';
            }
        }
    }

    public function save()
    {
        $this->validate();

        $restaurantData = [
            'name'     => $this->restaurant_name,
            'settings' => [
                'currency'       => $this->currency,
                'tax_percentage' => (float) $this->tax_percentage,
                'primary_color'  => $this->primary_color,
            ],
        ];

        if ($this->adminId) {
            $admin = Admin::findOrFail($this->adminId);
            $admin->update([
                'name'  => $this->name,
                'email' => $this->email,
            ]);

            if ($this->password) {
                $admin->update(['password' => Hash::make($this->password)]);
            }

            if ($admin->restaurant) {
                $admin->restaurant->update($restaurantData);
            } else {
                $restaurant = Restaurant::create($restaurantData);
                $admin->update(['restaurant_id' => $restaurant->id]);
            }

            session()->flash('success', 'Admin and Restaurant updated successfully.');
        } else {
            $restaurant = Restaurant::create($restaurantData);

            Admin::create([
                'name'          => $this->name,
                'email'         => $this->email,
                'password'      => Hash::make($this->password),
                'role'          => $this->role,
                'restaurant_id' => $restaurant->id,
            ]);

            session()->flash('success', 'Admin and Restaurant created successfully.');
        }

        return redirect()->route('master.admin-management.index');
    }

    public function render()
    {
        return view('livewire.admin-restaurant-form');
    }
}
