@extends('layouts.app')

@section('title', 'My bookings - Nails by Jen')

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="font-serif text-2xl font-semibold text-rose-600">My bookings</h1>
        <div class="flex gap-2 text-xs">
            @foreach (['' => 'All', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'done' => 'Done', 'cancelled' => 'Cancelled'] as $value => $label)
                <a href="{{ route('admin.orders.index', array_filter(['status' => $value ?: null, 'search' => $search])) }}"
                   class="px-3 py-1 rounded-full border {{ $status === $value ? 'bg-rose-500 text-white border-rose-500' : 'border-gray-200 text-plum-400 hover:border-rose-300' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('admin.orders.index') }}" class="flex gap-2">
        @if ($status)
            <input type="hidden" name="status" value="{{ $status }}">
        @endif
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gold-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0a7.5 7.5 0 10-10.6 0 7.5 7.5 0 0010.6 0z" />
            </svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, contact number, or Facebook name"
                class="w-full pl-9 rounded-xl border-2 border-rose-200 focus:border-rose-500 focus:ring-rose-400 text-sm">
        </div>
        <button type="submit" class="px-4 rounded-xl bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium">Search</button>
        @if ($search)
            <a href="{{ route('admin.orders.index', array_filter(['status' => $status])) }}"
               class="px-4 rounded-xl border border-gray-200 text-plum-400 text-sm flex items-center hover:border-rose-300">Clear</a>
        @endif
    </form>

    <div class="bg-white/90 rounded-3xl shadow-glam border border-gold-200/60 divide-y divide-rose-50">
        @forelse ($orders as $order)
            <div class="flex items-center justify-between px-6 py-4 hover:bg-rose-50/50 transition-colors">
                <a href="{{ route('admin.orders.show', $order) }}" class="flex-1 min-w-0">
                    <p class="font-semibold truncate">{{ $order->customer_name }}</p>
                    <p class="text-xs text-plum-400 truncate">
                        {{ config('nail_pricing.base_services.' . $order->base_service . '.label', $order->base_service) }}
                        @if ($order->preferred_date) · {{ $order->preferred_date->format('M d, Y') }} @endif
                    </p>
                </a>
                <div class="flex items-center gap-3 shrink-0 ml-4">
                    <span class="font-semibold text-rose-600">₱{{ number_format($order->total_price, 2) }}</span>
                    <span class="text-xs px-2 py-1 rounded-full {{ $order->statusBadgeColor() }}">{{ ucfirst($order->status) }}</span>

                    @if ($order->status !== 'done')
                        <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="done">
                            <button type="submit" title="Mark as done"
                                class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center">
                                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}"
                          onsubmit="return confirm('Delete this booking from {{ addslashes($order->customer_name) }}? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Delete booking"
                            class="w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center">
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="px-6 py-8 text-center text-plum-400 text-sm">
                @if ($search)
                    No bookings match "{{ $search }}".
                @else
                    No bookings yet.
                @endif
            </p>
        @endforelse
    </div>

    {{ $orders->links() }}
</div>
@endsection
