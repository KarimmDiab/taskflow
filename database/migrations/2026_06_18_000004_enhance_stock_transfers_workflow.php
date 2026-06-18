<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_transfers', function (Blueprint $table): void {
            if (! Schema::hasColumn('stock_transfers', 'transfer_number')) {
                $table->string('transfer_number', 100)->nullable()->after('id');
            }
            if (! Schema::hasColumn('stock_transfers', 'status')) {
                $table->string('status', 30)->default('draft')->after('to_branch_id');
            }
            if (! Schema::hasColumn('stock_transfers', 'sent_at')) {
                $table->timestamp('sent_at')->nullable()->after('notes');
                $table->foreignId('sent_by')->nullable()->after('sent_at')->constrained('users')->nullOnDelete();
                $table->timestamp('received_at')->nullable()->after('sent_by');
                $table->foreignId('received_by')->nullable()->after('received_at')->constrained('users')->nullOnDelete();
                $table->timestamp('cancelled_at')->nullable()->after('received_by');
                $table->foreignId('cancelled_by')->nullable()->after('cancelled_at')->constrained('users')->nullOnDelete();
                $table->text('cancellation_reason')->nullable()->after('cancelled_by');
            }
            if (! Schema::hasColumn('stock_transfers', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('cancellation_reason')->constrained('users')->nullOnDelete();
            }
        });

        DB::table('stock_transfers')
            ->whereNull('transfer_number')
            ->orderBy('id')
            ->select(['id'])
            ->chunkById(100, function ($transfers): void {
                foreach ($transfers as $transfer) {
                    DB::table('stock_transfers')
                        ->where('id', $transfer->id)
                        ->update(['transfer_number' => 'TRF-'.str_pad((string) $transfer->id, 6, '0', STR_PAD_LEFT)]);
                }
            });

        Schema::table('stock_transfers', function (Blueprint $table): void {
            $table->string('transfer_number', 100)->nullable(false)->change();
            $table->unique('transfer_number');
        });

        Schema::table('stock_transfer_items', function (Blueprint $table): void {
            if (! Schema::hasColumn('stock_transfer_items', 'product_variant_id')) {
                $table->foreignId('product_variant_id')->nullable()->after('stock_transfer_id')->constrained('product_variants')->restrictOnDelete()->cascadeOnUpdate();
            }
            if (! Schema::hasColumn('stock_transfer_items', 'quantity_sent')) {
                $table->unsignedInteger('quantity_sent')->nullable()->after('quantity');
                $table->unsignedInteger('quantity_received')->nullable()->after('quantity_sent');
                $table->text('notes')->nullable()->after('quantity_received');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stock_transfer_items', function (Blueprint $table): void {
            if (Schema::hasColumn('stock_transfer_items', 'product_variant_id')) {
                $table->dropForeign(['product_variant_id']);
                $table->dropColumn('product_variant_id');
            }
            if (Schema::hasColumn('stock_transfer_items', 'quantity_sent')) {
                $table->dropColumn(['quantity_sent', 'quantity_received', 'notes']);
            }
        });

        Schema::table('stock_transfers', function (Blueprint $table): void {
            if (Schema::hasColumn('stock_transfers', 'transfer_number')) {
                $table->dropUnique(['transfer_number']);
                $table->dropColumn('transfer_number');
            }
            foreach (['sent_by', 'received_by', 'cancelled_by', 'created_by'] as $column) {
                if (Schema::hasColumn('stock_transfers', $column)) {
                    $table->dropForeign([$column]);
                }
            }
            $table->dropColumn([
                'status',
                'sent_at',
                'sent_by',
                'received_at',
                'received_by',
                'cancelled_at',
                'cancelled_by',
                'cancellation_reason',
                'created_by',
            ]);
        });
    }
};
