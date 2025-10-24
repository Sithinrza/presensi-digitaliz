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
        Schema::create('jadwal_pegawai', function (Blueprint $table) {
            // Id_jadwal_pegawai int PK AUTO INC
            $table->id();

            // Id_pegawai FK (saya asumsikan mengarah ke tabel 'karyawans')
            $table->foreignId('id_karyawan')
                  ->constrained('karyawans')
                  ->onDelete('cascade');

            // Id_jadwal_kerja FK
            $table->foreignId('id_jadwal_kerja')
                  ->constrained('jadwal_kerjas')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pegawai');
    }
};
