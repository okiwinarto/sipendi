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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman', 30)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('unit_kerja_id')->constrained('unit_kerja')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->enum('jenis_pengemudi', ['sopir_dinas', 'swakemudi'])->default('sopir_dinas');
            $table->text('tujuan_perjalanan');
            $table->string('kota_tujuan', 100);
            $table->date('tanggal_berangkat');
            $table->time('jam_berangkat');
            $table->date('tanggal_kembali_rencana');
            $table->time('jam_kembali_rencana');
            $table->tinyInteger('jumlah_penumpang')->default(1);
            $table->enum('tingkat_prioritas', ['normal', 'mendesak'])->default('normal');
            $table->string('no_surat_tugas', 50)->nullable();
            $table->string('file_surat_tugas', 255)->nullable();
            $table->enum('status', [
                'diajukan',
                'diverifikasi_garasi',
                'ditolak_garasi',
                'disetujui',
                'ditolak_pimpinan',
                'kendaraan_keluar',
                'kendaraan_kembali',
                'selesai',
                'dibatalkan',
            ])->default('diajukan');
            $table->text('catatan_pemohon')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
