<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder satu per satu
        $this->call([ // Jalankan seeder yang didaftarkan
            user_seeder::class, // Seeder untuk User, Dokter, Resepsionis
            jadwal_praktik_seeder::class, // Seeder untuk Jadwal Praktik
            pasien_seeder::class,
        ]);
    }
}
