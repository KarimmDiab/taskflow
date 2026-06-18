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
            $table->string('payment_number')->unique();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('purchase_invoice_id')->nullable()->constrained('purchase_invoices')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('payment_method_id')->constrained('payment_methods')->onDelete('restrict')->onUpdate('cascade');
            $table->decimal('amount', 12, 2)->unsigned();
            $table->date('payment_date');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
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
