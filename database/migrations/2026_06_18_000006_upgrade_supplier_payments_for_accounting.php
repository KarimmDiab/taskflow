<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('supplier_payments', function (Blueprint $table) {
            if (! Schema::hasColumn('supplier_payments', 'payment_number')) {
                $table->string('payment_number')->nullable()->unique()->after('id');
            }

            if (! Schema::hasColumn('supplier_payments', 'payment_method_id')) {
                $table->foreignId('payment_method_id')->nullable()->after('purchase_invoice_id')->constrained('payment_methods')->onDelete('restrict')->onUpdate('cascade');
            }

            if (! Schema::hasColumn('supplier_payments', 'amount')) {
                $table->decimal('amount', 12, 2)->unsigned()->default(0)->after('payment_method_id');
            }

            if (! Schema::hasColumn('supplier_payments', 'reference_number')) {
                $table->string('reference_number')->nullable()->after('payment_date');
            }

            if (! Schema::hasColumn('supplier_payments', 'notes')) {
                $table->text('notes')->nullable()->after('reference_number');
            }

            if (! Schema::hasColumn('supplier_payments', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('notes')->constrained('users')->onDelete('restrict')->onUpdate('cascade');
            }
        });

        if (Schema::hasColumn('supplier_payments', 'paid_amount')) {
            DB::table('supplier_payments')
                ->where('amount', 0)
                ->update(['amount' => DB::raw('paid_amount')]);
        }

        if (Schema::hasColumn('supplier_payments', 'note')) {
            DB::table('supplier_payments')
                ->whereNull('notes')
                ->update(['notes' => DB::raw('note')]);
        }

        if (Schema::hasColumn('supplier_payments', 'user_id')) {
            DB::table('supplier_payments')
                ->whereNull('created_by')
                ->update(['created_by' => DB::raw('user_id')]);
        }

        $fallbackPaymentMethodId = DB::table('payment_methods')->orderBy('id')->value('id');
        if ($fallbackPaymentMethodId) {
            DB::table('supplier_payments')
                ->whereNull('payment_method_id')
                ->update(['payment_method_id' => $fallbackPaymentMethodId]);
        }

        DB::table('supplier_payments')
            ->whereNull('payment_number')
            ->orderBy('id')
            ->select(['id'])
            ->chunkById(100, function ($payments): void {
                foreach ($payments as $payment) {
                    DB::table('supplier_payments')
                        ->where('id', $payment->id)
                        ->update(['payment_number' => 'SP-'.now()->format('Y').'-'.str_pad((string) $payment->id, 6, '0', STR_PAD_LEFT)]);
                }
            });

        Schema::table('supplier_payments', function (Blueprint $table) {
            if (Schema::hasColumn('supplier_payments', 'payment_number')) {
                $table->string('payment_number')->nullable(false)->change();
            }

            if (Schema::hasColumn('supplier_payments', 'amount')) {
                $table->decimal('amount', 12, 2)->unsigned()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('supplier_payments', function (Blueprint $table) {
            foreach (['payment_number', 'payment_method_id', 'amount', 'reference_number', 'notes', 'created_by'] as $column) {
                if (Schema::hasColumn('supplier_payments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
