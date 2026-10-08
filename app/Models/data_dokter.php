<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class data_dokter extends Model
{
    use HasFactory;

    protected $table = 'data_dokters';

    protected $fillable = [
        'id_akun',
        'nama_dokter',
        'tanggal_lahir_dokter',
        'alamat',
        'nomor_str',
        'nomor_sip',
        'foto_path',
    ];

    // ========== RELASI ==========

    /**
     * Relasi: Dokter milik 1 User (belongsTo)
     */
    public function Akun_user()
    {
        return $this->belongsTo(akun_user::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\akun_user::class, 'id_akun', 'id');
    }

    public function jadwal_praktik()
    {
        return $this->hasMany(jadwal_praktik::class, 'id_dokter', 'id');
    }
}
