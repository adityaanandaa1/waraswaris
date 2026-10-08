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
        Schema::create('antrians', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_antrian');
            $table->integer('nomor_sekarang');
            $table->integer('total_antrian');

        // status slot nomor untuk kebutuhan ui
        $table->enum('status', [
        'tersedia',        // bisa diambil
        'menunggu',        // sudah diambil, menunggu giliran
        'sedang_dilayani', // sedang dipanggil/dilayani
        'selesai',         // selesai dilayani (riwayat)
        'batal',           // dibatalkan; bisa direuse
    ])->default('tersedia');

        $table->foreignId('id_reservasi')->nullable()
              ->constrained('reservasis')
              ->nullOnDelete();
       
        $table->foreignId('id_pasien')->nullable()
              ->constrained('data_pasiens')
              ->nullOnDelete();

        $table->timestamps();

        // Satu nomor unik per hari (dan per dokter bila dipakai)
        $table->unique(['tanggal_antrian','nomor_sekarang']);  
        $table->index(['tanggal_antrian','status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('antrians', function (Blueprint $table) {
        // Sesuaikan nama kolom FK-mu:
        $table->dropForeign(['id_reservasi']); // atau ['id_reservasi']
        $table->dropForeign(['id_pasien']);    // atau ['id_pasien']
    });

        Schema::dropIfExists('antrians');
    }
};
