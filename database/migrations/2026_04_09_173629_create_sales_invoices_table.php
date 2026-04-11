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
        Schema::create('sales_invoices', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_amount', 10, 2)->unsigned()->default(0);
            $table->decimal('deduction', 10, 2)->unsigned()->default(0);
            $table->decimal('net_total', 10, 2)->unsigned()->default(0);
            $table->decimal('paid_amount', 10, 2)->unsigned()->default(0);
            $table->decimal('remaining_amount', 10, 2)->unsigned()->default(0);
            $table->string('payment_method');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('sales_invoices');
    }
};
