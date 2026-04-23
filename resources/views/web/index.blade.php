@extends('layouts.web')

@section('title', 'Saral Menu - Digitalize your Restaurant')
@section('meta_title', 'Saral Menu - Smart QR Code Menu for Modern Restaurants')
@section('meta_description', 'Saral Menu is the fastest way for guests to order from their table using QR codes. No apps, no friction—just seamless ordering and payments.')
@section('meta_keywords', 'qr code menu, digital restaurant menu, contactless ordering, saral menu nepal, restaurant tech')

@section('content')
    @include('web.partials.hero')

    @include('web.partials.features')

    @include('web.partials.how-it-works')

    <!-- ═══════════════ PRICING ═══════════════ -->
    <section id="pricing"
        class="relative bg-brand-lightBeige py-16 sm:py-20 md:py-24 px-5 sm:px-8 md:px-12 lg:px-20 overflow-hidden text-gray-900">
        <div class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-40">
            <svg class="absolute top-[-10%] right-[-10%] w-[60%] h-[60%] text-[#f8e3c3]" viewBox="0 0 200 200"
                fill="currentColor">
                <path
                    d="M45.7,-77.4C58.1,-70.5,66.4,-55.9,73.1,-41.2C79.8,-26.5,84.9,-11.7,83.2,2.5C81.5,16.7,73,30.3,64.2,43.7C55.4,57.1,46.3,70.3,34,76.5C21.7,82.7,6.2,81.9,-9.2,78.7C-24.6,75.5,-39.9,69.9,-52.3,60.5C-64.7,51.1,-74.2,37.9,-79.8,23.3C-85.4,8.7,-87.1,-7.3,-82.9,-21.9C-78.7,-36.5,-68.6,-49.7,-55.9,-56.5C-43.2,-63.3,-27.9,-63.7,-13.4,-65.7C1.1,-67.7,15.6,-71.3,45.7,-77.4Z"
                    transform="translate(100 100)" />
            </svg>
        </div>

        <div class="max-w-5xl mx-auto relative z-10">
            <div class="text-center mb-10 sm:mb-14 reveal">
                <h2 class="font-atma font-bold text-3xl sm:text-4xl md:text-5xl text-center text-brand-red mb-2 sm:mb-4 reveal">Simple
                    Pricing</h2>
                <p class="font-inter text-brand-red text-base sm:text-lg font-medium">Start free. Upgrade when you're
                    ready.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ $plans->count() }} gap-6 sm:gap-8 lg:gap-12">
                @foreach ($plans as $plan)
                    <div
                        class="bg-brand-beige border {{ $plan->price > 0 ? 'border-2 border-brand-red' : 'border-[#e5c496]' }} rounded-[2rem] p-7 sm:p-10 md:p-12 flex flex-col shadow-sm card-hover relative {{ $loop->index % 2 == 0 ? 'reveal-left' : 'reveal-right' }}">
                        @if ($plan->price > 0)
                            <div
                                class="absolute -top-3.5 right-6 bg-brand-red text-white text-xs font-bold px-4 py-1 rounded-full shadow-lg animate-pulse">
                                POPULAR</div>
                        @endif
                        <span
                            class="font-inter font-bold text-base sm:text-lg uppercase tracking-wider mb-3">{{ $plan->name }}</span>
                        <div class="mb-8">
                            @if ($plan->price == 0)
                                <h3 class="font-inter font-bold text-5xl sm:text-6xl">Free</h3>
                            @else
                                <span
                                    class="font-inter font-bold text-brand-red text-4xl sm:text-5xl">{{ $plan->currency }}
                                    {{ number_format($plan->price) }}</span>
                                <span class="font-inter text-brand-red text-base font-medium opacity-80">/
                                    {{ $plan->duration_value }} {{ $plan->duration_unit }}</span>
                            @endif
                        </div>
                        <ul class="space-y-4 mb-10 flex-grow">
                            @foreach ($plan->features ?? [] as $feature)
                                <li class="flex items-center gap-3 text-base sm:text-lg font-medium"><i
                                        class='bx bx-check text-brand-red text-2xl flex-shrink-0'></i>{{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ $plan->price > 0 ? "/#contact" : route('master.register') }}"
                            class="btn-hover text-center w-full py-3.5 {{ $plan->price > 0 ? 'bg-brand-red text-white hover:bg-[#a32626]' : 'bg-white text-gray-900 line-clamp-1' }} font-bold text-base sm:text-lg rounded-xl shadow-md touch-manipulation">
                            {{ $plan->price == 0 ? 'Start Free' : 'Go Pro' }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('web.partials.testimonials')

    @include('web.partials.faq')

    @include('web.partials.above-footer')
@endsection
