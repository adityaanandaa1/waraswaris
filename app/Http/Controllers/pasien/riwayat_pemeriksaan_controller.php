<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\reservasi;
use App\Models\rekam_medis;
use App\Models\data_pasien;
use App\Models\data_dokter;

class riwayat_pemeriksaan_controller extends Controller
{
    /**
     * Menampilkan daftar riwayat pemeriksaan
     */
    public function riwayat_pemeriksaan()
    {
        $user = Auth::user();
        $pasien_aktif = $this->get_pasien_aktif();

        if (!$pasien_aktif) {
            return redirect()
                ->route('pasien.tambah_biodata')
                ->withErrors(['error' => 'Lengkapi biodata pasien terlebih dahulu.']);
        }

        // Ambil reservasi yang sudah selesai DAN memiliki rekam medis
        $reservasis = reservasi::query()
            ->with(['rekam_medis:id,id_reservasi,nomor_rekam_medis,tanggal_pemeriksaan,diagnosa'])
            ->where('id_pasien', $pasien_aktif->id)
            ->where('status', 'selesai')
            ->whereHas('rekam_medis') // Hanya yang punya rekam medis
            ->orderBy('tanggal_reservasi', 'desc')
            ->paginate(10);

        return view('pasien.dashboard', compact('pasien_Aktif', 'reservasis'));
    }

    /**
     * Menampilkan detail rekam medis (versi publik untuk pasien)
     */
    public function detail($reservasiId)
    {
        $user = Auth::user();
        $pasien_aktif = $this->get_pasien_aktif();

        if (!$pasien_aktif) {
            return redirect()
                ->route('pasien.tambah_biodata')
                ->withErrors(['error' => 'Lengkapi biodata pasien terlebih dahulu.']);
        }

        // Validasi kepemilikan reservasi
        $reservasi = reservasi::with(['rekam_medis', 'data_pasien', 'data_dokter'])
            ->where('id', $reservasiId)
            ->where('id_pasien', $pasien_aktif->id)
            ->where('status', 'selesai')
            ->whereHas('rekam_medis')
            ->firstOrFail();

        // Ambil data publik rekam medis (tanpa alergi & resep)
        $rekam_medis = $reservasi->rekam_medis;
        $dataPublik = $rekam_medis->data_publik;
        $dokter = $reservasi->data_dokter ?: data_dokter::first();

        return view('pasien.riwayat_pemeriksaan', compact('reservasi', 'rekam_medis', 'dataPublik', 'pasien_aktif', 'dokter'));
    }

    /**
     * Helper: Ambil pasien aktif dari session
     */
    private function get_pasien_aktif(): ?data_pasien
    {
        $user = Auth::user();

        // 1. Coba dari session
        $pasien_aktifId = session('pasien_aktif_id');
        
        if ($pasien_aktifId) {
            $pasien = data_pasien::where('id', $pasien_aktifId)
                ->where('id_akun', $user->id)
                ->first();

            if ($pasien) {
                return $pasien;
            }

            session()->forget('pasien_aktif_id');
        }

        // 2. Fallback ke pasien utama
        $pasienUtama = $user->pasiens()
            ->where('is_primary', true)
            ->first();

        if ($pasienUtama) {
            session(['pasien_aktif_id' => $pasienUtama->id]);
        }

        return $pasienUtama;
    }
}
