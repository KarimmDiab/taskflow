<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_invoice_details', function (Blueprint $table): void {
            if (! Schema::hasColumn('purchase_invoice_details', 'id')) {
                $table->id()->first();
            }

            if (! Schema::hasColumn('purchase_invoice_details', 'branch_id')) {
                $table->foreignId('branch_id')->nullable()->after('purchase_invoice_id')->constrained('branches')->restrictOnDelete()->cascadeOnUpdate();
            }
        });

        if (Schema::hasColumn('purchase_invoice_details', 'branch_id')) {
            DB::table('purchase_invoice_details')
                ->join('purchase_invoices', 'purchase_invoice_details.purchase_invoice_id', '=', 'purchase_invoices.id')
                ->whereNull('purchase_invoice_details.branch_id')
                ->update(['purchase_invoice_details.branch_id' => DB::raw('purchase_invoices.branch_id')]);
        }

        Schema::create('purchase_returns', function (Blueprint $table): void {
            $table->id();
            $table->string('return_number')->unique();
            $table->foreignId('supplier_id')->constrained('suppliers')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('purchase_invoice_id')->constrained('purchase_invoices')->restrictOnDelete()->cascadeOnUpdate();
            $table->date('return_date');
            $table->decimal('total_amount', 12, 2)->unsigned()->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::create('purchase_return_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('purchase_return_id')->constrained('purchase_returns')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('purchase_invoice_item_id')->constrained('purchase_invoice_details')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_variant_id')->constrained('product_variants')->restrictOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_cost', 12, 2)->unsigned();
            $table->decimal('total', 12, 2)->unsigned();
            $table->text('reason')->nullable();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::create('supplier_credits', function (Blueprint $table): void {
            $table->id();
            $table->string('credit_number')->unique();
            $table->foreignId('supplier_id')->constrained('suppliers')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('purchase_invoice_id')->nullable()->constrained('purchase_invoices')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('purchase_return_id')->nullable()->constrained('purchase_returns')->restrictOnDelete()->cascadeOnUpdate();
            $table->decimal('amount', 12, 2)->unsigned();
            $table->decimal('remaining_amount', 12, 2)->unsigned();
            $table->date('credit_date');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_credits');
        Schema::dropIfExists('purchase_return_items');
        Schema::dropIfExists('purchase_returns');
    }
};
