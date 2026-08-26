@extends('layouts.app')

@section('title', 'Booking received - Nails by Jen')

@section('content')
<div class="max-w-lg mx-auto bg-white rounded-2xl shadow-sm border border-rose-100 p-8 text-center">
    <div class="text-4xl mb-2">💖</div>
    <h1 class="text-xl font-bold text-rose-600 mb-1">Booking request sent!</h1>
    <p class="text-sm text-gray-500 mb-6">I'll message you on Facebook/Messenger to confirm your schedule.</p>

    <div class="text-left space-y-2 text-sm bg-rose-50 rounded-xl p-4">
        <div class="flex justify-between"><span class="text-gray-500">Name</span><span>{{ $order->customer_name }}</span></div>
        <div class="flex justify-between"><span class="text-gray-500">Base service</span><span>{{ config('nail_pricing.base_services.' . $order->base_service . '.label', $order->base_service) }}</span></div>
        @if ($order->has_full_set_design)
            <div class="flex justify-between"><span class="text-gray-500">Design</span><span>{{ config('nail_pricing.full_set_designs.' . $order->full_set_design_type . '.label', $order->full_set_design_type) }}</span></div>
        @endif
        @if ($order->nail_color)
            <div class="flex justify-between"><span class="text-gray-500">Color</span><span>{{ $order->nail_color }}</span></div>
        @endif
        @if ($order->service_location)
            <div class="flex justify-between"><span class="text-gray-500">Location</span><span>{{ $order->serviceLocationLabel() }}</span></div>
        @endif
        @if ($order->preferred_date)
            <div class="flex justify-between"><span class="text-gray-500">Preferred date</span><span>{{ $order->preferred_date->format('M d, Y') }}</span></div>
        @endif
        @if ($order->preferred_time)
            <div class="flex justify-between"><span class="text-gray-500">Preferred time</span><span>{{ $order->preferred_time->format('g:i A') }}</span></div>
        @endif
        <hr class="border-rose-200">
        <div class="flex justify-between font-bold text-rose-600"><span>Estimated total</span><span>₱{{ number_format($order->total_price, 2) }}</span></div>
    </div>

    <a href="{{ route('booking.create') }}" class="inline-block mt-6 text-sm text-rose-500 hover:underline">Book another set</a>
</div>
@endsection
