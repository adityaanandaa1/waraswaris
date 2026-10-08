<?php

namespace App\Http\Controllers\Dokter;

use Carbon\Carbon;
use App\Models\reservasi;
use App\Models\data_dokter;
use App\Models\data_pasien;
use App\Models\rekam_medis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class rekam_medis_controller extends Controller
{

    public function getReservasiDetail($id)
    {
        try {
            // Ambil data reservasi
            $reservasi = Reservasi::with(['data_pasien', 'data_dokter', 'rekam_medis'])->findOrFail($id);

            if (!$reservasi->data_pasien) {
                return response()->json(['error' => 'Data pasien tidak ditemukan'], 404);
            }

            // Pastikan nomor rekam medis tersedia saat form diisi
            $nomorRekamMedis = $this->ensureNomorRekamMedis($reservasi);
        
            // Format tanggal lahir pasien
            $pasien = $reservasi->data_pasien;
            $tanggal_lahir_pasien = $pasien->tanggal_lahir_pasien
                ? Carbon::parse($reservasi->data_pasien->tanggal_lahir_pasien)
                : null;
        
            // Hitung umur
            $umur = $tanggal_lahir_pasien
                ? $tanggal_lahir_pasien->age
                : null;
        
            // Format jenis kelamin
            $jenis_kelamin = $pasien->jenis_kelamin;
        
            if ($jenis_kelamin === 'L' || strtolower($jenis_kelamin) === 'laki-laki') {
                $jenis_kelamin = 'Laki-laki';
            } elseif ($jenis_kelamin === 'P' || strtolower($jenis_kelamin) === 'perempuan') {
                $jenis_kelamin = 'Perempuan';
            } else {
                $jenis_kelamin = '-';
            }
        
            // Nama wali (pakai pasien utama pada akun yang sama jika ada)
            $nama_wali = '-';
            if ($pasien && $pasien->id_akun) {
                $wali = \App\Models\data_pasien::where('id_akun', $pasien->id_akun)
                    ->where('is_primary', true)
                    ->first('nama_pasien');
                if (!$wali) {
                    $wali = \App\Models\data_pasien::where('id_akun', $pasien->id_akun)
                        ->orderBy('id', 'asc')
                        ->first('nama_pasien');
                }
                $nama_wali = $wali->nama_pasien ?? '-';
            }

            // Data dokter (pakai relasi di reservasi, fallback ke dokter yang login)
            $dokter        = $reservasi->data_dokter ?: Auth::user()->dokter;
            $nama_dokter   = $dokter->nama_dokter ?? '-';
            $alamat_dokter = $dokter->alamat ?? '-';
        
            // Siapkan data response 
            $data = [
                'nomor_rekam_medis'    => $nomorRekamMedis ?? '-',
                'keluhan'               => $reservasi->keluhan ?? '-',
                'nama_pasien'           => $pasien->nama_pasien ?? '-',
                'nama_wali'             => $nama_wali,
            
                // WAJIB sama seperti JS-mu
                'jenis_kelamin_pasien'  => $jenis_kelamin,
                'tanggal_lahir_pasien'  => $tanggal_lahir_pasien
                                            ? $tanggal_lahir_pasien->translatedFormat('d F Y')
                                            : '-',
            
                'golongan_darah'        => $pasien->golongan_darah ?? 'Tidak diketahui',
                'umur'                  => $umur,
                'pekerjaan'             => $pasien->pekerjaan ?? 'Tidak bekerja',
                'alamat'                => $pasien->alamat ?? '-',
                'no_telepon'            => $pasien->no_telepon ?? '-',
                'catatan_pasien'        => $pasien->catatan_pasien ?? 'Tidak ada catatan',
            
                // Jika ada dokter
                'nama_dokter'           => $nama_dokter,
                'alamat_dokter'         => $alamat_dokter,
            ];
        
            Log::info("Data berhasil disiapkan untuk reservasi ID: {$id}");
        
            return response()->json($data);
        
        } catch (\Exception $e) {
            Log::error("Gagal mengambil detail reservasi ID: {$id} - " . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat data'], 500);
        }
    }

    public function buat_rekam_medis(Reservasi $reservasi)
    {
        // (Opsional) cek jika sudah ada rekam medis → bisa redirect ke edit
        return view('dokter.buat_rekam_medis', compact('reservasi'));
    }

    public function simpan_rekam_medis(Request $request, Reservasi $reservasi)
    {
        $data = $request->validate([
            'tinggi_badan'           => 'required|integer|max:250',
            'berat_badan'            => 'required|numeric|max:500',
            'tekanan_darah'          => 'required|string',
            'suhu'                   => 'required|numeric|min:30|max:45',
            'diagnosa'               => 'required|string',
            'saran'                  => 'required|string',
            'rencana_tindak_lanjut'  => 'required|string',
            'catatan_tambahan'       => 'nullable|string',
            'riwayat_alergi'         => 'nullable|string',
            'resep_obat'             => 'nullable|string',
        ], [
            
        'tinggi_badan.required' => 'Tinggi badan wajib diisi.',
        'tinggi_badan.integer'  => 'Tinggi badan harus berupa angka.',
        'tinggi_badan.min'      => 'Tinggi badan minimal 30 cm.',
        'tinggi_badan.max'      => 'Tinggi badan maksimal 250 cm.',
        
        // Pesan custom untuk berat_badan
        'berat_badan.required' => 'Berat badan wajib diisi.',
        'berat_badan.numeric'  => 'Berat badan harus berupa angka.',
        'berat_badan.min'      => 'Berat badan minimal 1 kg.',
        'berat_badan.max'      => 'Berat badan maksimal 500 kg.',
        
        // Pesan custom untuk tekanan_darah
        'tekanan_darah.required' => 'Tekanan darah wajib diisi.',
        'tekanan_darah.string'   => 'Tekanan darah harus berupa teks.',
        'tekanan_darah.max'      => 'Tekanan darah maksimal 15 karakter.',
        
        // Pesan custom untuk suhu
        'suhu.required' => 'Suhu tubuh wajib diisi.',
        'suhu.numeric'  => 'Suhu tubuh harus berupa angka.',
        'suhu.min'      => 'Suhu tubuh minimal 30°C.',
        'suhu.max'      => 'Suhu tubuh maksimal 45°C.',
        
        // Pesan custom untuk diagnosa
        'diagnosa.required' => 'Diagnosa wajib diisi.',
        'diagnosa.string'   => 'Diagnosa harus berupa teks.',
        
        // Pesan custom untuk saran
        'saran.required' => 'Saran wajib diisi.',
        'saran.string'   => 'Saran harus berupa teks.',
        
        // Pesan custom untuk rencana_tindak_lanjut
        'rencana_tindak_lanjut.required' => 'Rencana tindak lanjut wajib diisi.',
        'rencana_tindak_lanjut.string'   => 'Rencana tindak lanjut harus berupa teks.',
        ]);

        // Ambil pasien_id dari reservasi (support dua nama kolom)
        $pasienId = $reservasi->id_pasien;
        if (!$pasienId) {
            return back()->withErrors('Reservasi tidak memiliki pasien yang terkait.');
        }

        // Tanggal pemeriksaan & nomor RM
        $tglObj = $reservasi->tanggal_reservasi
            ? Carbon::parse($reservasi->tanggal_reservasi)
            : now();

        $data['tanggal_pemeriksaan'] = $tglObj->toDateString();
        // ---- KUNCI RELASI KE RESERVASI & PASIEN (SESUAIKAN NAMA KOLOM DI TABELMU) ----
        $data['id_reservasi'] = $reservasi->id;  // ganti ke 'id_reservasi' bila kolommu itu
        $data['id_pasien']    = $pasienId;       // ganti ke 'id_pasien' bila kolommu itu

        $rekam = rekam_medis::firstOrNew(['id_reservasi' => $reservasi->id]);

        DB::transaction(function () use (&$rekam, $data, $tglObj, $pasienId, $reservasi) {
        if (!$rekam->exists || empty($rekam->nomor_rekam_medis)) {

            // KUNCI semua RM milik pasien ini supaya urutan aman saat concurrency
            // (memang lebih berat dari COUNT(*), tapi aman; jumlah per pasien biasanya kecil)
            $existingRows = rekam_medis::where('id_pasien', $pasienId)
                ->lockForUpdate()
                ->select('id')
                ->get();

            $seqKe = $existingRows->count() + 1;                 // urutan RM ke-berapa untuk pasien ini
            $SS    = str_pad($seqKe, 2, '0', STR_PAD_LEFT);      // 2 digit
            $PPPP  = str_pad($pasienId, 4, '0', STR_PAD_LEFT);   // 4 digit

            $tglCompact = $tglObj->format('Ymd'); // contoh: 20251113
            $rekam->nomor_rekam_medis = "RM-{$tglCompact}-{$SS}-{$PPPP}";
        }

        // isi & simpan
        $rekam->fill($data);
        $rekam->save();
    });


        // Simpan (1 reservasi = 1 rekam medis)
        rekam_medis::updateOrCreate(
            ['id_reservasi' => $reservasi->id],  // ganti ke 'id_reservasi' jika pakai itu
            $data
        );

        // Tandai pasien selesai
        $reservasi->update(['status' => 'selesai']);

        // Kembali ke daftar pasien
        return redirect()
            ->route('dokter.daftar_antrian')
            ->with('success', 'Rekam medis tersimpan dan pasien ditandai selesai.');
    }

    public function riwayat_rekam_medis(Request $request)
    {
        $user = Auth::user();
        $pasienAktif = null;

        if ($request->filled('pasien_id')) {
            $pasienAktif = data_pasien::find($request->input('pasien_id'));
        }

        if (!$pasienAktif) {
            $pasienAktif = $this->getPasienAktif();
        }

        if (!$pasienAktif) {
            return view('dokter.riwayat_rekam_medis', [
                'riwayatRekamMedis' => collect(),
                'rekamMedisAktif'   => null,
                'pasienData'        => null,
                'dokterData'        => null,
                'pasienAktif'       => null,
            ])->with('warning', 'Belum ada pasien aktif yang dapat ditampilkan.');
        }

        // Ambil semua rekam medis untuk pasien ini
        $riwayatRekamMedis = rekam_medis::query()
            ->select('rekam_medis.*')
            ->join('reservasis', 'rekam_medis.id_reservasi', '=', 'reservasis.id')
            ->where('reservasis.id_pasien', $pasienAktif->id)
            ->where('reservasis.status', 'selesai')
            ->orderBy('rekam_medis.tanggal_pemeriksaan', 'desc')
            ->get();

        // Jika ada parameter ID, ambil rekam medis spesifik
        // Jika tidak, ambil yang pertama/terbaru
        $selectedId = $request->get('id');
        
        if ($selectedId) {
            $rekamMedisAktif = rekam_medis::with(['reservasi.data_pasien'])
                ->whereHas('reservasi', function($q) use ($pasienAktif) {
                    $q->where('id_pasien', $pasienAktif->id);
                })
                ->find($selectedId);
        } else {
            $rekamMedisAktif = $riwayatRekamMedis->first();
        }

        // Data pasien dan dokter
        $pasienData = null;
        $dokterData = null;

        if ($rekamMedisAktif) {
            $pasienData = $rekamMedisAktif->reservasi->data_pasien;
            
            // Ambil data dokter dari rekam medis atau dari user dokter yang sedang login
            // Sesuaikan dengan struktur database Anda
            $dokterData = data_dokter::first(); // Untuk sementara ambil dokter pertama
            
            // Atau jika ada relasi dokter di reservasi:
            // $dokterData = $rekamMedisAktif->reservasi->dokter;
        }

        return view('dokter.riwayat_rekam_medis', compact(
            'riwayatRekamMedis',
            'rekamMedisAktif',
            'pasienData',
            'dokterData',
            'pasienAktif'
        ));
    }

    /**
     * Pastikan rekam medis memiliki nomor sebelum form diisi.
     * Menghasilkan nomor baru jika belum ada.
     */
    private function ensureNomorRekamMedis(Reservasi $reservasi): ?string
    {
        $pasienId = $reservasi->id_pasien;

        if (!$pasienId) {
            return null;
        }

        $rekam = rekam_medis::firstOrNew(['id_reservasi' => $reservasi->id]);

        if (!$rekam->exists) {
            $rekam->id_pasien            = $pasienId;
            $rekam->tanggal_pemeriksaan  = $reservasi->tanggal_reservasi
                ? Carbon::parse($reservasi->tanggal_reservasi)->toDateString()
                : now()->toDateString();
        }

        if (!empty($rekam->nomor_rekam_medis)) {
            return $rekam->nomor_rekam_medis;
        }

        DB::transaction(function () use (&$rekam, $reservasi, $pasienId) {
            $tglObj = $reservasi->tanggal_reservasi
                ? Carbon::parse($reservasi->tanggal_reservasi)
                : now();

            $existingCount = rekam_medis::where('id_pasien', $pasienId)
                ->whereNotNull('nomor_rekam_medis')
                ->lockForUpdate()
                ->count();

            $seqKe = $existingCount + 1;
            $SS    = str_pad($seqKe, 2, '0', STR_PAD_LEFT);
            $PPPP  = str_pad($pasienId, 4, '0', STR_PAD_LEFT);
            $tglCompact = $tglObj->format('Ymd');

            $rekam->nomor_rekam_medis = "RM-{$tglCompact}-{$SS}-{$PPPP}";
            $rekam->save();
        });

        return $rekam->nomor_rekam_medis;
    }

    /**
     * Helper: Ambil pasien aktif dari session
     */
    private function getPasienAktif(): ?data_pasien
    {
        $user = Auth::user();

        // 1. Coba dari session
        $pasienAktifId = session('pasien_aktif_id');
        
        if ($pasienAktifId) {
            $pasien = data_pasien::where('id', $pasienAktifId)
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
