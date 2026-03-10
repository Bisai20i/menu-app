<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\AdminSubscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Carbon;

class SubscriptionService
{
    /**
     * Get the currently active plan for an admin or a default Free plan
     */
    public function getActivePlan(Admin $admin)
    {
        if ($admin->is_super_admin) {
             return null; // Super admins don't need a formal tracking plan physically
        }

        $activeSubscription = $admin->activeSubscription;

        if ($activeSubscription && $activeSubscription->isActive()) {
            return $activeSubscription->plan;
        }

        // Return a virtual Free plan object
        $freePlan = new SubscriptionPlan([
            'name'           => 'Free Plan',
            'price'          => 0,
            'duration_value' => 0,
            'duration_unit'  => 'Forever',
            'features'       => [],
        ]);
        // Set an arbitrary ID to differentiate from null/database records
        $freePlan->id = 0; 
        
        return $freePlan;
    }

    /**
     * Assign a plan to an admin
     */
    public function assignPlan(Admin $admin, SubscriptionPlan $plan): AdminSubscription
    {
        // First, expire any current active subscriptions
        $this->removePlan($admin);

        // Calculate expiration date
        $expiresAt = match ($plan->duration_unit) {
            'day'   => Carbon::now()->addDays($plan->duration_value),
            'month' => Carbon::now()->addMonths($plan->duration_value),
            'year'  => Carbon::now()->addYears($plan->duration_value),
            default => null, // Shouldn't hit default normally
        };

        return AdminSubscription::create([
            'admin_id'             => $admin->id,
            'subscription_plan_id' => $plan->id,
            'starts_at'            => Carbon::now(),
            'expires_at'           => $expiresAt,
            'status'               => 'active',
        ]);
    }

    /**
     * Remove or mark current subscription as expired
     */
    public function removePlan(Admin $admin): void
    {
        AdminSubscription::where('admin_id', $admin->id)
            ->where('status', 'active')
            ->update([
                'status'     => 'expired',
                'expires_at' => Carbon::now(),
            ]);
    }

    /**
     * Get billing history for an admin (all subscriptions, newest first)
     */
    public function getBillingHistory(Admin $admin)
    {
        return AdminSubscription::with('plan')
            ->where('admin_id', $admin->id)
            ->latest('starts_at')
            ->get();
    }
}
