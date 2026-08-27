@extends('layouts.app')

@section('title', 'Admin login - Nails by Jen')

@section('content')
<div class="max-w-sm mx-auto">
    <div class="bg-white/90 rounded-3xl shadow-glam border border-gold-200/60 p-8 space-y-6">
        <div class="text-center">
            <p class="text-[11px] uppercase tracking-[0.25em] text-gold-600 mb-1">Admin area</p>
            <h1 class="text-2xl font-serif font-semibold text-rose-600">Welcome back, Jen 💅</h1>
        </div>

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 text-red-600 px-4 py-3 text-sm">
                {{ $errors->first('password') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.attempt') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm mb-1">Password</label>
                <input type="password" name="password" autofocus required
                    class="w-full rounded-lg border-2 border-rose-300 focus:border-rose-500 focus:ring-rose-400">
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-rose-500 via-rose-600 to-gold-500 hover:from-rose-600 hover:to-gold-600 text-white font-medium tracking-wide uppercase text-sm rounded-xl py-3 shadow-glam transition-all">
                Log in
            </button>
        </form>
    </div>
</div>
@endsection
