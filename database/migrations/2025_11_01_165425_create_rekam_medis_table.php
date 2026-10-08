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
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id();
            
            // Nomor Rekam Medis (RM-20251031-001)
            $table->string('nomor_rekam_medis')->unique();
            
            // Foreign Keys
            $table->foreignId('id_pasien')
                  ->constrained('data_pasiens')
                  ->onDelete('cascade');
            
            $table->foreignId('id_reservasi')
                  ->constrained('reservasis')
                  ->onDelete('cascade');
            
            $table->date('tanggal_pemeriksaan');
            
            // Data yang pasien bisa lihat
            $table->decimal('tinggi_badan', 5, 2)->nullable(); // 170.50 cm
            $table->decimal('berat_badan', 5, 2)->nullable(); // 65.50 kg
            $table->string('tekanan_darah', 10)->nullable(); // 120/80
            $table->decimal('suhu', 4, 2)->nullable(); // 36.50 °C
            $table->text('diagnosa')->nullable();
            $table->text('saran')->nullable();
            $table->text('rencana_tindak_lanjut')->nullable();
            $table->text('catatan_tambahan')->nullable();
            
            $table->text('riwayat_alergi')->nullable();
            $table->text('resep_obat')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekam_medis');
    }
};
