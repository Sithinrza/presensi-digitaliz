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
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->foreignId('agama_id')->constrained('agamas');
            $table->foreignId('jabatan_id')->constrained('jabatans');
            $table->foreignId('divisi_id')->constrained('divisis');
            $table->foreignId('posisi_id')->constrained('posisis');
            $table->foreignId('pendidikan_terakhir_id')->constrained('pendidikan_terakhirs');

            $table->string('nip', 50)->unique();
            $table->string('nama_lengkap', 100);
            $table->text('alamat')->nullable();
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan']);
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('no_telepon', 20)->nullable();

            $table->date('tanggal_bergabung');
            $table->string('foto_profil', 255)->nullable();
            $table->enum('status_karyawan', ['Aktif', 'Tidak Aktif'])->default('Aktif');

            // Timestamps
            $table->timestamps(); // Membuat created_at dan updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
