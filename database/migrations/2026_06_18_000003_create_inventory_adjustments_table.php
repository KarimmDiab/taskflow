<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_adjustments', function (Blueprint $table): void {
            $table->id();
            $table->string('adjustment_number', 100)->unique();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_variant_id')->constrained('product_variants')->restrictOnDelete()->cascadeOnUpdate();
            $table->integer('quantity_before');
            $table->integer('quantity_after');
            $table->integer('adjustment_quantity');
            $table->string('adjustment_type', 40);
            $table->text('reason');
            $table->text('notes')->nullable();
            $table->string('status', 30)->default('completed');
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamp('adjustment_date');
            $table->timestamps();

            $table->index(['branch_id', 'product_variant_id']);
            $table->index('adjustment_type');
            $table->index('adjustment_date');
            $table->engine = 'InnoDB';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_adjustments');
    }
};
