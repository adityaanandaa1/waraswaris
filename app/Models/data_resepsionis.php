<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class data_resepsionis extends Model
{
    use HasFactory;

    protected $table = 'data_resepsionis';

    protected $fillable = [
        'id_akun',
        'nama_resepsionis',
    ];

    // ========== RELASI ==========

    /**
     * Relasi: Resepsionis milik 1 User (belongsTo)
     */
    public function Akun_user()
    {
        return $this->belongsTo(akun_user::class);
    }
}