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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('color_id')->constrained('colors')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('size_id')->constrained('sizes')->onUpdate('cascade')->onDelete('restrict');
            $table->string('sku')->unique();
            $table->decimal('variant_cost', 10, 2)->default(0)->nullable();
            $table->decimal('variant_price', 10, 2)->default(0)->nullable();
            $table->boolean('is_active')->default('1');
            $table->timestamps();
            $table->softDeletes();
            $table->engine('InnoDB');
            $table->unique([
                'product_id',
                'color_id',
                'size_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
