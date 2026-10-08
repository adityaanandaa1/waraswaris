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
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_pasien') //fk id_pasien
                  ->constrained('data_pasiens') //relasi ke tabel data_pasien
                  ->onDelete('restrict');
            
            $table->date('tanggal_reservasi'); 
            $table->integer('nomor_antrian'); // Nomor antrian (1, 2, 3...)
            $table->text('keluhan')->nullable();

            $table->enum('status', ['menunggu', 'sedang_dilayani', 'selesai',   
                'batal'])->default('menunggu');
            
                
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};
