@extends('layouts.app')

@section('title', 'Riwayat Reservasi - WarasWaris')

@section('content')
<div class="min-h-screen bg-gray-50">
    
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-4">
                    <a href="{{ route('pasien.index_reservasi') }}" class="text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <h1 class="text-xl font-bold text-blue-600">Riwayat Reservasi</h1>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-gray-700">{{ Auth::user()->email }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-blue-600 font-bold text-lg">
                        {{ substr($pasien_aktif->nama_pasien, 0, 1) }}
                    </span>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $pasien_aktif->nama_pasien }}</p>
                    <p class="text-sm text-gray-500">Riwayat reservasi dan pemeriksaan</p>
                </div>
            </div>
        </div>

        <!-- List Riwayat -->
        @if($reservasis->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Riwayat</h3>
                <p class="text-gray-600 mb-6">Anda belum pernah melakukan reservasi</p>
                <a 
                    href="{{ route('pasien.index_reservasi') }}"
                    class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"
                >
                    Buat Reservasi Sekarang
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($reservasis as $reservasi)
                    <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            
                            <!-- Info Reservasi -->
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-bold">#{{ $reservasi->nomor_antrian }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            {{ \Carbon\Carbon::parse($reservasi->tanggal_reservasi)->format('d F Y') }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($reservasi->tanggal_reservasi)->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>

                                @if($reservasi->keluhan)
                                    <div class="mb-3">
                                        <p class="text-sm text-gray-500">Keluhan:</p>
                                        <p class="text-gray-700">{{ $reservasi->keluhan }}</p>
                                    </div>
                                @endif

                                <!-- Status Badge -->
                                <div>
                                    @if($reservasi->status == 'selesai')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Selesai
                                        </span>
                                    @elseif($reservasi->status == 'menunggu')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            Menunggu
                                        </span>
                                    @elseif($reservasi->status == 'sedang_diperiksa')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Sedang Diperiksa
                                        </span>
                                    @elseif($reservasi->status == 'batal')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            Dibatalkan
                                        </span>
                                    @elseif($reservasi->status == 'tidak_hadir')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                            </svg>
                                            Tidak Hadir
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Button -->
                            @if($reservasi->status == 'selesai' && $reservasi->rekamMedis)
                                <a 
                                    href="#" 
                                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition text-sm"
                                >
                                    Lihat Hasil
                                </a>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $reservasis->links() }}
            </div>
        @endif

    </div>

</div>
@endsection