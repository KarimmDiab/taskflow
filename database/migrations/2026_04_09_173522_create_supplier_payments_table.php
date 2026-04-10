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
        Schema::create('supplier_payments', function (Blueprint $table) {
            $table->id();
            $table->date('payment_date');
            $table->decimal('paid_amount')->unsigned()->default(0);
            $table->string('note')->nullable();
            $table->foreignId('purchase_invoice_id')->constrained('purchase_invoices')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('supplier_payments');
    }
};
