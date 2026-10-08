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
        Schema::create('data_dokters', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_akun') //FK id_akun
                  ->constrained('akun_users') //relasi ke tabel akun_user
                  ->onDelete('cascade');
            
            $table->string('nama_dokter'); 
            $table->date('tanggal_lahir_dokter');
            $table->string('nomor_str')->unique(); // Nomor STR (harus unik)
            $table->string('nomor_sip')->unique(); // Nomor SIP (harus unik)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_dokters');
    }
};
