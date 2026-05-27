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
        Schema::create('size_charts', function (Blueprint $table) {

            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('restrict')
                ->onUpdate('cascade')
                ->nullable();

            $table->string('image_path');

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
        Schema::dropIfExists('size_charts');
    }
};
