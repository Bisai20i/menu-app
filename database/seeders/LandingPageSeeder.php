<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\SubscriptionPlan;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class LandingPageSeeder extends Seeder
{
    public function run(): void
    {
        $admin = \App\Models\Admin::first();
        $adminId = $admin ? $admin->id : 1;

        // Subscription Plans
        SubscriptionPlan::create([
            'admin_id' => $adminId,
            'name' => 'Starter',
            'price' => 0,
            'duration_unit' => 'month',
            'duration_value' => 1,
            'currency' => '$',
            'features' => [
                'PDF / image menu',
                'One QR code',
                'Basic analytics'
            ],
            'published' => true,
            'sort_order' => 1,
        ]);

        SubscriptionPlan::create([
            'admin_id' => $adminId,
            'name' => 'Pro',
            'price' => 29,
            'duration_unit' => 'month',
            'duration_value' => 1,
            'currency' => '$',
            'features' => [
                'Digital menu management',
                'Table-specific QRs',
                'Order tracking + payments',
                'Priority support'
            ],
            'published' => true,
            'sort_order' => 2,
        ]);

        // Testimonials
        Testimonial::create([
            'name' => 'Alex',
            'designation' => 'Bar Manager',
            'content' => 'We turned around tables 20% faster. Guests love the freedom to order when they\'re ready.',
            'published' => true,
            'sort_order' => 1,
        ]);

        Testimonial::create([
            'name' => 'Priya',
            'designation' => 'Restaurant Owner',
            'content' => 'Guests love not waiting for the check. It\'s the small things that make a big difference in hospitality.',
            'published' => true,
            'sort_order' => 2,
        ]);

        Testimonial::create([
            'name' => 'Jordan',
            'designation' => 'General Manager',
            'content' => 'Setup took 10 minutes. The team was skeptical at first, but now they can\'t imagine service without it.',
            'published' => true,
            'sort_order' => 3,
        ]);

        // FAQs
        Faq::create([
            'question' => 'Do guests need to download an app?',
            'answer' => 'No. It works in the camera or browser. Guests simply point their phone at the QR code and the menu loads instantly—no downloads, no sign-ups, no friction.',
            'published' => true,
            'sort_order' => 1,
        ]);

        Faq::create([
            'question' => 'Can we update the menu daily?',
            'answer' => 'Yes. Changes publish instantly. Update prices, add specials, mark items as sold out—all in real-time from your dashboard.',
            'published' => true,
            'sort_order' => 2,
        ]);

        // Testimonial Images
        // Since we don't have the actual images, we will just use the placeholder logic in the view

        Faq::create([
            'question' => 'Is it secure for payments?',
            'answer' => 'Yes. PCI-compliant checkout with bank-level encryption. We never store card details on our servers.',
            'published' => true,
            'sort_order' => 3,
        ]);

        Faq::create([
            'question' => 'What if the internet is spotty?',
            'answer' => 'Codes load lightweight pages designed for weak signal. The menu works even on slow connections, and orders queue until connectivity returns.',
            'published' => true,
            'sort_order' => 4,
        ]);
    }
}
