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
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_number')->unique();
            $table->string('status')->default('pending');
            // Statuses: pending | paid | processing | shipped | delivered | cancelled

            // Shipping info
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone', 20);
            $table->text('address');
            $table->string('city');
            $table->string('province');
            $table->string('postal_code', 10);
            $table->string('shipping_method'); // jne | jnt | sicepat | gosend
            $table->string('payment_method');  // transfer | gopay | ovo | dana | cod
            $table->text('notes')->nullable();

            // Financials (stored in IDR, no decimal needed)
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('shipping_cost');
            $table->unsignedInteger('total');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
