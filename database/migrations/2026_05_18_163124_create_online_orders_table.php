<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('online_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_invoice_id')->constrained('sales_invoices')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('shipping_id')->constrained('shippings')->onDelete('restrict')->onUpdate('cascade');
            $table->integer('shipping_cost')->default(0);
            $table->string('address');
            $table->string('area', 225);
            $table->string('order_note', 225)->nullable();;
            $table->string('status', 50)->default('pending');
            $table->string('customer_name',225);
            $table->string('customer_phone', 20);
            $table->string('customer_email', 100)->nullable();
            $table->timestamps();
            $table->engine('InnoDB');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_orders');
    }
};
