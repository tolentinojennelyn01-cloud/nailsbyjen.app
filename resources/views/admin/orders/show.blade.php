@extends('layouts.app')

@section('title', 'Booking detail - Nails by Jen')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <a href="{{ route('admin.orders.index') }}" class="text-sm text-rose-500 hover:underline">&larr; Back to bookings</a>

    <div class="bg-white rounded-2xl shadow-sm border border-rose-100 p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-bold">{{ $order->customer_name }}</h1>
            <span class="text-xs px-3 py-1 rounded-full {{ $order->statusBadgeColor() }}">{{ ucfirst($order->status) }}</span>
        </div>

        <div class="grid sm:grid-cols-2 gap-3 text-sm">
            <p><span class="text-gray-400">Contact:</span> {{ $order->contact_number }}</p>
            <p><span class="text-gray-400">Facebook:</span> {{ $order->fb_name ?? '—' }}</p>
            <p><span class="text-gray-400">Preferred date:</span> {{ optional($order->preferred_date)->format('M d, Y') ?? '—' }}</p>
            <p><span class="text-gray-400">Preferred time:</span> {{ optional($order->preferred_time)->format('g:i A') ?? '—' }}</p>
            <p><span class="text-gray-400">Location:</span> {{ $order->serviceLocationLabel() }}</p>
            <p><span class="text-gray-400">Length:</span> {{ ucfirst($order->nail_length ?? '—') }}</p>
            <p><span class="text-gray-400">Shape:</span> {{ $order->nail_shape ?? '—' }}</p>
            <p><span class="text-gray-400">Color:</span> {{ $order->nail_color ?? '—' }}</p>
        </div>

        <hr class="border-rose-100">

        <div class="text-sm space-y-1">
            <div class="flex justify-between">
                <span>{{ config('nail_pricing.base_services.' . $order->base_service . '.label', $order->base_service) }}</span>
                <span>₱{{ number_format($order->base_price, 2) }}</span>
            </div>
            @if ($order->has_full_set_design)
                <div class="flex justify-between">
                    <span>{{ config('nail_pricing.full_set_designs.' . $order->full_set_design_type . '.label', $order->full_set_design_type) }}</span>
                    <span>₱{{ number_format($order->full_set_design_price, 2) }}</span>
                </div>
            @endif
            @foreach ($order->addons ?? [] as $addon)
                <div class="flex justify-between">
                    <span>{{ $addon['name'] }} × {{ $addon['qty'] }}</span>
                    <span>₱{{ number_format($addon['subtotal'], 2) }}</span>
                </div>
            @endforeach
            @if ($order->removal_option)
                <div class="flex justify-between">
                    <span>{{ config('nail_pricing.removal.' . $order->removal_option . '.label', $order->removal_option) }}</span>
                    <span>₱{{ number_format($order->removal_price, 2) }}</span>
                </div>
            @endif
            <hr class="border-rose-100">
            <div class="flex justify-between font-bold text-rose-600 text-base">
                <span>Total</span>
                <span>₱{{ number_format($order->total_price, 2) }}</span>
            </div>
        </div>

        @if ($order->notes)
            <div class="text-sm bg-rose-50 rounded-lg p-3">
                <p class="text-gray-400 text-xs mb-1">Notes</p>
                {{ $order->notes }}
            </div>
        @endif

        @if ($order->reference_image)
            <div class="text-sm">
                <p class="text-gray-400 text-xs mb-1">Reference photo</p>
                <a href="{{ asset('storage/' . $order->reference_image) }}" target="_blank">
                    <img src="{{ asset('storage/' . $order->reference_image) }}" alt="Design reference from {{ $order->customer_name }}"
                        class="max-h-64 rounded-xl border border-rose-100 shadow-sm hover:opacity-90 transition-opacity">
                </a>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" class="flex items-center gap-2 pt-2">
            @csrf
            @method('PATCH')
            <select name="status" class="rounded-lg border-gray-300 text-sm focus:border-rose-400 focus:ring-rose-400">
                @foreach (['pending', 'confirmed', 'done', 'cancelled'] as $s)
                    <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button class="bg-rose-500 hover:bg-rose-600 text-white text-sm rounded-lg px-4 py-2">Update status</button>
        </form>
    </div>
</div>
@endsection
