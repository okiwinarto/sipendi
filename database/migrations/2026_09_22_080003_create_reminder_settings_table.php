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
        Schema::create('reminder_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe_reminder', ['service', 'pajak_tahunan', 'pajak_5tahunan', 'kir', 'sim_sopir'])->unique();
            $table->unsignedSmallInteger('h_minus_tahap1')->default(30);
            $table->unsignedSmallInteger('h_minus_tahap2')->default(14);
            $table->unsignedSmallInteger('h_minus_tahap3')->default(1);
            $table->json('target_role');
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminder_settings');
    }
};
