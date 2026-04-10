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
        Schema::create('customer_sales_invoices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sales_invoice_id')
                ->constrained('sales_invoices')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->timestamps();
            $table->softDeletes();

            $table->engine = 'InnoDB';

            $table->unique(['sales_invoice_id', 'customer_id']); // مهم جدًا
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_sales_invoices');
    }
};
