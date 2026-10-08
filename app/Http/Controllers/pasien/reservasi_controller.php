<?php

namespace App\Http\Controllers\pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\data_pasien;
use App\Models\jadwal_praktik;
use App\Models\reservasi;
use App\Models\antrian;
use App\Models\rekam_medis;
use Carbon\Carbon;

class reservasi_controller extends Controller
{
    //ambil no antrian
    public function buat_reservasi(Request $request)
    {
        //validasi input
        $validated = $request->validate([
            'tanggal_reservasi' => 'required|date|after_or_equal:today',
            'keluhan' => 'required|string|max:500',
        ],[
            'tanggal_reservasi.required' => 'Tanggal reservasi wajib dipilih',
            'tanggal_reservasi.after_or_equal' => 'Tidak dapat reservasi untuk tanggal yang sudah lewat',
            'keluhan.required' => 'Keluhan wajib diisi',
            'keluhan.max' => 'Keluhan maksimal 500 karakter',
        ]);

        $pasien_aktif = $this->get_pasien_aktif();
        

        if (!$pasien_aktif) {
            // Fallback ke pasien utama
            $user = Auth::user();
            $pasien = $user->pasiens()->where('is_primary', true)->first();
            
            if (!$pasien) {
            return redirect()->route('pasien.tambah_biodata')
                ->withErrors(['error' => 'Lengkapi biodata pasien terlebih dahulu.']);
    }
            $pasien_aktif = $pasien;
            session(['pasien_aktif_id' => $pasien_aktif->id]);
        }

        $tanggal = Carbon::parse($validated['tanggal_reservasi']);

        // VALIDASI 1: Hanya bisa reservasi untuk hari ini
        if (!$tanggal->isToday()) {
            return back()->withErrors([
                'error' => 'Reservasi hanya dapat dilakukan untuk hari ini'
            ])->withInput();
        }

        //cek apakah sudah punya reservasi aktif
        $reservasi_ditemukan = \App\Models\reservasi::where('id_pasien', $pasien_aktif->id)
            ->where('tanggal_reservasi', $tanggal->format('Y-m-d'))
            ->whereIn('status', ['menunggu', 'sedang_dilayani'])
            ->exists();
        
        if($reservasi_ditemukan) {
            return back()->withErrors([
                'error' => 'Anda sudah memiliki reservasi'
            ]);
        }

        //cek jadwal praktik
        $nama_hari = $this->get_nama_hari($tanggal);
        $jadwal = jadwal_praktik::whereDate('tanggal_jadwal_praktik', $tanggal->toDateString())
            ->first();
        
        if(!$jadwal || !$jadwal->is_active) {
            return back()->withErrors([
                'error' => 'Klinik tutup pada hari ' . $nama_hari
            ]);
        }

        //gunakan transaction untuk konsistensi data
        DB::beginTransaction();
        try {
            // Kunci baris antrian hari ini agar anti race-condition
            $antrian = antrian::firstOrCreate(
                ['tanggal_antrian' => $tanggal->format('Y-m-d')],
                ['nomor_sekarang' => 0, 'total_antrian' => 0]
            );
            // lock row
            $antrian = antrian::where('id', $antrian->id)->lockForUpdate()->first();

            // Ambil nomor terbesar yang TERPAKAI (menunggu/sedang_diperiksa/selesai)
            $maxTerpakai = reservasi::whereDate('tanggal_reservasi', $tanggal->toDateString())
                ->whereIn('status', ['menunggu','sedang_dilayani','selesai'])
                ->max('nomor_antrian');

            // Nomor berikutnya = max + 1 (null -> 0)
            $nomor_antrian = (int) ($maxTerpakai ?? 0) + 1;

            // Buat reservasi
            $reservasi = reservasi::create([
                'id_pasien'         => $pasien_aktif->id,
                'tanggal_reservasi' => $tanggal->toDateString(),
                'nomor_antrian'     => $nomor_antrian,
                'keluhan'           => $validated['keluhan'],
                'status'            => 'menunggu',
            ]);

            // Update total antrian AKTIF (bukan selesai)
            $total_aktif = \App\Models\reservasi::whereDate('tanggal_reservasi', $tanggal->toDateString())
                ->whereIn('status', ['menunggu','sedang_dilayani'])
                ->count();

            $antrian->update(['total_antrian' => $total_aktif]);

            DB::commit();

            return redirect()->route('pasien.dashboard', [
                'tanggal' => $tanggal->toDateString()
            ])->with('success', "Reservasi berhasil! Nomor antrian Anda: #{$nomor_antrian}");

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat membuat reservasi. Silakan coba lagi.'
            ])->withInput();
        }
    }

   
    public function batalkan_reservasi($id)
    {
        $user = Auth::user();
        $pasien_aktif = $this->get_pasien_aktif();

        $reservasi = reservasi::where('id', $id)
            ->where('id_pasien', $pasien_aktif->id)
            ->where('status', 'menunggu')
            ->firstOrFail();

        DB::beginTransaction();

        try{
            $reservasi->update(['status' => 'batal']);

            // Update total antrian (decrement)
            $antrian = antrian::where('tanggal_antrian', $reservasi->tanggal_reservasi)->first();
            
            if ($antrian) {
                // Hitung ulang total antrian yang masih aktif
                $total_aktif = reservasi::where('tanggal_reservasi', $reservasi->tanggal_reservasi)
                    ->whereIn('status', ['menunggu', 'sedang_dilayani'])
                    ->count();
                
                $antrian->update(['total_antrian' => $total_aktif]);
            }

            DB::commit();

            return redirect()->route('pasien.dashboard', [
                'tanggal_antrian' => $reservasi->tanggal_reservasi
            ])->with('success', 'Reservasi berhasil dibatalkan');
        } catch(\Exception $e){
            DB::rollBack();

            return back()->withErrors([
                'error' => 'Gagal membatalkan reservasi: ' . $e->getMessage()
            ]);
        }

        
       
    }

    public function riwayat_reservasi()
    {
        $user = Auth::user();
        $pasien = $this->get_pasien_aktif();

        if (!$pasien) return redirect()
            ->route('pasien.tambah_biodata')
            ->withErrors(['error' => 'Lengkapi biodata pasien terlebih dahulu.']);


        $riwayat = rekam_medis::query()
            ->where('id_pasien', $pasien->id)
            ->orderByDesc('tanggal_pemeriksaan')
            ->paginate(20);

        return view('pasien.riwayat_pemeriksaan', compact('pasien','riwayat'));
    }

    
    /**
     * Konversi Carbon date ke nama hari dalam Bahasa Indonesia
     */
    private function get_nama_hari($tanggal)
    {
        $hari_inggris = [
            'Monday', 'Tuesday', 'Wednesday', 'Thursday', 
            'Friday', 'Saturday', 'Sunday'
        ];
        
        $hari_indonesia = [
            'Senin', 'Selasa', 'Rabu', 'Kamis', 
            'Jumat', 'Sabtu', 'Minggu'
        ];
        
        return str_replace($hari_inggris, $hari_indonesia, $tanggal->format('l'));
    }

    private function get_pasien_aktif(): ?data_pasien
{
    $user = Auth::user();

    // 1) Coba dari session
    $pasienAktifId = session('pasien_aktif_id');
    if ($pasienAktifId) {
        $pasien = data_pasien::where('id', $pasienAktifId)
            ->where('id_akun', $user->id) // <= selaraskan FK-mu (id_akun atau id_user)
            ->first();

        if ($pasien) {
            return $pasien; // valid & milik user
        }

        // session basi / bukan milik user -> bersihkan
        session()->forget('pasien_aktif_id');
    }

    // 2) Fallback ke pasien utama
    $pasienUtama = $user->pasiens()->where('is_primary', true)->first();

    // simpan ke session untuk next request
    if ($pasienUtama) {
        session(['pasien_aktif_id' => $pasienUtama->id]);
    }

    return $pasienUtama; // bisa null kalau belum punya biodata sama sekali
}
}
