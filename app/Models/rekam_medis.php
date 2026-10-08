<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rekam_medis extends Model
{
    use HasFactory;

    protected $table = 'rekam_medis';

    protected $fillable = [
        'nomor_rekam_medis',
        'id_pasien',
        'id_reservasi',
        'tanggal_pemeriksaan',
        'tinggi_badan',
        'berat_badan',
        'tekanan_darah',
        'suhu',
        'diagnosa',
        'saran',
        'rencana_tindak_lanjut',
        'catatan_tambahan',
        'riwayat_alergi',
        'resep_obat',
    ];

    protected $casts = [
        'tanggal_pemeriksaan' => 'date',
        'tinggi_badan' => 'decimal:2',
        'berat_badan' => 'decimal:2',
        'suhu' => 'decimal:2',
    ];

    // ========== RELASI ==========

    /**
     * Relasi: Rekam Medis milik 1 Pasien
     */
    public function Data_pasien()
    {
        return $this->belongsTo(data_pasien::class, 'id_pasien', 'id');
    }

    /**
     * Relasi: Rekam Medis milik 1 Reservasi
     */
    public function reservasi()
    {
       return $this->belongsTo(reservasi::class, 'id_reservasi', 'id');
    }

    // ========== ACCESSOR (Data yang Pasien Boleh Lihat) ==========

    /**
     * Ambil hanya data yang boleh dilihat pasien
     * 
     * Penggunaan: $rekamMedis->data_publik
     */
    public function getDataPublikAttribute()
    {
        return [
            'nomor_rekam_medis' => $this->nomor_rekam_medis,
            'tanggal_pemeriksaan' => $this->tanggal_pemeriksaan->format('d M Y'),
            'tinggi_badan' => $this->tinggi_badan,
            'berat_badan' => $this->berat_badan,
            'tekanan_darah' => $this->tekanan_darah,
            'suhu' => $this->suhu,
            'diagnosa' => $this->diagnosa,
            'saran' => $this->saran,
            'rencana_tindak_lanjut' => $this->rencana_tindak_lanjut,
            'catatan_tambahan' => $this->catatan_tambahan,
        ];
    }

     public function getDataLengkapAttribute()
    {
        return [
            'nomor_rekam_medis' => $this->nomor_rekam_medis,
            'tanggal_pemeriksaan' => $this->tanggal_pemeriksaan->format('d M Y'),
            'tinggi_badan' => $this->tinggi_badan,
            'berat_badan' => $this->berat_badan,
            'tekanan_darah' => $this->tekanan_darah,
            'suhu' => $this->suhu,
            'alergi' => $this->alergi,
            'riwayat_penyakit' => $this->riwayat_penyakit,
            'diagnosa' => $this->diagnosa,
            'resep_obat' => $this->resep_obat,
            'saran' => $this->saran,
            'rencana_tindak_lanjut' => $this->rencana_tindak_lanjut,
            'catatan_tambahan' => $this->catatan_tambahan,
        ];
    }
}