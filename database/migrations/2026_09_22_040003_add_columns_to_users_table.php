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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('unit_kerja_id')->nullable()->after('id')->constrained('unit_kerja')->nullOnDelete();
            $table->foreignId('garasi_id')->nullable()->after('unit_kerja_id')->constrained('garasi')->nullOnDelete();
            $table->string('nip', 30)->nullable()->unique()->after('name');
            $table->string('no_hp', 20)->nullable()->after('email');
            $table->string('foto', 255)->nullable()->after('no_hp');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('foto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['unit_kerja_id']);
            $table->dropForeign(['garasi_id']);
            $table->dropColumn(['unit_kerja_id', 'garasi_id', 'nip', 'no_hp', 'foto', 'status']);
        });
    }
};
