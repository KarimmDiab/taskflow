<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('message');
            $table->string('type', 100);
            $table->string('module', 100);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
            $table->index(['module', 'type']);
            $table->index(['reference_id', 'type', 'is_read']);
            $table->index('created_at');
            $table->engine('InnoDB');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
