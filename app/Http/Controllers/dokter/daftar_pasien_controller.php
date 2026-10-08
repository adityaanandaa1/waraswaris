<?php

namespace App\Http\Controllers\dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\data_pasien;
use App\Models\akun_user;
use App\Models\reservasi;
use Carbon\Carbon;

class daftar_pasien_controller extends Controller
{
    public function __construct()
    {
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'id');
    }

    public function daftar_pasien(Request $request)
    {
        $search = trim((string) $request->get('q', ''));

        $query = data_pasien::with([
            'reservasi_terbaru' => function ($q) {
                $q->select('reservasis.id', 'reservasis.id_pasien', 'reservasis.tanggal_reservasi', 'reservasis.status');
            },
        ])
        ->select(
            'data_pasiens.id',
            'data_pasiens.id_akun',
            'data_pasiens.nama_pasien',
            'data_pasiens.foto_path',
            'data_pasiens.no_telepon',
            'data_pasiens.jenis_kelamin',
            'data_pasiens.tanggal_lahir_pasien',
            'data_pasiens.golongan_darah',
            'data_pasiens.pekerjaan',
            'data_pasiens.alamat',
            'data_pasiens.catatan_pasien'
        )
        ->selectSub(function ($subQuery) {
            $subQuery->select('wali.nama_pasien')
                ->from('data_pasiens as wali')
                ->whereColumn('wali.id_akun', 'data_pasiens.id_akun')
                ->where('wali.is_primary', true)
                ->limit(1);
        }, 'nama_wali');

        if ($search !== '') {
            $query->where('data_pasiens.nama_pasien', 'like', '%'.$search.'%');
        }

        $pasien_list   = $query->orderBy('data_pasiens.nama_pasien', 'asc')->get();
        $total_pasien  = data_pasien::count();
        return view('dokter.daftar_pasien', [
            'pasien_list'  => $pasien_list,
            'total_pasien' => $total_pasien,
            'search'       => $search,
        ]);
    }

    public function detail_pasien($id)
    {
        try {
            Log::info("Mengakses detail pasien ID: {$id}");

            $pasien = data_pasien::select(
                    'id','id_akun','nama_pasien','foto_path','jenis_kelamin','tanggal_lahir_pasien',
                    'golongan_darah','pekerjaan','alamat','no_telepon','catatan_pasien','is_primary'
                )->findOrFail($id);

            // umur dan tanggal lahir terformat
            $umur = null;
            $tanggalFormatted = '-';
            if ($pasien->tanggal_lahir_pasien) {
                try {
                    $tanggalCarbon  = Carbon::parse($pasien->tanggal_lahir_pasien)->locale('id');
                    $umur = $tanggalCarbon->age;
                    $tanggalFormatted = $tanggalCarbon->isoFormat('D MMMM YYYY');
                } catch (\Exception $e) {
                    Log::warning("Gagal parse TTL pasien {$id}: ".$e->getMessage());
                }
            }

            // wali dari data_pasien (akun yang sama, is_primary = true; fallback pasien pertama)
            $nama_wali = $pasien->user?->primary_pasien?->nama_pasien ?? '-';

            // normalisasi JK
            $jenis_kelamin_pasien = strtolower((string)($pasien->jenis_kelamin ?? ''));
            if ($jenis_kelamin_pasien === 'l' || $jenis_kelamin_pasien === 'laki-laki' || $jenis_kelamin_pasien === 'laki laki') {
                $jenis_kelamin_pasien = 'Laki-laki';
            } elseif ($jenis_kelamin_pasien === 'p' || $jenis_kelamin_pasien === 'perempuan') {
                $jenis_kelamin_pasien = 'Perempuan';
            } else {
                $jenis_kelamin_pasien = '-';
            }

            // response
            $data = [
                'id'             => $pasien->id,
                'nama_pasien'    => $pasien->nama_pasien ?? '-',
                'nama_wali'      => $nama_wali,
                'jenis_kelamin_pasien'  => $jenis_kelamin_pasien,
                'tanggal_lahir_pasien'  => $tanggalFormatted,
                'golongan_darah' => $pasien->golongan_darah ?? 'Tidak diketahui',
                'umur'           => $umur,
                'pekerjaan'      => $pasien->pekerjaan ?? 'Tidak bekerja',
                'alamat'         => $pasien->alamat ?? '-',
                'no_telepon'     => $pasien->no_telepon ?? '-',
                'foto_path'      => $pasien->foto_path ?? null,
                'catatan_pasien' => $pasien->catatan_pasien ?? '-',
            ];

            return response()->json($data);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Data pasien tidak ditemukan'], 404);
        } catch (\Throwable $e) {
            Log::error('detail_pasien error: '.$e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan: '.$e->getMessage()], 500);
        }
    }
}
