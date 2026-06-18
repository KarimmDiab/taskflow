<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('type', 20);
            $table->decimal('value', 10, 2)->unsigned();
            $table->decimal('min_order_amount', 10, 2)->unsigned()->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::table('sales_invoices', function (Blueprint $table): void {
            $table->decimal('subtotal', 10, 2)->unsigned()->default(0)->after('invoice_number');
            $table->decimal('discount_amount', 10, 2)->unsigned()->default(0)->after('deduction');
            $table->string('discount_type', 20)->nullable()->after('discount_amount');
            $table->foreignId('coupon_id')->nullable()->after('discount_type')->constrained('coupons')->nullOnDelete();
            $table->string('coupon_code')->nullable()->after('coupon_id');
            $table->decimal('grand_total', 10, 2)->unsigned()->default(0)->after('tax_amount');
        });

        Schema::table('sales_invoice_details', function (Blueprint $table): void {
            $table->string('item_discount_type', 20)->nullable()->after('unit_price');
            $table->decimal('item_discount_value', 10, 2)->unsigned()->nullable()->after('item_discount_type');
            $table->decimal('item_discount_amount', 10, 2)->unsigned()->default(0)->after('item_discount_value');
            $table->decimal('line_total_after_discount', 10, 2)->unsigned()->default(0)->after('line_total');
        });

        Schema::table('online_orders', function (Blueprint $table): void {
            $table->decimal('subtotal', 10, 2)->unsigned()->default(0)->after('shipping_cost');
            $table->decimal('discount_amount', 10, 2)->unsigned()->default(0)->after('subtotal');
            $table->string('discount_type', 20)->nullable()->after('discount_amount');
            $table->foreignId('coupon_id')->nullable()->after('discount_type')->constrained('coupons')->nullOnDelete();
            $table->string('coupon_code')->nullable()->after('coupon_id');
            $table->decimal('grand_total', 10, 2)->unsigned()->default(0)->after('coupon_code');
            $table->decimal('net_total', 10, 2)->unsigned()->default(0)->after('grand_total');
            $table->timestamp('coupon_counted_at')->nullable()->after('net_total');
        });
    }

    public function down(): void
    {
        Schema::table('online_orders', function (Blueprint $table): void {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn([
                'subtotal',
                'discount_amount',
                'discount_type',
                'coupon_id',
                'coupon_code',
                'grand_total',
                'net_total',
                'coupon_counted_at',
            ]);
        });

        Schema::table('sales_invoice_details', function (Blueprint $table): void {
            $table->dropColumn([
                'item_discount_type',
                'item_discount_value',
                'item_discount_amount',
                'line_total_after_discount',
            ]);
        });

        Schema::table('sales_invoices', function (Blueprint $table): void {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn([
                'subtotal',
                'discount_amount',
                'discount_type',
                'coupon_id',
                'coupon_code',
                'grand_total',
            ]);
        });

        Schema::dropIfExists('coupons');
    }
};
