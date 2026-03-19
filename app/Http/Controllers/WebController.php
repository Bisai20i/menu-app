<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\SubscriptionPlan;
use App\Models\Testimonial;

class WebController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::where('published', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $testimonials = Testimonial::where('published', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $faqs = Faq::where('published', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('web.index', compact('plans', 'testimonials', 'faqs'));
    }
}
