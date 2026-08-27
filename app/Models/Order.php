<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'contact_number',
        'fb_name',
        'preferred_date',
        'preferred_time',
        'service_location',
        'service_address',
        'home_service_fee',
        'base_service',
        'base_price',
        'has_full_set_design',
        'full_set_design_type',
        'full_set_design_price',
        'nail_color',
        'nail_shape',
        'nail_length',
        'addons',
        'addons_total',
        'removal_option',
        'removal_price',
        'notes',
        'reference_image',
        'total_price',
        'status',
    ];

    protected $casts = [
        'addons' => 'array',
        'has_full_set_design' => 'boolean',
        'preferred_date' => 'date',
        'preferred_time' => 'datetime:H:i',
        'base_price' => 'decimal:2',
        'home_service_fee' => 'decimal:2',
        'full_set_design_price' => 'decimal:2',
        'addons_total' => 'decimal:2',
        'removal_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function serviceLocationLabel(): string
    {
        return match ($this->service_location) {
            'home_service' => 'Home Service (Jen travels to you)',
            'home_base' => 'Home Base (at Jen\'s place)',
            default => '—',
        };
    }

    public function statusBadgeColor(): string
    {
        return match ($this->status) {
            'confirmed' => 'bg-blue-100 text-blue-700',
            'done' => 'bg-green-100 text-green-700',
            'cancelled' => 'bg-gray-200 text-gray-600',
            default => 'bg-pink-100 text-pink-700', // pending
        };
    }
}
