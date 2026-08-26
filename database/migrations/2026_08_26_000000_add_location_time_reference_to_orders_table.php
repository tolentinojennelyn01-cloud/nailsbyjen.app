<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // home_service = Jen travels to the customer, home_base = customer comes to Jen's place
            $table->string('service_location')->nullable()->after('preferred_date');
            $table->time('preferred_time')->nullable()->after('preferred_date');
            $table->string('reference_image')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['service_location', 'preferred_time', 'reference_image']);
        });
    }
};
