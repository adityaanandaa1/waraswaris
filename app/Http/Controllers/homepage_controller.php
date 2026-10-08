<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\jadwal_praktik;
use Carbon\Carbon;

class homepage_controller extends Controller
{
    public function homepage(Request $request)
    {
        // Ambil tanggal dari request atau gunakan hari ini sebagai default
        $tanggal_dipilih = $request->filled('tanggal')
            ? Carbon::parse($request->input('tanggal'), 'Asia/Jakarta')->startOfDay()
            : Carbon::today('Asia/Jakarta');

        $tanggal_dipilih->locale('id');

        // Konversi nama hari ke bahasa Indonesia
        $nama_hari = $this->get_nama_hari($tanggal_dipilih);
        $hari_enum = strtolower($nama_hari);

        // Ambil jadwal praktik untuk hari yang dipilih
        $jadwal = jadwal_praktik::where('hari', $hari_enum)
            ->where('tanggal_jadwal_praktik', $tanggal_dipilih->toDateString())
            ->first();

        // Jika tidak ada jadwal spesifik untuk tanggal tersebut, cari jadwal default untuk hari tersebut
        if (!$jadwal) {
            // Cari jadwal terbaru untuk hari yang sama
            $jadwal = jadwal_praktik::where('hari', $hari_enum)
                ->orderBy('tanggal_jadwal_praktik', 'desc')
                ->first();
            
            // Jika masih tidak ada, buat jadwal default
            if (!$jadwal) {
                $jadwal = (object) [
                    'hari' => $hari_enum,
                    'jam_mulai' => '09:00',
                    'jam_selesai' => '21:00',
                    'is_active' => true,
                ];
            }
        }

        return view('homepage', compact('jadwal', 'nama_hari', 'tanggal_dipilih'));
    }

    private function get_nama_hari($tanggal)
    {
        $hari_inggris = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $hari_indonesia = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        return str_replace($hari_inggris, $hari_indonesia, $tanggal->format('l'));
    }
}