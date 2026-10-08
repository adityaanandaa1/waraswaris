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
        Schema::create('jadwal_praktiks', function (Blueprint $table) {
            $table->id();

            $table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis',
                        'jumat', 'sabtu', 'minggu']);
            $table->date('tanggal_jadwal_praktik');
            $table->time('jam_mulai')->nullable()->default('09:00:00'); // Jam buka 
            $table->time('jam_selesai')->nullable()->default(('15:00:00')); // Jam tutup 

            // Status aktif/tutup (1 = buka, 0 = tutup/libur)
            $table->boolean('is_active')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_praktiks');
    }
};
