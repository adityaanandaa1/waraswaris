<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\akun_user;
use App\Models\data_pasien;

class pasien_seeder extends Seeder
{
    public function run(): void
    {
        // Ambil user pasien yang sudah dibuat di UserSeeder
        $userPasien = akun_user::where('email', 'pasien@gmail.com')->first();

        // Buat data pasien utama (pemilik akun)
        data_pasien::updateOrCreate([
            'id_akun' => $userPasien->id,
            'nama_pasien' => 'Budi Santoso',
            'tanggal_lahir_pasien' => '1990-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'golongan_darah' => 'O',
            'alamat' => 'Jl. Merdeka No. 123, Malang',
            'no_telepon' => '081234567890',
            'pekerjaan' => 'Guru',
            'catatan_pasien' => 'Alergi udang dan kacang',
            'is_primary' => '1', // Pemilik akun utama
        ]);

        // Buat anggota keluarga
        data_pasien::updateOrCreate([
            'id_akun' => $userPasien->id,
            'nama_pasien' => 'Ani Santoso',
            'tanggal_lahir_pasien' => '1992-08-20',
            'jenis_kelamin' => 'Perempuan',
            'golongan_darah' => 'A',
            'alamat' => 'Jl. Merdeka No. 123, Malang',
            'no_telepon' => '081234567890',
            'pekerjaan' => 'Ibu Rumah Tangga',
            'catatan_pasien' => null,
            'is_primary' => '0', // Anggota keluarga
        ]);
    }
}