<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class antrian extends Model
{
    use HasFactory;

    protected $table = 'antrians';

    protected $fillable = [
        'id_pasien',
        'id_reservasi',
        'tanggal_antrian',
        'nomor_sekarang',
        'total_antrian',
        'status',
    ];

    protected $casts = [
        'tanggal_antrian' => 'date',
    ];

    // ========== SCOPE ==========

    /**
     * Ambil antrian hari ini
     * 
     * Penggunaan: Antrian::hariIni()->first()
     */
    public function scopeHariIni($query)
    {
        return $query->where('tanggal', today());
    }
}