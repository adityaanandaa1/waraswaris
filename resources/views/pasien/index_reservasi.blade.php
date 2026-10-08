@extends('layouts.app')

@section('title', 'Reservasi - WarasWaris')

@section('content')
<div class="min-h-screen bg-gray-50">
    
    <!-- Navbar (sama seperti dashboard) -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-4">
                    <a href="{{ route('pasien.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <h1 class="text-xl font-bold text-blue-600">Reservasi</h1>
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
        
        <!-- Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- LEFT - Info Pasien -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-3">Reservasi untuk:</h3>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 font-bold text-lg">
                                {{ substr($pasien_aktif->nama_pasien, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $pasien_aktif->nama_lengkap }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $pasien_aktif->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}, 
                                {{ $pasien_aktif->tanggal_lahir_pasien->age }} tahun
                            </p>
                        </div>
                    </div>
                    
                    <!-- Switch Profile -->
                    @if($pasiens->count() > 1)
                        <div class="border-t pt-4">
                            <p class="text-xs text-gray-500 mb-2">Ganti profil:</p>
                            <div class="space-y-1">
                                @foreach($pasiens as $pasien)
                                    <form action="{{ route('pasien.ganti_profil', $pasien->id) }}" method="POST">
                                        @csrf
                                        <button 
                                            type="submit"
                                            class="w-full text-left px-3 py-2 rounded-lg transition text-sm
                                                {{ $pasien->id == $pasien_aktif->id 
                                                    ? 'bg-blue-50 text-blue-700 font-medium' 
                                                    : 'hover:bg-gray-50 text-gray-700' 
                                                }}"
                                        >
                                            {{ $pasien->nama_pasien }}
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Link Riwayat -->
                <a 
                    href="{{ route('pasien.riwayat_reservasi') }}"
                    class="block bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900">Riwayat Reservasi</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            </div>

            <!-- RIGHT - Reservasi Form -->
            <div class="lg:col-span-2">
                
                <!-- Pilih Tanggal -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Pilih Tanggal Reservasi</h2>
                    
                    <form action="{{ route('pasien.index_reservasi') }}" method="GET" class="flex gap-3">
                        <input 
                            type="date" 
                            name="tanggal"
                            value="{{ $tanggal_dipilih->format('Y-m-d') }}"
                            min="{{ today()->format('Y-m-d') }}"
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <button 
                            type="submit"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"
                        >
                            Lihat Jadwal
                        </button>
                    </form>

                    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <strong>{{ $nama_hari }}, {{ $tanggal_dipilih->format('d F Y') }}</strong>
                        </p>
                    </div>
                </div>

                <!-- Jadwal Praktik -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Jadwal Praktik</h3>
                    <div class="flex items-center gap-4 p-4 {{ $klinik_tutup ? 'bg-red-50' : 'bg-green-50' }} rounded-lg">
                        <svg class="w-8 h-8 {{ $klinik_tutup ? 'text-red-500' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-900">
                                {{ $klinik_tutup ? 'Klinik Libur' : 'Klinik Buka' }}
                            </p>
                            <p class="text-sm text-gray-600">
                                @if($klinik_tutup)
                                    Libur
                                @else
                                    {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }} WIB
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                @if($klinik_tutup)
                    <!-- Klinik Tutup -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-8 text-center">
                        <svg class="w-16 h-16 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <h3 class="text-xl font-semibold text-red-900 mb-2">Klinik Tutup</h3>
                        <p class="text-red-700">
                            Klinik tidak beroperasi pada hari <strong>{{ $nama_hari }}</strong>.
                            <br>Silakan pilih tanggal lain.
                        </p>
                    </div>
                @else
                    <!-- Antrian Real-Time -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Antrian Saat Ini</h3>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="p-4 bg-blue-50 rounded-lg text-center">
                                <p class="text-sm text-blue-600 mb-1">Nomor Sekarang</p>
                                <p class="text-3xl font-bold text-blue-700">#{{ $antrian->nomor_sekarang }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg text-center">
                                <p class="text-sm text-gray-600 mb-1">Total Antrian</p>
                                <p class="text-3xl font-bold text-gray-700">{{ $antrian->total_antrian }}</p>
                            </div>
                        </div>

                        @if($antrian->total_antrian > 0)
                            <div class="text-center text-sm text-gray-600">
                                Sisa antrian: <strong>{{ max(0, $antrian->total_antrian - $antrian->nomor_sekarang) }}</strong> orang
                            </div>
                        @endif
                    </div>

                    @if($reservasi_aktif)
                        <!-- Sudah Punya Reservasi -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                                        <span class="text-2xl font-bold text-green-700">#{{ $reservasi_aktif->nomor_antrian }}</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-green-900 mb-2">Anda Sudah Memiliki Reservasi</h4>
                                    <p class="text-sm text-green-700 mb-3">
                                        Nomor antrian: <strong>#{{ $reservasi_aktif->nomor_antrian }}</strong>
                                        <br>Status: <span class="font-semibold">{{ ucfirst($reservasi_aktif->status) }}</span>
                                        <br>Keluhan: {{ $reservasi_aktif->keluhan ?? '-' }}
                                    </p>
                                    
                                    @if($reservasi_aktif->status == 'menunggu')
                                        <form action="{{ route('pasien.batalkan_reservasi', $reservasi_aktif->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi?')">
                                            @csrf
                                            <button 
                                                type="submit"
                                                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition text-sm"
                                            >
                                                Batalkan Reservasi
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Form Ambil Nomor -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ambil Nomor Antrian</h3>
                            
                            <form action="{{ route('pasien.buat_reservasi') }}" method="POST">
                                @csrf
                                
                                <input type="hidden" name="tanggal_reservasi" value="{{ $tanggal_dipilih->format('Y-m-d') }}">
                                
                                <!-- Keluhan -->
                                <div class="mb-6">
                                    <label for="keluhan" class="block text-sm font-medium text-gray-700 mb-2">
                                        Keluhan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea 
                                        name="keluhan" 
                                        id="keluhan" 
                                        rows="4"
                                        maxlength="500"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                        placeholder="Contoh: Demam 3 hari, batuk, pilek..."
                                    >{{ old('keluhan') }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">Wajib diisi, maksimal 500 karakter</p>
                                </div>

                                <!-- Info -->
                                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                                    <p class="text-sm text-blue-800">
                                        <strong>Nomor antrian Anda:</strong> #{{ $antrian->total_antrian + 1 }}
                                    </p>
                                </div>

                                <!-- Submit Button -->
                                <button 
                                    type="submit"
                                    class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition shadow-lg hover:shadow-xl"
                                >
                                    Ambil Nomor Antrian
                                </button>
                            </form>
                        </div>
                    @endif
                @endif

            </div>

        </div>
    </div>

</div>
@endsection
