<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\akun_user;
use App\Models\data_dokter;
use App\Models\data_resepsionis;

class user_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // seeder akun dokter
        $userDokter = akun_user::updateOrCreate([ //akun_user dari model
            'email' => 'dokter@gmail.com',
            'password' => Hash::make('dokter123'), // Password: dokter123
            'role' => 'dokter',
        ]);

        // Buat data dokter ambil method dari model data_dokter
        data_dokter::updateOrCreate([
            'id_akun' => $userDokter->id,
            'nama_dokter' => 'dr. Muhammad Abdul Waris',
            'tanggal_lahir_dokter' => '1997-08-01',
            'nomor_str' => 'STR-2024-001',
            'nomor_sip' => 'SIP-2024-001',
        ]);

        // buat akun resepsionis
        $userResepsionis = akun_user::updateOrCreate([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'), // Password: admin123
            'role' => 'resepsionis',
        ]);

        // Buat data resepsionis terkait
        data_resepsionis::updateOrCreate([
            'id_akun' => $userResepsionis->id,
            'nama_resepsionis' => 'Admin Klinik',
        ]);

        // buat akun seeder pasien
        $userPasien = akun_user::updateOrCreate([
            'email' => 'pasien@gmail.com',
            'password' => Hash::make('pasien123'), // Password: pasien123
            'role' => 'pasien',
        ]);

    }
}
