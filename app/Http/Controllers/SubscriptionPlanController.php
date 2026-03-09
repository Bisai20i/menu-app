<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends BaseCrudController
{
    protected $model       = SubscriptionPlan::class;
    protected $routePrefix = 'master.subscription-plans';

    protected array $jsonFields = ['features'];

    protected function rules($id = null): array
    {
        return [
            'name'             => 'required|string|max:255',
            'duration_value'   => 'required|integer|min:1',
            'duration_unit'    => 'required|in:day,month,year',
            'price'            => 'required|numeric|min:0',
            'currency'         => 'required|string',
            'published'        => 'required|boolean',
            'sort_order'       => 'nullable|integer',

            'features'         => 'nullable|array',
            'features.*.key'   => 'required|string',
            'features.*.value' => 'nullable',
        ];
    }

    // protected function beforeSave($request, $item = null): array
    // {
    //     $data = $request->all();

    //     // Normalize JSON key-value input
    //     if ($request->has('features')) {
    //         $data['features'] = collect($request->features)
    //             ->pluck('value', 'key')
    //             ->filter(fn ($v, $k) => $k !== '')
    //             ->toArray();
    //     }

    //     $data['admin_id'] = auth()->id();

    //     return $data;
    // }

    protected function fields($item = null): array
    {
        return [
            'name'           => [
                'type'        => 'text',
                'label'       => 'Plan Name',
                'placeholder' => 'e.g. Premium 10 Months',
                'required'    => true,
            ],

            'published'      => [
                'type'     => 'select',
                'label'    => 'Publication Status',
                'options'  => [
                    '1' => 'Published',
                    '0' => 'Unpublished',
                ],
                'required' => true,
                'column'   => 'col-md-3',
            ],
            'sort_order'     => [
                'type'   => 'number',
                'label'  => 'Sort Order',
                'help'   => 'Lowest order will display first.',
                'column' => 'col-md-3',
            ],

            'duration_value' => [
                'type'        => 'number',
                'label'       => 'Duration Value',
                'placeholder' => '4..',
                'required'    => true,
                'column'      => 'col-7 col-md-4',
                'help'        => 'Enter Duration in Number and select unit as month, year...',
            ],

            'duration_unit'  => [
                'type'     => 'select',
                'label'    => 'Duration Unit',
                'options'  => [
                    'day'   => 'Day',
                    'month' => 'Month',
                    'year'  => 'Year',
                ],
                'required' => true,
                'column'   => 'col-5 col-md-2',
            ],

            'price'          => [
                'type'        => 'float',
                'label'       => 'Price',
                'placeholder' => 'e.g. 1000',
                'required'    => true,
                'column'      => 'col-7 col-md-4 ',
            ],

            'currency'       => [
                'type'        => 'text',
                'label'       => 'Currency',
                'placeholder' => 'NPR, USD, $ ...',
                'column'      => 'col-5 col-md-2',
            ],

            'features'       => [
                'type'              => 'json',
                'label'             => 'Plan Features',
                'column'            => 'col-12',
                'key_placeholder'   => 'Feature key (job_posts)',
                'value_placeholder' => 'Value (50)',
                'help'              => 'Define plan features as key-value pairs',
            ],

        ];
    }

    //show subscriptions plan lists for manpowers
    public function show()
    {
        $admin = auth('admin')->user();
        
        // 1. Get active subscription (if exists)
        $activeSubscription = $admin->activeSubscription;
        
        // 2. Fetch only published plans ordered by sort_order
        $plans = SubscriptionPlan::where('published', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        // 3. Metadata for the view
        $title = "Subscriptions";

        return view('admin.subscriptions.index', compact('plans', 'activeSubscription', 'title'));
    }

    public function paymentPage(SubscriptionPlan $plan)
    {
        $title = "Complete Payment";
        return view('admin.subscriptions.payment', compact('plan', 'title'));
    }

    public function submitPayment(Request $request)
    {
        $request->validate([
            'plan_id'        => 'required|exists:subscription_plans,id',
            'screenshot'     => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'transaction_id' => 'nullable|string|max:100',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        // Store Screenshot
        $path = $request->file('screenshot')->store('payments', 'public');

        Payment::create([
            'admin_id'             => auth('admin')->id(),
            'subscription_plan_id' => $plan->id,
            'amount'               => $plan->price,
            'currency'             => $plan->currency,
            'screenshot'           => $path,
            'transaction_id'       => $request->transaction_id,
            'status'               => 'pending',
        ]);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Payment proof submitted. Admin will verify and activate your subscription soon.');
    }
}
