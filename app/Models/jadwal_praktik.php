<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class jadwal_praktik extends Model
{
    use HasFactory;

    protected $table = 'jadwal_praktiks';

    protected $fillable = [
        'hari',
        'tanggal_jadwal_praktik',
        'jam_mulai',
        'jam_selesai',
        'is_active',
    ];

    /**
     * Cast tipe data
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

     public function getJamMulaiAttribute($value)
    {
        // DB TIME: "09:00:00" -> tampil "09:00"
        return $value ? substr($value, 0, 5) : null;
    }

    public function getJamSelesaiAttribute($value)
    {
        return $value ? substr($value, 0, 5) : null;
    }

    // scope ambil jadwal per tanggal
    public function scopeForDate($q, $date)
    {
        $date = $date instanceof Carbon ? $date->toDateString() : $date;
        return $q->whereDate('tanggal_jadwal_praktik', $date);
    }

    // cepat: jadwal hari ini
    public function scopeToday($q) { return $q->forDate(today()); }

    // Tidak ada relasi khusus karena jadwal adalah data standalone
}