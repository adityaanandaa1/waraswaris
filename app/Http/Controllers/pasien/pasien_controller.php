<?php

namespace App\Http\Controllers\pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\data_pasien;
use App\Models\antrian;
use App\Models\jadwal_praktik;
use App\Models\reservasi;
use Carbon\Carbon;

class pasien_controller extends Controller
{
    //tampilkan dashboard pasien
    public function dashboard()
{
    $user = Auth::user();
     if (!$user->pasiens()->exists()) {
        return redirect()->route('pasien.tambah_biodata');
    }

    $pasiens = $user->pasiens()->get();
    $pasien_aktif = $this->get_pasien_aktif();
    
    // Ambil data untuk tab reservasi
    $tanggal_dipilih = today();
    $nama_hari = $this->get_nama_hari($tanggal_dipilih);
    $hari_enum = strtolower($nama_hari);
    
    // Ambil jadwal spesifik tanggal, fallback ke jadwal terbaru untuk hari yang sama
    $jadwal = jadwal_praktik::whereDate('tanggal_jadwal_praktik', $tanggal_dipilih->toDateString())
        ->first();

    if (!$jadwal) {
        $jadwal = jadwal_praktik::where('hari', $hari_enum)
            ->latest('tanggal_jadwal_praktik')
            ->first();
    }

    $klinik_tutup = !$jadwal || !$jadwal->is_active;
    
    // Ambil antrian
    $antrian = Antrian::whereDate('tanggal_antrian', $tanggal_dipilih)->first();
    if (!$antrian) {
        $antrian = Antrian::create([
            'tanggal_antrian' => $tanggal_dipilih->toDateString(),
            'nomor_sekarang' => 0,
            'total_antrian' => 0,
        ]);
    }
    $antrian->nomor_sekarang = $this->get_nomor_antrian_sekarang($tanggal_dipilih);
    
    // Cek reservasi aktif
    $reservasi_aktif = Reservasi::where('id_pasien', $pasien_aktif->id)
        ->whereDate('tanggal_reservasi', $tanggal_dipilih)
        ->whereIn('status', ['menunggu', 'sedang_dilayani', 'sedang_diperiksa'])
        ->first();
    
    // Cek reminder
    $reminder_aktif = null;
    if ($reservasi_aktif && $antrian) {
        $selisih = $reservasi_aktif->nomor_antrian - $antrian->nomor_sekarang;
        if ($selisih <= 2 && $selisih > 0) {
            $reminder_aktif = (object) [
                'nomor_antrian' => $reservasi_aktif->nomor_antrian,
                'selisih' => $selisih,
            ];
        }
    }

    $reservasis = reservasi::query()
            ->with(['rekam_medis:id,id_reservasi,nomor_rekam_medis,tanggal_pemeriksaan,diagnosa'])
            ->where('id_pasien', $pasien_aktif->id)
            ->where('status', 'selesai')
            ->whereHas('rekam_medis') // Hanya yang punya rekam medis
            ->orderBy('tanggal_reservasi', 'desc')
            ->paginate(10);
    
    return view('pasien.dashboard', compact(
        'pasiens',
        'pasien_aktif',
        'tanggal_dipilih',
        'nama_hari',
        'jadwal',
        'klinik_tutup',
        'antrian',
        'reservasi_aktif',
        'reminder_aktif',
        'reservasis'
    ));
}

private function get_nama_hari($tanggal)
{
    $hariInggris = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $hariIndonesia = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    return str_replace($hariInggris, $hariIndonesia, $tanggal->format('l'));
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

    //tampilkan form untuk pasien baru atau anggota keluarga
    public function tambah_biodata()
    {
        $user = Auth::user();
        $pasiens = $user->pasiens;
            
        // Cek apakah ini pasien pertama (pemilik akun)
        $isPrimary = $pasiens->isEmpty();
            
        return view('pasien.tambah_biodata', compact('isPrimary'));
        //menambah akun sebagai pemilik akun(isprimary)
    }

    //simpan biodata baru
    public function simpan_biodata(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'tanggal_lahir_pasien' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'golongan_darah' => 'nullable|in:A,B,AB,O',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:15',
            'pekerjaan' => 'nullable|string|max:255',
            'catatan_pasien' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'nama_pasien.required' => 'Nama lengkap wajib diisi',
            'tanggal_lahir_pasien.required' => 'Tanggal lahir wajib diisi',
            'tanggal_lahir_pasien.before' => 'Tanggal lahir harus sebelum hari ini',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid',
            'golongan_darah.in' => 'Golongan darah tidak valid',
            'alamat.required' => 'Alamat wajib diisi',
            'no_telepon.required' => 'Nomor telepon wajib diisi',
            'foto.max' => 'Ukuran foto maksimal 2MB',
        ]);

        $user = Auth::user();
            
        // Cek apakah ini pasien pertama
        $isPrimary = $user->pasiens()->count() === 0;

        $foto_path = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            
            // Generate nama file unik
            $filename = 'pasien-' . $user->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            
            // Buat folder jika belum ada
            $upload_path = public_path('uploads/pasien');
            if (!file_exists($upload_path)) {
                mkdir($upload_path, 0755, true);
            }
            
            // Pindahkan file
            $file->move($upload_path, $filename);
            
            // Simpan relative path
            $foto_path = 'uploads/pasien/' . $filename;
        }

        // Buat data pasien baru
        $pasien = data_pasien::create([
            'id_akun' => $user->id,
            'nama_pasien' => $validated['nama_pasien'],
            'tanggal_lahir_pasien' => $validated['tanggal_lahir_pasien'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'golongan_darah' => $validated['golongan_darah'],
            'alamat' => $validated['alamat'],
            'no_telepon' => $validated['no_telepon'],
            'pekerjaan' => $validated['pekerjaan'],
            'catatan_pasien' => $validated['catatan_pasien'],
            'is_primary' => $isPrimary, // True jika pasien pertama
            'foto_path' => $foto_path,
        ]);

        // Set sebagai pasien aktif di session
        session(['pasien_aktif_id' => $pasien->id]);

        $message = $isPrimary 
            ? 'Biodata berhasil disimpan! Selamat datang di WarasWaris.' 
            : 'Anggota keluarga berhasil ditambahkan!';

        return redirect()->route('pasien.dashboard')
            ->with('success', $message);
    }


    //edit untuk menampilkan biodata pasien
    public function edit_biodata($id)
    {
        $user=Auth::user();
        
        $pasien = data_pasien::where('id', $id)
            ->where('id_akun', $user->id)
            ->firstOrFail();
        
        return view('pasien.edit_biodata', compact('pasien'));
    }

    // update biodata pasien mengirim ke db
    public function update_biodata(Request $request, $id)
    {
        $user = Auth::user();

        //validasi kepemilikan
        $pasien = data_pasien::where('id', $id)
                ->where('id_akun', $user->id)
                ->firstOrFail();

        // Validasi input
        $validated = $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'tanggal_lahir_pasien' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'golongan_darah' => 'nullable|in:A,B,AB,O',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:15',
            'pekerjaan' => 'nullable|string|max:255',
            'catatan_pasien' => 'nullable|string',
            'foto_cropped' => 'nullable|string',
            'remove_foto' => 'nullable|string',
        ]);

        // Handle foto cropped (PRIORITAS)
        if ($request->filled('foto_cropped')) {
            try {
                $imageData = $request->foto_cropped;
                
                // Decode base64
                if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                    $type = strtolower($type[1]);
                    
                    if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
                        throw new \Exception('Format tidak valid');
                    }
                    
                    $imageData = base64_decode($imageData);
                    
                    if ($imageData === false) {
                        throw new \Exception('Decode gagal');
                    }
                } else {
                    throw new \Exception('Data tidak valid');
                }
                
                // Hapus foto lama jika ada
                if ($pasien->foto_path) {
                    $oldPath = public_path($pasien->foto_path);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                
                // Tentukan path folder
                $folderPath = public_path('pasien_photos');
                
                // Buat folder jika belum ada
                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0755, true);
                }
                
                // Nama file unik
                $fileName = 'pasien_' . $pasien->id . '_' . time() . '.jpg';
                $fullPath = $folderPath . '/' . $fileName;
                
                // Simpan file
                file_put_contents($fullPath, $imageData);
                
                // Update path di database
                $pasien->foto_path = 'pasien_photos/' . $fileName;
                
            } catch (\Exception $e) {
                return back()
                    ->withErrors(['error' => 'Upload foto gagal: ' . $e->getMessage()])
                    ->withInput();
            }
        }

        // Handle hapus foto
        if ($request->remove_foto == '1' && empty($request->foto_cropped)) {
            if ($pasien->foto_path) {
                $oldPath = public_path($pasien->foto_path);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
                $pasien->foto_path = null;
            }
        }

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($pasien->foto_path) {
                $old_path = public_path($pasien->foto_path);
                if (file_exists($old_path)) {
                    unlink($old_path);
                }
            }

            $file = $request->file('foto');
            $filename = 'pasien-' . $user->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            
            $upload_path = public_path('uploads/pasien');
            if (!file_exists($upload_path)) {
                mkdir($upload_path, 0755, true);
            }
            
            $file->move($upload_path, $filename);
            $validated['foto_path'] = 'uploads/pasien/' . $filename;
        } elseif ($request->boolean('remove_foto')) {
            // Hapus foto jika user memilih hapus tanpa upload baru
            if ($pasien->foto_path) {
                $old_path = public_path($pasien->foto_path);
                if (file_exists($old_path)) {
                    unlink($old_path);
                }
            }
            $validated['foto_path'] = null;
        }

        // Update data
        $pasien->update($validated);

        return redirect()->route('pasien.dashboard')
            ->with('success', 'Biodata berhasil diperbarui!');
    }

    //hapus anggota keluarga
    public function hapus_biodata($id)
    {
        $user = Auth::user();

        //validasi kepemilikan
        $pasien = data_pasien::where('id', $id)
            ->where('id_akun', $user->id)
            ->firstOrFail();
        
        if ($pasien->is_primary) {
            return back()->withErrors([
                'error' => 'Tidak dapat menghapus data pemilik akun!'
            ]);
        }

        $pasien->delete();

        // Jika pasien yang dihapus adalah pasien aktif, ganti ke pasien utama
        if (session('pasien_aktif_id') == $id) {
            $pasien_utama = $user->pasiens()->where('is_primary', true)->first();
            session(['pasien_aktif_id' => $pasien_utama->id]);
        }

        return redirect()->route('pasien.dashboard')
            ->with('success', 'Anggota keluarga berhasil dihapus!');
    }

    //ganti profile
    public function ganti_profil($id)
    {
        $user = Auth::user();
        
        // Validasi bahwa pasien ini milik user yang login
        $pasien = data_pasien::where('id', $id)
            ->where('id_akun', $user->id)
            ->firstOrFail();

        // Simpan ke session
        session(['pasien_aktif_id' => $pasien->id]);

        return redirect()->route('pasien.dashboard')
            ->with('success', "Beralih ke profil: {$pasien->nama_pasien}");
    }

    //Ambil pasien yang sedang aktif dari session
    //Jika belum ada session, ambil pasien utama (is_primary)
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
        $pasien_utama = $user->pasiens()->where('is_primary', true)->first();
            
        // Simpan ke session untuk selanjutnya
        if ($pasien_utama) {
            session(['pasien_aktif_id' => $pasien_utama->id]);
        }
            
        return $pasien_utama;
    }

    //Tampilkan detail biodata pasien
    public function tampilkan_biodata($id)
    {
        $user = Auth::user();
        
        $pasien = data_pasien::where('id', $id)
            ->where('id_akun', $user->id)
            ->firstOrFail();
        
        return view('pasien.tampilkan_biodata', compact('pasien'));
    }
}
