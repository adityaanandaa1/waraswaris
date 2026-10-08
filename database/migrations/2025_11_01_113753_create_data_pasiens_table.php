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
        Schema::create('data_pasiens', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_akun')
                  ->constrained('akun_users') //relasi ke tabel akun_user
                  ->onDelete('cascade'); // Jika user dihapus, pasien ikut terhapus

            $table->string('nama_pasien');
            $table->date('tanggal_lahir_pasien');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']); 
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O']);
            $table->text('alamat');
            $table->string('no_telepon', 15); // Nomor telepon (max 15 digit)
            $table->string('pekerjaan')->nullable(); // boleh kosong
            $table->text('catatan_pasien')->nullable(); // boleh kosong

            // Penanda apakah ini pemilik akun utama (1 = ya, 0 = anggota keluarga)
            $table->boolean('is_primary')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pasiens');
    }
};
