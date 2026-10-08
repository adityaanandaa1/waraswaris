<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\reservasi;

class Reservasi_Controller_dokter extends Controller
{
    public function mark_periksa(Reservasi $reservasi)
    {
        // Pastikan ∈ enum: menunggu, sedang_dilayani, selesai
        if ($reservasi->status === 'selesai') {
            return back()->withErrors('Reservasi sudah selesai.');
        }

        $reservasi->update(['status' => 'sedang_dilayani']); // <- bukan sedang_diperiksa

        return redirect()
            ->route('resepsionis.buat_rekam_medis', $reservasi)
            ->with('success', 'Nomor antrian sedang dilayani. Silakan isi rekam medis.');
    }
}
