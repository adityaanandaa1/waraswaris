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
        Schema::create('data_resepsionis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_akun')
                  ->constrained('akun_users')
                  ->onDelete('cascade'); 
            $table->string('nama_resepsionis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_resepsionis');
    }
};
