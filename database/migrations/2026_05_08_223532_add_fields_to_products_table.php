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
        Schema::table('products', function (Blueprint $table) {

            $table->boolean('is_active')
                ->default(true)
                ->after('product_price');

            $table->text('product_desc')
                ->nullable()
                ->after('is_active');

            $table->integer('minimum_stock')
                ->default(0)
                ->after('product_desc');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->dropColumn([
                'is_active',
                'product_desc',
                'minimum_stock'
            ]);

        });
    }
};