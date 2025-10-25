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
        Schema::create('agenda_karyawan', function (Blueprint $table) {
            $table->foreignId('agenda_id')->constrained('agendas')->onDelete('cascade');

            $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');

            $table->primary(['agenda_id', 'karyawan_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_karyawan');
    }
};
