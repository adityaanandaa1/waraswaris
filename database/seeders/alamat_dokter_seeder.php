<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\data_dokter;

class alamat_dokter_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update alamat untuk dokter yang sudah ada
        $dokters = data_dokter::all();

        foreach ($dokters as $dokter) {
            $dokter->update([
                'alamat' => 'Jl. Brawijaya Center No.2, Arjasa 6, Kalisat, Kec. Kalisat, Kabupaten Jember, Jawa Timur 68183'
            ]);
        }

        // Atau jika ingin update spesifik
        data_dokter::where('id', 1)->update([
            'alamat' => 'Jl. Brawijaya Center No.2, Arjasa 6, Kalisat, Kec. Kalisat, Kabupaten Jember, Jawa Timur 68183'
        ]);

        $this->command->info('Alamat dokter berhasil ditambahkan!');
    }
}