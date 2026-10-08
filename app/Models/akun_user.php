<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class akun_user extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom-kolom yang boleh diisi (bulk insert/update)
     */
    protected $fillable = [
        'email',
        'password',
        'role', // Tambahan: role pengguna
    ];

    /**
     * Kolom yang disembunyikan saat data dikonversi ke JSON/Array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     * 
     * Mengubah tipe data kolom otomatis
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Otomatis hash password
        ];
    }

    protected $table = 'akun_users';

    public function data_pasiens()
    {
        return $this->pasiens();
    }

    public function pasiens()
    {
        return $this->hasMany(\App\Models\data_pasien::class, 'id_akun', 'id');
    }

    public function primary_pasien() // wali = pasien utama
    {
        return $this->hasOne(data_pasien::class, 'id_akun', 'id')
                    ->where('is_primary', true);
    }

    /**
     * Relasi: User punya 1 Dokter (hasOne)
     * 
     * Contoh: $user->dokter (jika role = dokter)
     */
    public function dokter()
    {
        return $this->hasOne(data_dokter::class, 'id_akun', 'id');
    }

    /**
     * Relasi: User punya 1 Resepsionis (hasOne)
     * 
     * Contoh: $user->resepsionis (jika role = resepsionis)
     */
    public function Resepsionis()
    {
        return $this->hasOne(data_resepsionis::class);
    }
}
