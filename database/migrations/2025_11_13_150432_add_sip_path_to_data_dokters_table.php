<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_dokters', function (Blueprint $table) {
            $table->string('sip_path')->nullable()->after('nama_dokter');
        });
    }

    public function down(): void
    {
        Schema::table('data_dokters', function (Blueprint $table) {
            $table->dropColumn('sip_path');
        });
    }
};
