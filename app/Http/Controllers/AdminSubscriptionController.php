<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\SubscriptionPlan;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class AdminSubscriptionController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function index(Request $request)
    {
        $query = Admin::where('role', '!=', 'superadmin');

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($status = $request->input('status')) {
            if ($status === 'active') {
                $query->whereHas('activeSubscription');
            } elseif ($status === 'expired') {
                $query->whereDoesntHave('activeSubscription')
                      ->whereHas('subscriptions', function ($q) {
                          $q->where('status', 'expired')
                            ->orWhere(function ($subQ) {
                                $subQ->where('status', 'active')
                                     ->whereNotNull('expires_at')
                                     ->whereRaw('DATE_ADD(expires_at, INTERVAL grace_period DAY) <= ?', [now()]);
                            });
                      });
            } elseif ($status === 'no_subscription') {
                $query->doesntHave('subscriptions');
            }
        }

        $admins = $query->get();
        
        // Sorting using Collections
        $sort = $request->input('sort');
        if ($sort === 'new_sub') {
            $admins = $admins->sortByDesc(fn($admin) => $admin->activeSubscription->starts_at ?? null)->values();
        } elseif ($sort === 'expiring_soon') {
            $admins = $admins->sortBy(function($admin) {
                if (!$admin->activeSubscription || !$admin->activeSubscription->expires_at) {
                    return '9999-12-31';
                }
                return $admin->activeSubscription->expires_at;
            })->values();
        }
        
        // Enhance admins with their current active plan
        foreach ($admins as $admin) {
            $admin->current_plan = $this->subscriptionService->getActivePlan($admin);
        }

        $plans = SubscriptionPlan::where('published', true)->get();

        return view('admin.admin-subscriptions.index', compact('admins', 'plans'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'admin_id'               => 'required|exists:admins,id',
            'subscription_plan_id'   => 'required|exists:subscription_plans,id',
            'grace_period'           => 'nullable|integer|min:0',
            'custom_duration_months' => 'nullable|integer|min:1',
        ]);

        $admin = Admin::findOrFail($request->admin_id);
        
        // Ensure superadmins don't get assigned plans here
        if ($admin->is_super_admin) {
            return back()->with('error', 'Cannot assign plans to Super Admins.');
        }

        $plan = SubscriptionPlan::findOrFail($request->subscription_plan_id);
        $gracePeriod = $request->input('grace_period', 30);
        $customDuration = $request->input('custom_duration_months');

        $this->subscriptionService->assignPlan($admin, $plan, $gracePeriod, $customDuration);

        return back()->with('success', "Plan '{$plan->name}' assigned to {$admin->name} successfully.");
    }

    public function remove($adminId)
    {
        $admin = Admin::findOrFail($adminId);
        
        $this->subscriptionService->removePlan($admin);

        return back()->with('success', "Subscription plan removed for {$admin->name}. They are now on the Free Plan.");
    }

    public function billing()
    {
        $admin          = auth('admin')->user();
        $currentPlan    = $this->subscriptionService->getActivePlan($admin);
        $billingHistory = $this->subscriptionService->getBillingHistory($admin);
        $plans          = SubscriptionPlan::where('published', true)->orderBy('sort_order')->get();

        return view('admin.admin-subscriptions.billing', compact('admin', 'currentPlan', 'billingHistory', 'plans'));
    }
}
