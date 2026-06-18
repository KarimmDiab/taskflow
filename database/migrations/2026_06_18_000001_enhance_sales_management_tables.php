<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table): void {
            $table->decimal('tax_amount', 10, 2)->unsigned()->default(0)->after('deduction');
            $table->string('status', 30)->default('completed')->after('remaining_amount');
            $table->timestamp('cancelled_at')->nullable()->after('status');
            $table->foreignId('cancelled_by')->nullable()->after('cancelled_at')->constrained('users')->nullOnDelete();
            $table->text('cancellation_reason')->nullable()->after('cancelled_by');
        });

        Schema::table('sales_invoice_details', function (Blueprint $table): void {
            if (! Schema::hasColumn('sales_invoice_details', 'id')) {
                $table->id()->first();
            }
            $table->decimal('discount_amount', 10, 2)->unsigned()->default(0)->after('unit_price');
            $table->decimal('cost_price', 10, 2)->unsigned()->default(0)->after('discount_amount');
            $table->decimal('line_total', 10, 2)->unsigned()->default(0)->after('cost_price');
        });

        Schema::create('sales_returns', function (Blueprint $table): void {
            $table->id();
            $table->string('return_number', 100)->unique();
            $table->foreignId('sales_invoice_id')->constrained('sales_invoices')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('return_type', 30)->default('partial');
            $table->string('refund_method', 30)->default('cash_refund');
            $table->string('status', 30)->default('pending');
            $table->text('reason');
            $table->decimal('subtotal_amount', 10, 2)->unsigned()->default(0);
            $table->decimal('discount_amount', 10, 2)->unsigned()->default(0);
            $table->decimal('tax_amount', 10, 2)->unsigned()->default(0);
            $table->decimal('return_amount', 10, 2)->unsigned()->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_note')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });

        Schema::create('sales_return_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('sales_return_id')->constrained('sales_returns')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('sales_invoice_detail_id')->constrained('sales_invoice_details')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_variant_id')->constrained('product_variants')->restrictOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 10, 2)->unsigned()->default(0);
            $table->decimal('discount_amount', 10, 2)->unsigned()->default(0);
            $table->decimal('line_total', 10, 2)->unsigned()->default(0);
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::create('sales_activity_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('sales_invoice_id')->nullable()->constrained('sales_invoices')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('sales_return_id')->nullable()->constrained('sales_returns')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 80);
            $table->text('description')->nullable();
            $table->json('properties')->nullable();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_activity_logs');
        Schema::dropIfExists('sales_return_items');
        Schema::dropIfExists('sales_returns');

        Schema::table('sales_invoice_details', function (Blueprint $table): void {
            $table->dropColumn(['discount_amount', 'cost_price', 'line_total']);
            if (Schema::hasColumn('sales_invoice_details', 'id')) {
                $table->dropColumn('id');
            }
        });

        Schema::table('sales_invoices', function (Blueprint $table): void {
            $table->dropForeign(['cancelled_by']);
            $table->dropColumn(['tax_amount', 'status', 'cancelled_at', 'cancelled_by', 'cancellation_reason']);
        });
    }
};
