<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Customer info
            $table->string('customer_name');
            $table->string('contact_number');
            $table->string('fb_name')->nullable();
            $table->date('preferred_date')->nullable();

            // Base service (Base (Plain) section of the price list)
            $table->string('base_service');           // softgel_short_med | softgel_long | gel_polish_only
            $table->decimal('base_price', 8, 2);

            // Full Set Design (French / Ombre / Cat eye)
            $table->boolean('has_full_set_design')->default(false);
            $table->string('full_set_design_type')->nullable(); // french | ombre | cateye
            $table->decimal('full_set_design_price', 8, 2)->default(0);

            // Customer preferences (informational, no price impact)
            $table->string('nail_color')->nullable();
            $table->string('nail_shape')->nullable();  // round, almond, coffin, stiletto, square
            $table->string('nail_length')->nullable(); // short, medium, long

            // Per-nail add-ons, stored as JSON: [{name, unit_price, qty, subtotal}, ...]
            $table->json('addons')->nullable();
            $table->decimal('addons_total', 8, 2)->default(0);

            // Removal
            $table->string('removal_option')->nullable(); // my_work | not_my_work
            $table->decimal('removal_price', 8, 2)->default(0);

            $table->text('notes')->nullable();
            $table->decimal('total_price', 8, 2);

            // pending -> confirmed -> done (or cancelled)
            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
