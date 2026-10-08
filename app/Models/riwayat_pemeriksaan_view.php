<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class riwayat_pemeriksaan_view extends Model
{
    protected $table = 'riwayat_pemeriksaan_view';
    public $timestamps = false;   // kebanyakan VIEW tidak butuh
    public $incrementing = false;

    protected $casts = [
        'tanggal_pemeriksaan'=> 'date',
    ];

    public function reservasi() { 
        return $this->belongsTo(Reservasi::class, 'id_reservasi'); 
    }
}
