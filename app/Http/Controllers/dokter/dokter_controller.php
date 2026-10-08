<?php

namespace App\Http\Controllers\dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\data_dokter;
use App\Models\jadwal_praktik;
use App\Models\reservasi;
use App\Models\antrian;
use App\Models\data_pasien;
use App\Models\rekam_medis;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class dokter_controller extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $dokter = $user->data;

        // tanggal statistik selalu hari ini
        $hari_ini = Carbon::today('Asia/Jakarta');
        $tanggal_statistik = $hari_ini->toDateString();
        // tanggal yang dipilih hanya untuk melihat jadwal praktik
        $tanggal_dipilih = $request->filled('tanggal')
            ? Carbon::parse($request->input('tanggal'), 'Asia/Jakarta')->startOfDay()
            : $hari_ini->copy();

        $hari_ini->locale('id');
        $tanggal_dipilih->locale('id');

        $nama_hari = $this->get_nama_hari($tanggal_dipilih);
        $hari_enum = strtolower($nama_hari);

        $jadwal = jadwal_praktik::firstOrNew([
            'hari' => $hari_enum,
            'tanggal_jadwal_praktik' => $tanggal_dipilih->toDateString(),
        ]);

        if (!$jadwal->exists) {
            $jadwal->fill([
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '21:00:00',
                'is_active' => true,
            ])->save();
        }

        $antrian = antrian::where('tanggal_antrian', $tanggal_statistik)->first();
        if (!$antrian && $hari_ini->isToday()) {
            $antrian = antrian::create([
                'tanggal_antrian' => $tanggal_statistik,
                'nomor_sekarang' => 0,
                'total_antrian' => 0,
            ]);
        }

        $nomor_sekarang = $this->get_nomor_antrian_sekarang($hari_ini);
        if ($antrian) {
            $antrian->nomor_sekarang = $nomor_sekarang;
        } else {
            $antrian = (object) [
                'nomor_sekarang' => $nomor_sekarang,
                'total_antrian' => reservasi::where('tanggal_reservasi', $tanggal_statistik)
                    ->whereIn('status', ['menunggu', 'sedang_dilayani'])
                    ->count(),
            ];
        }

        $total_reservasi = reservasi::where('tanggal_reservasi', $tanggal_statistik)
            ->whereIn('status', ['menunggu', 'sedang_dilayani', 'selesai'])
            ->count();
        $pasien_terlayani = reservasi::where('tanggal_reservasi', $tanggal_statistik)
            ->where('status', 'selesai')
            ->count();
        $pasien_batal = reservasi::where('tanggal_reservasi', $tanggal_statistik)
            ->where('status', 'batal')
            ->count();

        $fotoUrl = $this->getDokterFotoUrl($dokter);

        $reservasis = reservasi::where('tanggal_reservasi', $tanggal_statistik)
            ->whereIn('status', ['menunggu', 'sedang_dilayani'])
            ->with('data_pasien')
            ->orderBy('nomor_antrian')
            ->get();

        return view('dokter.dashboard', compact(
            'user',
            'dokter',
            'jadwal',
            'antrian',
            'reservasis',
            'total_reservasi',
            'pasien_terlayani',
            'pasien_batal',
            'hari_ini',
            'nama_hari',
            'tanggal_dipilih',
            'fotoUrl'
        ));
    }
    

    public function update_jadwal(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'status' => 'required|in:buka,libur',
            'jam_mulai' => 'nullable|required_if:status,buka|date_format:H:i',
            'jam_selesai' => 'nullable|required_if:status,buka|date_format:H:i',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $tanggal = Carbon::parse($validated['tanggal'], 'Asia/Jakarta')->startOfDay();
            $tanggal->locale('id');
            $nama_hari = $this->get_nama_hari($tanggal);
            $hari_enum = strtolower($nama_hari);

            $jadwal = jadwal_praktik::updateOrCreate(
                [
                    'hari' => $hari_enum,
                    'tanggal_jadwal_praktik' => $tanggal->toDateString(),
                ],
                [
                    'is_active' => $validated['status'] === 'buka',
                    'jam_mulai' => $validated['status'] === 'buka' ? $validated['jam_mulai'] : null,
                    'jam_selesai' => $validated['status'] === 'buka' ? $validated['jam_selesai'] : null,
                ]
            );

            $message = $validated['status'] === 'libur'
                ? "Jadwal {$nama_hari} diset LIBUR" . ($validated['catatan'] ? ". Catatan: {$validated['catatan']}" : '-')
                : "Jadwal {$nama_hari} diperbarui: {$validated['jam_mulai']} - {$validated['jam_selesai']}";

            DB::commit();

            return redirect()
                ->route('dokter.dashboard', ['tanggal' => $tanggal->toDateString()])
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Gagal memperbarui jadwal']);
        }
    }

    public function panggil_antrian()
    {
        $antrian = antrian::where('tanggal_antrian', today())->first();

        if(!$antrian) {
            return back()->withErrors(['error' => 'Tidak ada antrian']);
        }

        DB::beginTransaction();

        try{
            //increment nomor sekarang
            $nomor_baru = $antrian->nomor_sekarang + 1;

            //cek apakah ada reservasi dengan nomor tersebut
            $reservasi = reservasi::where('tanggal_reservasi', today())
                ->where('nomor_antrian', $nomor_baru)
                ->where('status', 'menunggu')
                ->first();

            if ($reservasi) {
                // Update status reservasi jadi sedang diperiksa
                $reservasi->update(['status' => 'sedang_diperiksa']);
            }

            $antrian->update(['nomor_sekarang' => $nomor_baru]);

            DB::commit();

            return back()->with('succes', "Nomor Antrian #{$nomor_baru} dipanggil");
        
        } catch(\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Gagal memanggil antrian']);
        }
    }

    public function simpan_rekam_medis(Request $request, $id_reservasi)
    {
         $validated = $request->validate([
            'tinggi_badan' => 'nullable|numeric|min:0|max:300',
            'berat_badan' => 'nullable|numeric|min:0|max:500',
            'tekanan_darah' => 'nullable|string',
            'suhu' => 'nullable|numeric',
            'diagnosa' => 'required|string',
            'saran' => 'nullable|string',
            'rencana_tindak_lanjut' => 'nullable|string',
            'catatan_tambahan' => 'nullable|string',
            'riwayat_alergi' => 'nullable|string',
            'resep_obat' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try{
            $reservasi = reservasi::findOrFail($id_reservasi);
            $pasien_aktif = $this->get_pasien_aktif();

            //generate nomor rekam medis
            $tanggal = today()->format('Ymd');
            $id_pasien   = str_pad($pasien_aktif->id, 4, '0', STR_PAD_LEFT);
            $nomor_rekam_medis = "RM-{$tanggal}-{$id_pasien}";

             // Simpan atau update rekam medis
            $rekam_medis = rekam_medis::updateOrCreate(
                ['id_reservasi' => $id_reservasi],
                [
                    'nomor_rekam_medis' => $nomor_rekam_medis,
                    'id_pasien' => $reservasi->id_pasien,
                    'tanggal_pemeriksaan' => today(),
                    'tinggi_badan' => $validated['tinggi_badan'],
                    'berat_badan' => $validated['berat_badan'],
                    'tekanan_darah' => $validated['tekanan_darah'],
                    'suhu' => $validated['suhu'],
                    'diagnosa' => $validated['diagnosa'],
                    'saran' => $validated['saran'],
                    'rencana_tindak_lanjut' => $validated['rencana_tindak_lanjut'],
                    'catatan_tambahan' => $validated['catatan_tambahan'],
                    'riwayat_alergi' => $validated['riwayat_alergi'],
                    'resep_obat' => $validated['resep_obat'],
                ]
            );

            // Update status reservasi jadi selesai
            $reservasi->update(['status' => 'selesai']);
            
            DB::commit();
            
            return redirect()->route('dokter.dashboard')
                ->with('success', 'Rekam medis berhasil disimpan');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['error' => 'Gagal menyimpan rekam medis'])->withInput();
        }
    }

        private function get_nomor_antrian_sekarang(Carbon $tanggal): int
    {
        $sedang_dilayani = reservasi::whereDate('tanggal_reservasi', $tanggal)
            ->whereIn('status', ['sedang_dilayani', 'sedang_diperiksa'])
            ->orderByDesc('updated_at')
            ->value('nomor_antrian');

        if ($sedang_dilayani) {
            return (int) $sedang_dilayani;
        }

        $terakhir_selesai = reservasi::whereDate('tanggal_reservasi', $tanggal)
            ->where('status', 'selesai')
            ->max('nomor_antrian');

        return (int) ($terakhir_selesai ?? 0);
    }

    // Tampilkan form edit profil
    public function edit_profil()
    {
        $user   = Auth::user();
        $dokter = $user->dokter; // relasi user -> dokter

        if (!$dokter) {
            abort(404, 'Data dokter tidak ditemukan.');
        }

        $fotoUrl = $this->getDokterFotoUrl($dokter);

        return view('dokter.edit_biodata_dokter', compact('dokter', 'fotoUrl'));
    }

    // Simpan perubahan profil + SIP
    public function update_profil(Request $request)
    {
        $user   = Auth::user();
        $dokter = $user->dokter;

        if (!$dokter) {
            abort(404, 'Data dokter tidak ditemukan.');
        }

        $validated = $request->validate([
            'nama_dokter'      => 'required|string|max:255',
            'tanggal_lahir_dokter'    => 'nullable|date|before:today',
            'sip_file'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'foto'             => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'foto_cropped'     => 'nullable|string',
        ]);

        // update field teks
        $dokter->nama_dokter   = $validated['nama_dokter'];
        $dokter->tanggal_lahir_dokter = $validated['tanggal_lahir_dokter'] ?? $dokter->tanggal_lahir_dokter;

        // kalau ada file SIP baru
        if ($request->hasFile('sip_file')) {

            // hapus file lama kalau ada
            if ($dokter->sip_path && Storage::disk('public')->exists($dokter->sip_path)) {
                Storage::disk('public')->delete($dokter->sip_path);
            }

            // simpan file baru
            $path = $request->file('sip_file')->store(
                'sip_dokter/' . $dokter->id,
                'public'
            );

            $dokter->sip_path = $path;
        }

        if ($request->filled('foto_cropped')) {
            try {
                $imageData = $request->foto_cropped;

                if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                    $type = strtolower($type[1]);
                    if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
                        throw new \Exception('Format foto tidak valid.');
                    }
                    $imageData = base64_decode($imageData);
                    if ($imageData === false) {
                        throw new \Exception('Gagal decode foto.');
                    }
                } else {
                    throw new \Exception('Data foto tidak valid.');
                }

                $this->hapusFotoDokter($dokter->foto_path);

                $folder = 'dokter_photos/' . $dokter->id;
                $folderPath = public_path($folder);

                if (!is_dir($folderPath)) {
                    mkdir($folderPath, 0755, true);
                }

                $fileName = 'dokter_' . $dokter->id . '_' . time() . '.jpg';
                file_put_contents($folderPath . '/' . $fileName, $imageData);

                $dokter->foto_path = $folder . '/' . $fileName;
            } catch (\Exception $e) {
                return back()
                    ->withErrors(['error' => 'Upload foto gagal: ' . $e->getMessage()])
                    ->withInput();
            }
        } elseif ($request->hasFile('foto')) {
            $this->hapusFotoDokter($dokter->foto_path);

            $folder = 'dokter_photos/' . $dokter->id;
            $folderPath = public_path($folder);
            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0755, true);
            }

            $file = $request->file('foto');
            $fileName = 'dokter_' . $dokter->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            $file->move($folderPath, $fileName);
            $dokter->foto_path = $folder . '/' . $fileName;
        } elseif ($request->boolean('remove_foto')) {
            $this->hapusFotoDokter($dokter->foto_path);
            $dokter->foto_path = null;
        }

        $dokter->save();

        return redirect()
            ->route('dokter.dashboard') // sesuaikan nama route dashboard doktermu
            ->with('success', 'Profil dokter berhasil diperbarui.');
    }

    public function download_sip()
    {
        $user   = Auth::user();
        $dokter = $user->dokter;

        if (!$dokter || !$dokter->sip_path || !Storage::disk('public')->exists($dokter->sip_path)) {
            return back()->withErrors(['error' => 'File SIP belum diupload.']);
        }

        $namaFile = 'SIP-' . str_replace(' ', '_', $dokter->nama_dokter) . '.pdf';

        return Storage::disk('public')->download($dokter->sip_path, $namaFile);
    }


    private function get_pasien_aktif()
    {
        $user = Auth::user();
            
        // Cek session
        $pasien_aktif_id = session('pasien_aktif_id');
            
        if ($pasien_aktif_id) {
            // Ambil dari session
            $pasien = data_pasien::where('id', $pasien_aktif_id)
                ->where('id_akun', $user->id)
                ->first();
                
            if ($pasien) {
                return $pasien;
            }
        }
            
        // Jika tidak ada, ambil pasien utama
        $user = Auth::user()->user; // relasi user() ada di data_dokter
        $pasien_utama = $user
            ? $user->pasiens()->where('is_primary', true)->first()
            : null;
            
        // Simpan ke session untuk selanjutnya
        if ($pasien_utama) {
            session(['pasien_aktif_id' => $pasien_utama->id]);
        }
            
        return $pasien_utama;
    }

    private function getDokterFotoUrl(?data_dokter $dokter): ?string
    {
        if (!$dokter || !$dokter->foto_path) {
            return null;
        }

        $relativePath = ltrim($dokter->foto_path, '/');
        $publicPath = public_path($relativePath);

        if (is_file($publicPath)) {
            return asset($relativePath);
        }

        if (Storage::disk('public')->exists($relativePath)) {
            try {
                $directory = dirname($publicPath);
                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }

                file_put_contents($publicPath, Storage::disk('public')->get($relativePath));

                return asset($relativePath);
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }

    private function hapusFotoDokter(?string $path): void
    {
        if (!$path) {
            return;
        }

        $publicPath = public_path($path);
        if (is_file($publicPath)) {
            @unlink($publicPath);
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function get_nama_hari($tanggal)
    {
        $hari_inggris = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $hari_indonesia = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        return str_replace($hari_inggris, $hari_indonesia, $tanggal->format('l'));
    }
}
