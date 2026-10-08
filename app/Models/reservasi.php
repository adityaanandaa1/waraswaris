<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\data_pasien;
use App\Models\akun_user;

class reservasi extends Model
{
    use HasFactory;

    protected $table = 'reservasis';

    protected $fillable = [
        'id_pasien',
        'tanggal_reservasi',
        'nomor_antrian',
        'keluhan',
        'status',
    ];

    protected $casts = [
        'tanggal_reservasi' => 'date',
    ];

    // ========== RELASI ==========

    /**
     * Relasi: Reservasi milik 1 Pasien (belongsTo)
     */
    public function data_pasien()
    {
        return $this->belongsTo(data_pasien::class, 'id_pasien', 'id');
    }

    public function user()    
    {
        return $this->belongsTo(akun_user::class, 'id_user', 'id');
    }

    /**
     * Relasi: Reservasi milik 1 Dokter (belongsTo)
     */
    public function data_dokter()
    {
        return $this->belongsTo(data_dokter::class, 'id_dokter', 'id');
    }

    /**
     * Relasi: Reservasi punya 1 Rekam Medis (hasOne)
     * 
     * 1 reservasi = 1 kunjungan = 1 rekam medis
     */
    public function rekam_medis()
    {
        return $this->hasOne(rekam_medis::class, 'id_reservasi', 'id');
    }

    public function getNamaPasienAttribute()
    {
        return optional($this->data_pasien)->nama_pasien
            ?? optional($this->data_pasien)->nama_lengkap;
    }

    /**
     * Scope: Hanya yang sudah selesai
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Scope: Yang punya rekam medis
     */
    public function scopeDenganRekamMedis($query)
    {
        return $query->whereHas('rekam_medis');
    }

    /**
     * Check apakah reservasi punya rekam medis
     */
    public function hasRekamMedis()
    {
        return $this->rekam_medis()->exists();
    }

    // ========== SCOPE (Query Helper) ==========

    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal_reservasi', today());
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
