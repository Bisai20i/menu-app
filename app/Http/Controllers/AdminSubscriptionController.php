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

    public function index()
    {
        $admins = Admin::where('role', '!=', 'superadmin')->get();
        
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
            'admin_id'             => 'required|exists:admins,id',
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $admin = Admin::findOrFail($request->admin_id);
        
        // Ensure superadmins don't get assigned plans here
        if ($admin->is_super_admin) {
            return back()->with('error', 'Cannot assign plans to Super Admins.');
        }

        $plan = SubscriptionPlan::findOrFail($request->subscription_plan_id);

        $this->subscriptionService->assignPlan($admin, $plan);

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
