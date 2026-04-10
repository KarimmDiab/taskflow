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
        Schema::create('expenses_details', function (Blueprint $table) {
            $table->id();
            $table->date('expenses_date');
            $table->decimal('expenses_cost',10,2)->unsigned()->default(0);
            $table->string('expenses_note');
            $table->string('expenses_image')->nullable();
            $table->foreignId('expenses_item_id')->constrained('expenses_items')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('expenses_details');
    }
};
