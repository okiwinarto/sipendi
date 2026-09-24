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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garasi_id')->constrained('garasi')->cascadeOnDelete();
            $table->foreignId('kategori_id')->constrained('vehicle_categories')->cascadeOnDelete();
            $table->string('no_polisi', 15)->unique();
            $table->string('no_rangka', 50)->nullable();
            $table->string('no_mesin', 50)->nullable();
            $table->string('no_bpkb', 50)->nullable();
            $table->string('merk', 50);
            $table->string('tipe_model', 50);
            $table->year('tahun_pembuatan');
            $table->string('warna', 30);
            $table->enum('bahan_bakar', ['bensin', 'solar', 'listrik', 'hybrid'])->default('bensin');
            $table->tinyInteger('kapasitas_penumpang')->default(5);
            $table->string('foto_utama', 255)->nullable();
            $table->enum('status', ['tersedia', 'dipinjam', 'maintenance', 'perlu_perhatian', 'nonaktif'])->default('tersedia');
            $table->integer('odometer_terakhir')->default(0);
            $table->integer('interval_service_km')->nullable()->default(5000);
            $table->tinyInteger('interval_service_bulan')->nullable()->default(6);
            $table->date('tanggal_service_terakhir')->nullable();
            $table->integer('odometer_service_terakhir')->nullable();
            $table->date('tanggal_pajak_tahunan')->nullable();
            $table->date('tanggal_pajak_5tahunan')->nullable();
            $table->date('tanggal_kir_berlaku')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
