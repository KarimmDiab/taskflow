<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('product_images', 'color_id')) {
            DB::statement('ALTER TABLE product_images MODIFY COLUMN color_id BIGINT UNSIGNED NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('product_images', 'color_id')) {
            DB::statement('ALTER TABLE product_images MODIFY COLUMN color_id BIGINT UNSIGNED NOT NULL');
        }
    }
};
