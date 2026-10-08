<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            // 1. Hapus foreign key lama
            $table->dropForeign(['id_pasien']);

            // 2. Buat ulang dengan ON DELETE CASCADE
            $table->foreign('id_pasien')
                  ->references('id')
                  ->on('data_pasiens')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            $table->dropForeign(['id_pasien']);

            $table->foreign('id_pasien')
                  ->references('id')
                  ->on('data_pasiens')
                  ->onDelete('restrict');
        });
    }
};
