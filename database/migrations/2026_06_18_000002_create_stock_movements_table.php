<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_variant_id')->constrained('product_variants')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('movement_type', 40);
            $table->string('direction', 10);
            $table->unsignedInteger('quantity');
            $table->integer('quantity_before')->nullable();
            $table->integer('quantity_after')->nullable();
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->nullableMorphs('reference');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('movement_date');
            $table->timestamps();

            $table->index(['movement_type', 'direction']);
            $table->index(['branch_id', 'product_variant_id']);
            $table->index('movement_date');
            $table->engine = 'InnoDB';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
