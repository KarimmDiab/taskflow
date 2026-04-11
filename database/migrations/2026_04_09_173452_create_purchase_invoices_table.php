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
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->date('purchase_invoice_date');
            $table->decimal('total_amount',10,2)->unsigned()->default(0);
            $table->decimal('paid_amount',10,2)->unsigned()->default(0);
            $table->string('payment_method');
            $table->decimal('remaining_amount',10,2)->unsigned()->default(0);
            $table->string('product_image')->nullable();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
            $table->engine('InnoDB');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_invoices');
    }
};
