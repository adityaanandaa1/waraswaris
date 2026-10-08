<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class data_pasien extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database (opsional, Laravel auto-detect)
     * Karena nama model = Pasien, Laravel cari tabel "pasiens"
     */
    protected $table = 'data_pasiens';

    /**
     * Kolom yang boleh diisi secara massal
     */
    protected $fillable = [
        'id_akun',
        'nama_pasien',
        'tanggal_lahir_pasien',
        'jenis_kelamin',
        'golongan_darah',
        'alamat',
        'no_telepon',
        'pekerjaan',
        'catatan_pasien',
        'is_primary',
        'foto_path',
    ];

    /**
     * Cast tipe data otomatis
     */
    protected $casts = [
        'tanggal_lahir_pasien' => 'date', // Otomatis jadi Carbon object
        'is_primary' => 'boolean', // Otomatis jadi true/false
    ];

     public function getFotoUrlAttribute()
    {
        if ($this->foto_path && file_exists(public_path($this->foto_path))) {
            return asset($this->foto_path);
        }
        return null; // Return null jika tidak ada foto
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\akun_user::class, 'id_akun');
    }

    public function primary_pasien() // wali = pasien utama
    {
        return $this->hasOne(self::class, 'id_akun', 'id_akun')
                    ->where('is_primary', true);
    }


    /**
     * Relasi: Pasien punya banyak Reservasi (hasMany)
     * 
     * Contoh: $pasien->reservasis (semua reservasi pasien ini)
     */
    public function reservasis()
    {
        return $this->hasMany(reservasi::class);
    }

    public function reservasi_terbaru()
    {
        return $this->hasOne(\App\Models\reservasi::class, 'id_pasien', 'id')
            ->where('status', 'selesai')
            ->latestOfMany('tanggal_reservasi'); 
    }

    public function reservasi_aktif()
    {
        return $this->hasOne(\App\Models\reservasi::class, 'id_pasien', 'id')
            ->whereIn('status', ['menunggu','sedang_diperiksa'])
            ->latestOfMany('tanggal_reservasi');
    }

    /**
     * Relasi: Pasien punya banyak Rekam Medis (hasMany)
     * 
     * Contoh: $pasien->rekamMedis (semua riwayat pemeriksaan)
     */
    public function Rekam_medis()
    {
        return $this->hasMany(rekam_medis::class);
    }

    public function riwayat_pemeriksaan()
    {
        return $this->reservasis()
            ->with('rekam_medis')
            ->where('status', 'selesai')
            ->whereHas('rekam_medis')
            ->orderBy('tanggal_reservasi', 'desc');
    }
}
