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
        Schema::create('vehicle_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->enum('jenis_service', ['rutin', 'berkala', 'insidentil', 'perbaikan_kerusakan'])->default('berkala');
            $table->date('tanggal_service');
            $table->unsignedInteger('odometer_saat_service');
            $table->string('bengkel', 100)->nullable();
            $table->decimal('biaya', 12, 2)->nullable();
            $table->text('deskripsi_pekerjaan');
            $table->string('dokumen_nota', 255)->nullable();
            $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['vehicle_id', 'tanggal_service']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_maintenances');
    }
};
