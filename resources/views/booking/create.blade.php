@extends('layouts.app')

@section('title', 'Book your nails - Nails by Jen')

@section('content')
<div x-data="bookingForm(@js($pricing))" class="grid md:grid-cols-3 gap-8">

    {{-- ============ FORM ============ --}}
    <form method="POST" action="{{ route('booking.store') }}" enctype="multipart/form-data" class="md:col-span-2 bg-white/90 rounded-3xl shadow-glam border border-gold-200/60 p-6 sm:p-8 space-y-8">
        @csrf

        <div>
            <p class="text-[11px] uppercase tracking-[0.25em] text-gold-600 mb-1">Reserve your appointment</p>
            <h1 class="text-3xl font-serif font-semibold text-rose-600">Book your nails <span class="align-middle"></span></h1>
            <p class="text-sm text-plum-400 mt-2">Fill this out and I'll confirm your slot on Facebook/Messenger.</p>
        </div>

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 text-red-600 text-sm px-4 py-3">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Customer info --}}
        <fieldset class="space-y-4">
            <legend class="font-serif text-lg text-plum-700 mb-2">Your details</legend>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm mb-1">Full name</label>
                    <input type="text" name="customer_name" required value="{{ old('customer_name') }}"
                        class="w-full rounded-lg border-2 border-rose-300 focus:border-rose-500 focus:ring-rose-400">
                </div>
                <div>
                    <label class="block text-sm mb-1">Contact number</label>
                    <input type="text" name="contact_number" required value="{{ old('contact_number') }}"
                        class="w-full rounded-lg border-2 border-rose-300 focus:border-rose-500 focus:ring-rose-400">
                </div>
                <div>
                    <label class="block text-sm mb-1">Facebook name (optional)</label>
                    <input type="text" name="fb_name" value="{{ old('fb_name') }}"
                        class="w-full rounded-lg border-2 border-rose-300 focus:border-rose-500 focus:ring-rose-400">
                </div>
                <div>
                    <label class="block text-sm mb-1">Preferred date</label>
                    <input type="date" name="preferred_date" value="{{ old('preferred_date') }}"
                        class="w-full rounded-lg border-2 border-rose-300 focus:border-rose-500 focus:ring-rose-400">
                </div>
                <div>
                    <label class="block text-sm mb-1">Preferred time</label>
                    <input type="time" name="preferred_time" value="{{ old('preferred_time') }}"
                        class="w-full rounded-lg border-2 border-rose-300 focus:border-rose-500 focus:ring-rose-400">
                </div>
            </div>

            {{-- Service location --}}
            <div>
                <label class="block text-sm mb-2">Where would you like your appointment?</label>
                <div class="grid sm:grid-cols-2 gap-3">
                    <label class="flex items-start gap-3 border-2 rounded-xl px-4 py-3 cursor-pointer transition-colors {{ old('service_location') === 'home_service' ? 'border-gold-400 bg-rose-50/70' : 'border-rose-200' }} has-[:checked]:border-gold-400 has-[:checked]:bg-rose-50/70">
                        <input type="radio" name="service_location" value="home_service" required
                            x-model="serviceLocation"
                            @checked(old('service_location') === 'home_service') class="mt-1 text-rose-500 focus:ring-rose-400">
                        <span>
                            <span class="block font-medium text-sm">Home Service</span>
                            <span class="block text-xs text-plum-400">Jen travels to your address</span>
                        </span>
                    </label>
                    <label class="flex items-start gap-3 border-2 rounded-xl px-4 py-3 cursor-pointer transition-colors {{ old('service_location') === 'home_base' ? 'border-gold-400 bg-rose-50/70' : 'border-rose-200' }} has-[:checked]:border-gold-400 has-[:checked]:bg-rose-50/70">
                        <input type="radio" name="service_location" value="home_base"
                            x-model="serviceLocation"
                            @checked(old('service_location') === 'home_base') class="mt-1 text-rose-500 focus:ring-rose-400">
                        <span>
                            <span class="block font-medium text-sm">Home Base</span>
                            <span class="block text-xs text-plum-400">You come to Jen's place in Paliparan 2, Dasmariñas</span>
                        </span>
                    </label>
                </div>

                {{-- Address, shown only for Home Service --}}
                <div x-show="serviceLocation === 'home_service'" x-cloak class="mt-3">
                    <label class="block text-sm mb-1">Your address (city / barangay)</label>
                    <input type="text" name="service_address" value="{{ old('service_address') }}"
                        placeholder="e.g. Barangay Paliparan 3, Dasmariñas City"
                        class="w-full rounded-lg border-2 border-rose-300 focus:border-rose-500 focus:ring-rose-400">
                    <p class="text-xs text-plum-400 mt-1">A home service fee applies based on distance — Jen will confirm the exact amount once she reviews your address.</p>
                </div>
            </div>
        </fieldset>

        {{-- Base service --}}
        <fieldset>
            <legend class="font-serif text-lg text-plum-700 mb-2">Base service</legend>
            <div class="space-y-2">
                <template x-for="[key, item] in Object.entries(pricing.base_services)" :key="key">
                    <label class="flex items-center justify-between border rounded-xl px-4 py-3 cursor-pointer transition-colors"
                           :class="baseService === key ? 'border-gold-400 bg-rose-50/70 shadow-sm' : 'border-gray-200'">
                        <span class="flex items-center gap-3">
                            <input type="radio" name="base_service" :value="key" x-model="baseService" required class="text-rose-500 focus:ring-rose-400">
                            <span x-text="item.label"></span>
                        </span>
                        <span class="text-sm text-plum-400">₱<span x-text="item.price"></span></span>
                    </label>
                </template>
            </div>
        </fieldset>

        {{-- Nail length / shape / color (preferences) --}}
        <fieldset class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm mb-1">Nail length preference</label>
                <select name="nail_length" class="w-full rounded-lg border-2 border-rose-300 focus:border-rose-500 focus:ring-rose-400">
                    <option value="">Select length</option>
                    <option value="short">Short</option>
                    <option value="medium">Medium</option>
                    <option value="long">Long</option>
                </select>
                <p class="text-xs text-plum-400 mt-1">Extension pricing above already reflects short-med vs long.</p>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm mb-2">Nail shape</label>
                <div class="flex flex-wrap gap-3">
                    @foreach ($pricing['nail_shapes'] as $shape)
                        <label class="flex flex-col items-center gap-1 cursor-pointer group w-16">
                            <span class="relative flex items-center justify-center w-16 h-16 rounded-xl border-2 border-rose-200 text-rose-300 peer-checked:border-gold-400 peer-checked:bg-rose-50/70 peer-checked:text-rose-600 peer-checked:shadow-glam group-hover:border-rose-300 transition-colors has-[:checked]:border-gold-400 has-[:checked]:bg-rose-50/70 has-[:checked]:text-rose-600 has-[:checked]:shadow-glam">
                                <input type="radio" name="nail_shape" value="{{ $shape }}" class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                    @checked(old('nail_shape') === $shape)>
                                @include('partials.nail-shape-icon', ['shape' => $shape])
                            </span>
                            <span class="text-[11px] text-plum-400 text-center">{{ $shape }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm mb-2">Nail color</label>
                <div class="flex flex-wrap gap-3">
                    @foreach ($pricing['nail_colors'] as $hex => $label)
                        <label class="flex flex-col items-center gap-1 cursor-pointer group">
                            <span class="relative w-9 h-9">
                                <input type="radio" name="nail_color" value="{{ $label }}" class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <span
                                    @if($hex !== 'custom') style="background-color: {{ $hex }}" @endif
                                    class="pointer-events-none absolute inset-0 rounded-full border-2 border-gray-200 peer-checked:border-gold-500 peer-checked:ring-2 peer-checked:ring-gold-300 peer-checked:shadow-glam flex items-center justify-center text-[10px] transition-all group-hover:border-gold-300 {{ $hex === 'custom' ? 'bg-gray-100' : '' }}">@if($hex === 'custom')?@endif</span>
                                <svg class="pointer-events-none absolute -top-1 -right-1 w-4 h-4 rounded-full bg-gold-500 text-white p-0.5 opacity-0 scale-75 peer-checked:opacity-100 peer-checked:scale-100 transition-all" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span class="text-[11px] text-plum-400 text-center w-14">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </fieldset>

        {{-- Full set design --}}
        <fieldset>
            <label class="flex items-center gap-2 mb-2 font-serif text-lg text-plum-700">
                <input type="checkbox" name="has_full_set_design" value="1" x-model="hasFullSetDesign" class="text-rose-500 focus:ring-rose-400">
                Add a Full Set Design
            </label>
            <div x-show="hasFullSetDesign" x-cloak class="space-y-2 pl-6">
                <template x-for="[key, item] in Object.entries(pricing.full_set_designs)" :key="key">
                    <label class="flex items-center justify-between border rounded-xl px-4 py-2 cursor-pointer transition-colors"
                           :class="fullSetDesignType === key ? 'border-gold-400 bg-rose-50/70 shadow-sm' : 'border-gray-200'">
                        <span class="flex items-center gap-3">
                            <input type="radio" name="full_set_design_type" :value="key" x-model="fullSetDesignType" class="text-rose-500 focus:ring-rose-400">
                            <span x-text="item.label"></span>
                        </span>
                        <span class="text-sm text-plum-400">+ ₱<span x-text="item.price"></span></span>
                    </label>
                </template>
            </div>
        </fieldset>

        {{-- Per-nail add-ons --}}
        <fieldset>
            <legend class="font-serif text-lg text-plum-700 mb-2">Per-nail add-ons</legend>
            <div class="space-y-2">
                <template x-for="[key, item] in Object.entries(pricing.addons)" :key="key">
                    <div class="flex items-center justify-between border border-gray-200 rounded-lg px-4 py-2">
                        <div>
                            <p x-text="item.label" class="text-sm"></p>
                            <p class="text-xs text-plum-400">₱<span x-text="item.price"></span> per nail</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="addons[key] = Math.max(0, (addons[key]||0) - 1)" class="w-7 h-7 rounded-full bg-rose-100 text-rose-600">−</button>
                            <input type="number" :name="'addons[' + key + ']'" x-model.number="addons[key]" min="0" max="10"
                                class="w-12 text-center rounded-lg border-2 border-rose-300 focus:border-rose-500 focus:ring-rose-400">
                            <button type="button" @click="addons[key] = Math.min(10, (addons[key]||0) + 1)" class="w-7 h-7 rounded-full bg-rose-100 text-rose-600">+</button>
                        </div>
                    </div>
                </template>
            </div>
        </fieldset>

        {{-- Removal --}}
        <fieldset>
            <legend class="font-serif text-lg text-plum-700 mb-2">Removal (if applicable)</legend>
            <div class="space-y-2">
                <label class="flex items-center gap-2">
                    <input type="radio" name="removal_option" value="" x-model="removalOption" class="text-rose-500 focus:ring-rose-400">
                    No removal needed
                </label>
                <template x-for="[key, item] in Object.entries(pricing.removal)" :key="key">
                    <label class="flex items-center justify-between border rounded-xl px-4 py-2 cursor-pointer transition-colors"
                           :class="removalOption === key ? 'border-gold-400 bg-rose-50/70 shadow-sm' : 'border-gray-200'">
                        <span class="flex items-center gap-3">
                            <input type="radio" name="removal_option" :value="key" x-model="removalOption" class="text-rose-500 focus:ring-rose-400">
                            <span x-text="item.label"></span>
                        </span>
                        <span class="text-sm text-plum-400">+ ₱<span x-text="item.price"></span></span>
                    </label>
                </template>
            </div>
        </fieldset>

        <div>
            <label class="block text-sm mb-1">Notes (design inspo, custom color, allergies, etc.)</label>
            <textarea name="notes" rows="3" class="w-full rounded-lg border-2 border-rose-300 focus:border-rose-500 focus:ring-rose-400">{{ old('notes') }}</textarea>
        </div>

        {{-- Reference photo --}}
        <div x-data="{ fileName: '' }">
            <label class="block text-sm mb-1">Got a design in mind? Attach a reference photo (optional)</label>
            <label class="flex items-center gap-3 border-2 border-dashed border-rose-300 hover:border-gold-400 rounded-xl px-4 py-4 cursor-pointer transition-colors bg-rose-50/40">
                <svg class="w-6 h-6 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l-3.75 3.75M12 9.75l3.75 3.75M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                </svg>
                <span class="text-sm text-plum-500" x-text="fileName || 'Tap to upload a screenshot or photo of the style you like'"></span>
                <input type="file" name="reference_image" accept="image/*" class="hidden"
                    @change="fileName = $event.target.files[0]?.name || ''">
            </label>
            <p class="text-xs text-plum-400 mt-1">JPG, PNG, or WEBP — up to 5MB.</p>
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-rose-500 via-rose-600 to-gold-500 hover:from-rose-600 hover:to-gold-600 text-white font-medium tracking-wide uppercase text-sm rounded-xl py-3.5 shadow-glam transition-all">
            Send booking request
        </button>
    </form>

    {{-- ============ LIVE PRICE SUMMARY ============ --}}
    <aside class="md:col-span-1">
        <div class="bg-white/90 rounded-3xl shadow-glam border border-gold-200/60 p-6 sticky top-6 space-y-3">
            <p class="text-[11px] uppercase tracking-[0.25em] text-gold-600 mb-1">Your glam</p>
            <h2 class="font-serif text-xl font-semibold text-rose-600 mb-2">Estimated total</h2>

            <div class="flex justify-between text-sm" x-show="baseService">
                <span x-text="baseService ? pricing.base_services[baseService].label : ''"></span>
                <span x-text="'₱' + (baseService ? pricing.base_services[baseService].price : 0)"></span>
            </div>

            <div class="flex justify-between text-sm" x-show="hasFullSetDesign && fullSetDesignType">
                <span x-text="fullSetDesignType ? pricing.full_set_designs[fullSetDesignType].label : ''"></span>
                <span x-text="'₱' + (fullSetDesignType ? pricing.full_set_designs[fullSetDesignType].price : 0)"></span>
            </div>

            <template x-for="[key, qty] in Object.entries(addons)" :key="key">
                <div class="flex justify-between text-sm" x-show="qty > 0">
                    <span x-text="pricing.addons[key]?.label + ' × ' + qty"></span>
                    <span x-text="'₱' + (pricing.addons[key]?.price * qty)"></span>
                </div>
            </template>

            <div class="flex justify-between text-sm" x-show="removalOption">
                <span x-text="removalOption ? pricing.removal[removalOption].label : ''"></span>
                <span x-text="'₱' + (removalOption ? pricing.removal[removalOption].price : 0)"></span>
            </div>

            <div class="flex justify-between text-sm text-plum-400 italic" x-show="serviceLocation === 'home_service'">
                <span>Home service fee</span>
                <span>TBD</span>
            </div>

            <hr class="border-gold-200">
            <div class="flex justify-between font-serif font-semibold text-xl text-rose-600">
                <span>Total</span>
                <span x-text="'₱' + total"></span>
            </div>
            <p class="text-xs text-plum-400" x-show="serviceLocation === 'home_service'">Home service fee not yet included — Jen will confirm it based on your address.</p>
            <p class="text-xs text-plum-400" x-show="serviceLocation !== 'home_service'">Final price confirmed by Jen once the design is finalized in person.</p>
        </div>
    </aside>
</div>

<script>
function bookingForm(pricing) {
    return {
        pricing: pricing,
        serviceLocation: '{{ old('service_location') }}',
        baseService: '',
        hasFullSetDesign: false,
        fullSetDesignType: '',
        addons: {},
        removalOption: '',

        get total() {
            let sum = 0;
            if (this.baseService && this.pricing.base_services[this.baseService]) {
                sum += this.pricing.base_services[this.baseService].price;
            }
            if (this.hasFullSetDesign && this.fullSetDesignType && this.pricing.full_set_designs[this.fullSetDesignType]) {
                sum += this.pricing.full_set_designs[this.fullSetDesignType].price;
            }
            for (const [key, qty] of Object.entries(this.addons)) {
                if (qty > 0 && this.pricing.addons[key]) {
                    sum += this.pricing.addons[key].price * qty;
                }
            }
            if (this.removalOption && this.pricing.removal[this.removalOption]) {
                sum += this.pricing.removal[this.removalOption].price;
            }
            return sum;
        }
    }
}
</script>
@endsection
