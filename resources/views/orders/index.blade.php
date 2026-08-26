@extends('layouts.app')

@section('title', 'My bookings - Nails by Jen')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-rose-600">My bookings</h1>
        <div class="flex gap-2 text-xs">
            @foreach (['' => 'All', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'done' => 'Done', 'cancelled' => 'Cancelled'] as $value => $label)
                <a href="{{ route('admin.orders.index', $value ? ['status' => $value] : []) }}"
                   class="px-3 py-1 rounded-full border {{ $status === $value ? 'bg-rose-500 text-white border-rose-500' : 'border-gray-200 text-gray-500' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-rose-100 divide-y divide-rose-50">
        @forelse ($orders as $order)
            <a href="{{ route('admin.orders.show', $order) }}" class="flex items-center justify-between px-6 py-4 hover:bg-rose-50/50">
                <div>
                    <p class="font-semibold">{{ $order->customer_name }}</p>
                    <p class="text-xs text-gray-400">
                        {{ config('nail_pricing.base_services.' . $order->base_service . '.label', $order->base_service) }}
                        @if ($order->preferred_date) · {{ $order->preferred_date->format('M d, Y') }} @endif
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="font-semibold text-rose-600">₱{{ number_format($order->total_price, 2) }}</span>
                    <span class="text-xs px-2 py-1 rounded-full {{ $order->statusBadgeColor() }}">{{ ucfirst($order->status) }}</span>
                </div>
            </a>
        @empty
            <p class="px-6 py-8 text-center text-gray-400 text-sm">No bookings yet.</p>
        @endforelse
    </div>

    {{ $orders->links() }}
</div>
@endsection
