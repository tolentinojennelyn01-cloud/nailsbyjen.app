<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class OrderController extends Controller
{
    /**
     * Public booking form.
     */
    public function create()
    {
        $pricing = Config::get('nail_pricing');

        return view('booking.create', compact('pricing'));
    }

    /**
     * Store a new booking. Total price is recalculated server-side
     * from config so it can never be tampered with from the browser.
     */
    public function store(Request $request)
    {
        $pricing = Config::get('nail_pricing');

        $validated = $request->validate([
            'customer_name'         => ['required', 'string', 'max:255'],
            'contact_number'        => ['required', 'string', 'max:50'],
            'fb_name'                => ['nullable', 'string', 'max:255'],
            'preferred_date'         => ['nullable', 'date'],
            'preferred_time'         => ['nullable', 'date_format:H:i'],
            'service_location'       => ['nullable', 'in:home_service,home_base'],
            'service_address'        => ['required_if:service_location,home_service', 'nullable', 'string', 'max:255'],
            'base_service'           => ['required', 'in:' . implode(',', array_keys($pricing['base_services']))],
            'has_full_set_design'    => ['nullable', 'boolean'],
            'full_set_design_type'   => ['nullable', 'in:' . implode(',', array_keys($pricing['full_set_designs']))],
            'nail_color'             => ['nullable', 'string', 'max:100'],
            'nail_shape'             => ['nullable', 'string', 'max:50'],
            'nail_length'            => ['nullable', 'string', 'max:50'],
            'addons'                 => ['nullable', 'array'],
            'addons.*'               => ['nullable', 'integer', 'min:0', 'max:10'],
            'removal_option'         => ['nullable', 'in:my_work,not_my_work'],
            'notes'                  => ['nullable', 'string', 'max:1000'],
            'reference_image'        => ['nullable', 'image', 'max:5120'], // 5MB, jpg/png/webp/etc
        ]);

        // --- Recompute everything server-side (source of truth = config) ---
        $baseService = $validated['base_service'];
        $basePrice = $pricing['base_services'][$baseService]['price'];

        $hasFullSetDesign = $request->boolean('has_full_set_design');
        $fullSetDesignType = $hasFullSetDesign ? ($validated['full_set_design_type'] ?? null) : null;
        $fullSetDesignPrice = $fullSetDesignType ? $pricing['full_set_designs'][$fullSetDesignType]['price'] : 0;

        $addonsSelected = [];
        $addonsTotal = 0;
        foreach ($request->input('addons', []) as $key => $qty) {
            $qty = (int) $qty;
            if ($qty > 0 && isset($pricing['addons'][$key])) {
                $unitPrice = $pricing['addons'][$key]['price'];
                $subtotal = $unitPrice * $qty;
                $addonsSelected[] = [
                    'name' => $pricing['addons'][$key]['label'],
                    'unit_price' => $unitPrice,
                    'qty' => $qty,
                    'subtotal' => $subtotal,
                ];
                $addonsTotal += $subtotal;
            }
        }

        $removalOption = $validated['removal_option'] ?? null;
        $removalPrice = $removalOption ? $pricing['removal'][$removalOption]['price'] : 0;

        $total = $basePrice + $fullSetDesignPrice + $addonsTotal + $removalPrice;

        $referenceImagePath = null;
        if ($request->hasFile('reference_image')) {
            $referenceImagePath = $request->file('reference_image')->store('inspo', 'public');
        }

        $order = Order::create([
            'customer_name' => $validated['customer_name'],
            'contact_number' => $validated['contact_number'],
            'fb_name' => $validated['fb_name'] ?? null,
            'preferred_date' => $validated['preferred_date'] ?? null,
            'preferred_time' => $validated['preferred_time'] ?? null,
            'service_location' => $validated['service_location'] ?? null,
            'service_address' => $validated['service_address'] ?? null,
            'base_service' => $baseService,
            'base_price' => $basePrice,
            'has_full_set_design' => $hasFullSetDesign,
            'full_set_design_type' => $fullSetDesignType,
            'full_set_design_price' => $fullSetDesignPrice,
            'nail_color' => $validated['nail_color'] ?? null,
            'nail_shape' => $validated['nail_shape'] ?? null,
            'nail_length' => $validated['nail_length'] ?? null,
            'addons' => $addonsSelected,
            'addons_total' => $addonsTotal,
            'removal_option' => $removalOption,
            'removal_price' => $removalPrice,
            'notes' => $validated['notes'] ?? null,
            'reference_image' => $referenceImagePath,
            'total_price' => $total,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('booking.confirmation', $order)
            ->with('success', 'Thank you! Your booking request has been sent.');
    }

    public function confirmation(Order $order)
    {
        return view('booking.confirmation', compact('order'));
    }

    /**
     * Admin: list all bookings, newest first, with optional status filter.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $orders = Order::query()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('fb_name', 'like', "%{$search}%");
            }))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders', 'status', 'search'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Admin: set/adjust the home service travel fee once Jen has checked
     * the customer's address. Recomputes total_price from scratch so it
     * always reflects the current fee, not a stale add-on.
     */
    public function updateHomeServiceFee(Request $request, Order $order)
    {
        $validated = $request->validate([
            'home_service_fee' => ['required', 'numeric', 'min:0', 'max:99999'],
        ]);

        $newFee = $validated['home_service_fee'];

        $newTotal = $order->base_price
            + $order->full_set_design_price
            + $order->addons_total
            + $order->removal_price
            + $newFee;

        $order->update([
            'home_service_fee' => $newFee,
            'total_price' => $newTotal,
        ]);

        return back()->with('success', 'Home service fee updated.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:pending,confirmed,done,cancelled'],
        ]);

        $order->update(['status' => $request->input('status')]);

        return back()->with('success', 'Status updated.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return back()->with('success', 'Booking deleted.');
    }
}
