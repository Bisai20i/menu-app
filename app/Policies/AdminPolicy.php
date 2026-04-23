<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\RestaurantTable;

class AdminPolicy
{
    /**
     * Create a new policy instance.
     */
    protected function hasSubscribed(Admin $admin)
    {
        return isset($admin->restaurant_id) && ($admin->is_super_admin || $admin->hasActiveSubscription());
    }
    public function accessCreateMenu(Admin $admin)
    {
        return $this->hasSubscribed($admin); // Only subscribed admins can create menus
    }

    public function accessCreateGallery(Admin $admin)
    {
        return isset($admin->restaurant_id); // All admins can access gallery
    }

    public function accessCreateRestaurantTable(Admin $admin)
    {
        return $this->hasSubscribed($admin); // Only subscribed admins can create restaurant tables
    }


    public function deleteRecord(Admin $admin, $record)
    {
        // Check if the record belongs to the admin's restaurant or if the admin is a super admin
        return $admin->is_super_admin || (isset($record->restaurant_id) && $admin->restaurant_id === $record->restaurant_id);
    }

    public function updateRecord(Admin $admin, $record)
    {
        // Check if the record belongs to the admin's restaurant or if the admin is a super admin
        return $admin->is_super_admin || (isset($record->restaurant_id) && $admin->restaurant_id === $record->restaurant_id);
    }

}
