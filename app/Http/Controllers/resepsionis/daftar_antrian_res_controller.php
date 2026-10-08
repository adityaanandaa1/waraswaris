<?php

namespace App\Http\Controllers\resepsionis;

use App\Http\Controllers\Controller;
use App\Models\data_dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\jadwal_praktik;
use App\Models\reservasi;
use App\Models\data_pasien;
use App\Models\data_resepsionis;
use App\Models\akun_user;
use App\Models\antrian;


class daftar_antrian_res_controller extends Controller
{
    public function daftar_antrian_res()
    {
        $user = Auth::user();
        $resepsionis = $user->data;

        $hari_ini = today();
        $nama_hari = $hari_ini->locale('id')->translatedFormat('l');
        
        // Ambil jadwal dokter hari ini
        $jadwal = jadwal_praktik::where('hari', $nama_hari)->first();
        
        // Ambil daftar pasien yang reservasi hari ini
        $daftar_antrian = reservasi::query()
            ->whereDate('tanggal_reservasi', $hari_ini)
            ->whereIn('status', ['menunggu', 'sedang_dilayani', 'selesai', 'batal'])
            ->with(['data_pasien:id,nama_pasien'])
            ->orderByRaw('CAST(nomor_antrian AS UNSIGNED) ASC')
            ->get();

        $nomor_terakhir = reservasi::whereDate('tanggal_reservasi', $hari_ini)
            ->where('status', 'selesai')
            ->max('nomor_antrian'); // yang terakhir selesai

        $sedang_dilayani = reservasi::whereDate('tanggal_reservasi', $hari_ini)
            ->where('status', 'sedang_dilayani')
            ->orderByDesc('updated_at')
            ->value('nomor_antrian');
                
        // Ambil info antrian
        $antrian = (object)[
            'nomor_sekarang' => $sedang_dilayani ?? $nomor_terakhir ?? 0,
            'total_antrian' => reservasi::whereDate('tanggal_reservasi', $hari_ini)
                ->whereIn('status', ['menunggu', 'sedang_dilayani', 'selesai'])
                ->count()
        ];
        
        return view('resepsionis.daftar_antrian_resepsionis', compact(
            'hari_ini',
            'nama_hari',
            'jadwal',
            'daftar_antrian',
            'antrian'
        ));
    }

    public function lewati_antrian($id)
    {
        DB::beginTransaction();

        try{
            $reservasi = reservasi::findOrFail($id);

            //update status
            $reservasi->update(['status' => 'batal']);

            $today = today();

            // cari pasien menunggu berikutnya (prioritas nomor setelah yang dilewati)
            $nomor_selanjutnya = reservasi::whereDate('tanggal_reservasi', $today)
                ->where('status', 'menunggu')
                ->whereRaw('CAST(nomor_antrian AS UNSIGNED) > ?', [(int) $reservasi->nomor_antrian])
                ->orderByRaw('CAST(nomor_antrian AS UNSIGNED) ASC')
                ->first();

            if (!$nomor_selanjutnya) {
                $nomor_selanjutnya = reservasi::whereDate('tanggal_reservasi', $today)
                    ->where('status', 'menunggu')
                    ->orderByRaw('CAST(nomor_antrian AS UNSIGNED) ASC')
                    ->first();
            }

            $nomor_dilayani = reservasi::whereDate('tanggal_reservasi', $today)
                ->where('status', 'sedang_dilayani')
                ->exists();

            // otomatis jadikan nomor berikutnya "sedang dilayani" layaknya tombol periksa
            if ($nomor_selanjutnya && !$nomor_dilayani) {
                $nomor_selanjutnya->update(['status' => 'sedang_dilayani']);

                $antrian = antrian::where('tanggal_antrian', $today)->first();
                if ($antrian) {
                    $antrian->update(['nomor_sekarang' => (int) $nomor_selanjutnya->nomor_antrian]);
                }
            }

            DB::commit();

            return back()->with('succes', "pasien #{$reservasi->nomor_antrian} dilewati");
        
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Gagal melewati antrian']);
        }
    }

    public function detail_reservasi($id)
    {
        try {
            // Log untuk debugging
            Log::info("Mengakses detail reservasi ID: {$id}");

            // Eager load relasi yang dibutuhkan
            $reservasi = reservasi::with([
                'data_pasien:id,id_akun,nama_pasien,foto_path,jenis_kelamin,tanggal_lahir_pasien,golongan_darah,pekerjaan,alamat,no_telepon,catatan_pasien'
            ])->findOrFail($id);

            // Validasi data pasien ada
            if (!$reservasi->data_pasien) {
                Log::error("Data pasien tidak ditemukan untuk reservasi ID: {$id}");
                return response()->json([
                    'error' => 'Data pasien tidak ditemukan'
                ], 404);
            }

            // Hitung umur dari tanggal lahir
            $tanggal_lahir_pasien = null;
            $umur = null;
            
            if ($reservasi->data_pasien->tanggal_lahir_pasien) {
                try {
                    $tanggal_lahir_pasien = Carbon::parse($reservasi->data_pasien->tanggal_lahir_pasien);
                    $umur = $tanggal_lahir_pasien->age;
                } catch (\Exception $e) {
                    Log::warning("Gagal parse tanggal lahir: " . $e->getMessage());
                }
            }

            // Cari nama wali/pemilik akun
            $nama_wali = '-';

            $akun_wali = $reservasi->data_pasien ?? null; // jaga-jaga null
            if ($akun_wali && $akun_wali->id_akun) {
                // 1) Coba ambil pasien utama (is_primary = true)
                $wali = data_pasien::where('id_akun', $akun_wali->id_akun)
                        ->where('is_primary', true)
                        ->first('nama_pasien');

                // 2) Kalau belum ada yang ditandai primary, ambil satu yang ada
                if (!$wali) {
                    $wali = data_pasien::where('id_akun', $akun_wali->id_akun)
                            ->orderBy('id', 'asc')
                            ->first('nama_pasien');
                }

                // 3) Pilih kolom nama yang tersedia
                if ($wali) {
                    $nama_wali = $wali->nama_pasien ??  '-';
                }
            }

            // Format jenis kelamin
            $jenis_kelamin = $reservasi->data_pasien->jenis_kelamin;
            if ($jenis_kelamin === 'L' || strtolower($jenis_kelamin) === 'laki-laki') {
                $jenis_kelamin = 'Laki-laki';
            } elseif ($jenis_kelamin === 'P' || strtolower($jenis_kelamin) === 'perempuan') {
                $jenis_kelamin = 'Perempuan';
            }

            // Siapkan data response
            $data = [
                'keluhan' => $reservasi->keluhan ?? '-',
                'nama_pasien' => $reservasi->data_pasien->nama_pasien ?? '-',
                'nama_wali' => $nama_wali,
                'jenis_kelamin' => $jenis_kelamin,
                'tanggal_lahir' => $tanggal_lahir_pasien ? $tanggal_lahir_pasien->format('d F Y') : '-',
                'golongan_darah' => $reservasi->data_pasien->golongan_darah ?? 'Tidak diketahui',
                'umur' => $umur,
                'pekerjaan' => $reservasi->data_pasien->pekerjaan ?? 'Tidak bekerja',
                'alamat' => $reservasi->data_pasien->alamat ?? '-',
                'no_telepon' => $reservasi->data_pasien->no_telepon ?? '-',
                'catatan_pasien' => $reservasi->data_pasien->catatan_pasien ?? 'Tidak ada catatan',
                'foto_path' => $reservasi->data_pasien->foto_path ?? null,
            ];

            Log::info("Data berhasil disiapkan untuk reservasi ID: {$id}");

            return response()->json($data);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Reservasi tidak ditemukan: {$id}");
            return response()->json([
                'error' => 'Reservasi tidak ditemukan'
            ], 404);

        } catch (\Exception $e) {
            Log::error("Error pada detail_reservasi: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            
            return response()->json([
                'error' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()
            ], 500);
        }
    }

    

}
