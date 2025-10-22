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
        Schema::create('detail_jadwals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_jadwal_kerja')->constrained('jadwal_kerjas')->onDelete('cascade');

            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);

            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();

            $table->boolean('hari_kerja')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_jadwals');
    }
};
