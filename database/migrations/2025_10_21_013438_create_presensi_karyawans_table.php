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
        Schema::create('presensi_karyawans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');
            $table->foreignId('status_id')->constrained('status_presensis')->onDelete('restrict');
            $table->date('tanggal');

            $table->dateTime('waktu_ci')->nullable();
            $table->string('foto_ci')->nullable();
            $table->decimal('latitude_ci', 10, 8)->nullable();
            $table->decimal('longitude_ci', 11, 8)->nullable();

            $table->dateTime('waktu_co')->nullable();
            $table->string('foto_co')->nullable();
            $table->decimal('latitude_co', 10, 8)->nullable();
            $table->decimal('longitude_co', 11, 8)->nullable();

            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_karyawans');
    }
};
