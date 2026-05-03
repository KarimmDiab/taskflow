<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            // Nullable so existing rows remain valid until backfilled manually.
            $table->string('invoice_number', 100)->nullable()->after('id')->unique();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('product_code', 100)->nullable()->after('product_name')->unique();
        });
    }

    public function down(): void
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->dropUnique(['invoice_number']);
            $table->dropColumn('invoice_number');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['product_code']);
            $table->dropColumn('product_code');
        });
    }
};
