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
        Schema::create('computers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('device_uuid', 64)->unique();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('local_ip', 45)->nullable();
            $table->integer('local_port')->default(5050);
            $table->string('status', 20)->default('offline'); // 'online', 'offline'
            $table->timestamp('last_seen_at')->nullable();
            $table->json('capabilities')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('device_uuid');
            $table->index('last_seen_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('computers');
    }
};
