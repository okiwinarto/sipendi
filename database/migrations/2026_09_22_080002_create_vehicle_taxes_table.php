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
        Schema::create('vehicle_taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->enum('jenis_pajak', ['tahunan', 'lima_tahunan', 'kir']);
            $table->date('tanggal_bayar')->nullable();
            $table->date('masa_berlaku_sampai');
            $table->decimal('biaya', 12, 2)->nullable();
            $table->string('dokumen', 255)->nullable();
            $table->enum('status', ['aktif', 'akan_jatuh_tempo', 'kadaluarsa'])->default('aktif');
            $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['vehicle_id', 'jenis_pajak', 'masa_berlaku_sampai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_taxes');
    }
};
