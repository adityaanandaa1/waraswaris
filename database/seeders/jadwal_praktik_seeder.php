<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\jadwal_praktik;
use Carbon\Carbon;

class jadwal_praktik_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hariMap = [
            'monday'    => 'senin',
            'tuesday'   => 'selasa',
            'wednesday' => 'rabu',
            'thursday'  => 'kamis',
            'friday'    => 'jumat',
            'saturday'  => 'sabtu',
            'sunday'    => 'minggu',
        ];
        $awalHariIni = Carbon::now('Asia/Jakarta')->startOfDay();
        $jumlahHari  = 28; // 4 minggu

        for ($i = 0; $i < $jumlahHari; $i++) {
            $tanggal = $awalHariIni->copy()->addDays($i);
            $hariInggris = strtolower($tanggal->format('l'));
            $hari = $hariMap[$hariInggris];

            jadwal_praktik::updateOrCreate(
                ['hari' => $hari, 'tanggal_jadwal_praktik' => $tanggal->toDateString()],
                ['jam_mulai' => '09:00:00', 'jam_selesai' => '21:00:00', 'is_active' => true]
            );
        }
    }
}
