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
        Schema::create('vehicle_checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('petugas_id')->constrained('users')->cascadeOnDelete();
            $table->integer('odometer_masuk');
            $table->enum('level_bbm_masuk', ['E', '1/4', '1/2', '3/4', 'F']);
            $table->enum('kondisi_kendaraan', ['baik', 'perlu_perhatian'])->default('baik');
            $table->boolean('ada_kerusakan')->default(false);
            $table->text('deskripsi_kerusakan')->nullable();
            $table->json('checklist_kelengkapan');
            $table->json('foto_kondisi')->nullable();
            $table->tinyInteger('rating_kondisi')->nullable();
            $table->text('catatan')->nullable();
            $table->dateTime('waktu_masuk');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_checkins');
    }
};
