<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Free-text city/barangay the customer types in when booking home service.
            $table->string('service_address')->nullable()->after('service_location');

            // Set by Jen (admin) after reviewing the address — not customer-editable.
            $table->decimal('home_service_fee', 8, 2)->default(0)->after('service_address');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['service_address', 'home_service_fee']);
        });
    }
};
