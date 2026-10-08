@extends('layouts.app')

@section('title', 'Edit Biodata - WarasWaris')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">

<div class="min-h-screen bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl relative">
        <a href="{{ route('pasien.dashboard') }}" class="absolute top-6 left-6 w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center text-gray-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </a>

        <!-- Header -->
        <div class="text-center pt-12 pb-8 px-8">
            <h1 class="text-3xl font-bold text-blue-600">
                @if($pasien->is_primary)
                    Edit Biodata Utama
                @else
                    Edit Anggota Keluarga
                @endif
            </h1>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mx-8 mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('pasien.update_biodata', $pasien->id) }}" method="POST" enctype="multipart/form-data" class="px-8 pb-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- LEFT SIDE - Form Fields (2 columns) -->
                <div class="lg:col-span-2 space-y-5">
                    
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap
                        </label>
                        <input 
                            type="text" 
                            name="nama_pasien" 
                            value="{{ old('nama_pasien', $pasien->nama_pasien) }}"
                            placeholder="Isi Nama Lengkap"
                            required
                            class="w-full px-4 py-3 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        >
                    </div>

                    <!-- Nama Wali (hanya untuk anggota keluarga) -->
                    @if(!$pasien->is_primary)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Wali
                        </label>
                        <input 
                            type="text" 
                            value="{{ Auth::user()->pasiens->where('is_primary', true)->first()->nama_pasien ?? 'Pemilik Akun' }}"
                            disabled
                            class="w-full px-4 py-3 bg-gray-100 border-0 rounded-xl text-gray-600"
                        >
                    </div>
                    @endif

                    <!-- Row: Jenis Kelamin & No Telepon -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <!-- Jenis Kelamin -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis Kelamin
                            </label>
                            <div class="relative">
                                <select 
                                    name="jenis_kelamin"
                                    required
                                    class="w-full px-4 py-3 bg-gray-50 border-0 rounded-xl appearance-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                                >
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin', $pasien->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin', $pasien->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- No Telepon -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                No Telepon
                            </label>
                            <input 
                                type="tel" 
                                name="no_telepon" 
                                value="{{ old('no_telepon', $pasien->no_telepon) }}"
                                placeholder="Isi Nomor Telepon"
                                required
                                class="w-full px-4 py-3 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                            >
                        </div>

                    </div>

                    <!-- Row: Tanggal Lahir & Golongan Darah -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <!-- Tanggal Lahir -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Lahir
                            </label>
                            <div class="relative">
                                <input 
                                    type="date" 
                                    name="tanggal_lahir_pasien" 
                                    value="{{ old('tanggal_lahir_pasien', optional($pasien->tanggal_lahir_pasien)->format('Y-m-d')) }}"
                                    max="{{ date('Y-m-d') }}"
                                    required
                                    class="w-full px-4 py-3 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                                >
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                </div>
                            </div>
                        </div>

                        <!-- Golongan Darah -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Golongan Darah
                            </label>
                            <div class="relative">
                                <select 
                                    name="golongan_darah"
                                    class="w-full px-4 py-3 bg-gray-50 border-0 rounded-xl appearance-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                                >
                                    <option value="">Pilih Golongan Darah</option>
                                    <option value="A" {{ old('golongan_darah', $pasien->golongan_darah) == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('golongan_darah', $pasien->golongan_darah) == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="AB" {{ old('golongan_darah', $pasien->golongan_darah) == 'AB' ? 'selected' : '' }}>AB</option>
                                    <option value="O" {{ old('golongan_darah', $pasien->golongan_darah) == 'O' ? 'selected' : '' }}>O</option>
                                </select>
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Row: Pekerjaan & Alamat -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <!-- Pekerjaan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Pekerjaan
                            </label>
                            <input 
                                type="text" 
                                name="pekerjaan" 
                                value="{{ old('pekerjaan', $pasien->pekerjaan) }}"
                                placeholder="Isi Pekerjaan"
                                class="w-full px-4 py-3 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                            >
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat
                            </label>
                            <input 
                                type="text" 
                                name="alamat" 
                                value="{{ old('alamat', $pasien->alamat) }}"
                                placeholder="Isi Alamat Lengkap"
                                required
                                class="w-full px-4 py-3 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                            >
                        </div>

                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan
                        </label>
                        <textarea 
                            name="catatan_pasien" 
                            rows="4"
                            placeholder="Catatan tambahan (opsional)"
                            class="w-full px-4 py-3 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition resize-none"
                        >{{ old('catatan_pasien', $pasien->catatan_pasien) }}</textarea>
                    </div>
                </div>

                <!-- RIGHT SIDE - Photo Display/Upload (GANTI BAGIAN INI) -->
                <div class="lg:col-span-1 flex flex-col items-center justify-start pt-4">
                    
                    <!-- Photo Circle -->
                    <div class="relative mb-4">
                        <div id="photoPreview" class="w-40 h-40 rounded-full overflow-hidden shadow-lg">
                            @if($pasien->foto_path && file_exists(public_path($pasien->foto_path)))
                                <!-- Foto existing -->
                                <img id="previewImage" src="{{ asset($pasien->foto_path) }}" alt="Foto Pasien" class="w-full h-full object-cover">
                            @else
                                <!-- Placeholder gradient dengan inisial -->
                                <div class="w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                    <span id="placeholderInitial" class="text-white text-5xl font-bold">
                                        {{ strtoupper(substr($pasien->nama_pasien, 0, 1)) }}
                                    </span>
                                    <img id="previewImage" src="" alt="Preview" class="w-full h-full object-cover hidden">
                                </div>
                            @endif
                        </div>
                        
                        <!-- Camera Icon (Bottom Right) -->
                        <label for="foto-input" class="absolute bottom-2 right-2 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center cursor-pointer hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </label>
                    </div>

                    <!-- Upload Text -->
                    <p class="text-sm font-medium text-gray-700 mb-1">Ubah Foto</p>
                    <p class="text-xs text-gray-500 text-center px-4 mb-4">
                        Klik ikon kamera untuk mengganti foto (JPG/PNG, max 2MB)
                    </p>

                    <!-- File Input -->
                    <input 
                        type="file" 
                        accept="image/jpeg,image/jpg,image/png"
                        class="hidden"
                        id="foto-input"
                        onchange="openCropModal(event)"
                    >
                    <input type="hidden" name="remove_foto" id="remove_foto" value="0">
                    <!-- Hidden input untuk hasil crop (base64) -->
                    <input type="hidden" name="foto_cropped" id="foto-cropped">
  
                    <!-- Remove Button (if has photo) -->
                    @if($pasien->foto_path)
                    <button 
                        type="button" 
                        id="removePhotoBtn"
                        onclick="removePhoto()"
                        class="text-sm text-red-600 hover:text-red-700 font-medium"
                    >
                        Hapus Foto
                    </button>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center mt-8 w-full">
                <!-- Update Button -->
                <form action="{{ route('pasien.update_biodata', $pasien->id) }}" method="POST">
                    @csrf
                    <button 
                        type="submit"
                        class="px-12 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transition duration-200 text-lg"
                    >
                        Perbarui
                    </button>
                </form>

                <!-- Delete Button (only for non-primary profiles) -->
                @if(!$pasien->is_primary)
                    <form action="{{ route('pasien.hapus_biodata', $pasien->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus biodata ini?')"
                            class="px-12 py-3.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transition duration-200 text-lg"
                        >
                            Hapus
                        </button>
                    </form>
                @else
                <div></div> <!-- Empty div to maintain flex spacing -->
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Modal Crop -->
<div id="cropModal" class="fixed inset-0 bg-black bg-opacity-75 z-[9999] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-auto">
        <div class="p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Crop Foto</h3>
                <button type="button" onclick="closeCropModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Aspect Ratio Buttons -->
            <div class="flex gap-2 mb-4 flex-wrap">
                <button type="button" class="aspect-btn active" data-ratio="1" onclick="setAspectRatio(1, this)">
                    1:1 Bulat
                </button>
                <button type="button" class="aspect-btn" data-ratio="1.5" onclick="setAspectRatio(1.5, this)">
                    3:2 Portrait
                </button>
                <button type="button" class="aspect-btn" data-ratio="0.75" onclick="setAspectRatio(0.75, this)">
                    4:3 Landscape
                </button>
                <button type="button" class="aspect-btn" data-ratio="NaN" onclick="setAspectRatio(NaN, this)">
                    Bebas
                </button>
            </div>

            <!-- Cropper Container -->
            <div class="mb-4" style="max-height: 400px; overflow: hidden;">
                <img id="cropperImage" src="" style="max-width: 100%; display: block;">
            </div>

            <!-- Controls -->
            <div class="flex gap-2 mb-4 flex-wrap">
                <button type="button" onclick="cropperRotateLeft()" class="btn-crop-control">Putar Kiri</button>
                <button type="button" onclick="cropperRotateRight()" class="btn-crop-control">Putar Kanan</button>
                <button type="button" onclick="cropperFlipH()" class="btn-crop-control">Flip</button>
                <button type="button" onclick="cropperZoomIn()" class="btn-crop-control">Zoom In</button>
                <button type="button" onclick="cropperZoomOut()" class="btn-crop-control">Zoom Out</button>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button type="button" onclick="closeCropModal()" class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 rounded-xl font-semibold">
                    Batal
                </button>
                <button type="button" onclick="applyCrop()" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold">
                    Terapkan
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .aspect-btn {
        padding: 8px 16px;
        background: #f3f4f6;
        border: 2px solid transparent;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 13px;
        font-weight: 500;
    }
    .aspect-btn:hover {
        background: #e5e7eb;
    }
    .aspect-btn.active {
        background: #3b82f6;
        color: white;
        border-color: #2563eb;
    }
    .btn-crop-control {
        padding: 8px 12px;
        background: #f3f4f6;
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s;
        border: none;
    }
    .btn-crop-control:hover {
        background: #e5e7eb;
    }
    #cropModal.active {
        display: flex !important;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<!-- Script for image preview -->
<script>
    let cropper = null;
    // Open crop modal
    function openCropModal(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Validasi ukuran
        if (file.size > 2048 * 1024) {
            alert('Ukuran foto maksimal 2MB!');
            event.target.value = '';
            return;
        }

        // Validasi tipe
        if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
            alert('Format foto harus JPG, JPEG, atau PNG!');
            event.target.value = '';
            return;
        }

        // Baca file dan tampilkan di cropper
        const reader = new FileReader();
        reader.onload = function(e) {
            const cropperImage = document.getElementById('cropperImage');
            cropperImage.src = e.target.result;
            
            // Show modal
            const modal = document.getElementById('cropModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Initialize cropper
            setTimeout(() => {
                if (cropper) cropper.destroy();
                
                cropper = new Cropper(cropperImage, {
                    aspectRatio: 1,
                    viewMode: 2,
                    dragMode: 'move',
                    autoCropArea: 0.8,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    responsive: true,
                    guides: true,
                    center: true,
                    highlight: false,
                    minContainerWidth: 300,
                    minContainerHeight: 300,
                });
            }, 100);
        };
        reader.readAsDataURL(file);
    }

    // Close crop modal
    function closeCropModal() {
        const modal = document.getElementById('cropModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
        
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        
        // Reset file input
        document.getElementById('foto-input').value = '';
    }

    // Set aspect ratio
    function setAspectRatio(ratio, btn) {
        document.querySelectorAll('.aspect-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        if (cropper) cropper.setAspectRatio(ratio);
    }

    // Cropper controls
    function cropperRotateLeft() {
        if (cropper) cropper.rotate(-45);
    }

    function cropperRotateRight() {
        if (cropper) cropper.rotate(45);
    }

    function cropperFlipH() {
        if (cropper) {
            const scaleX = cropper.getData().scaleX || 1;
            cropper.scaleX(-scaleX);
        }
    }

    function cropperZoomIn() {
        if (cropper) cropper.zoom(0.1);
    }

    function cropperZoomOut() {
        if (cropper) cropper.zoom(-0.1);
    }

    // Apply crop
    function applyCrop() {
        if (!cropper) return;

        cropper.getCroppedCanvas({
            width: 400,
            height: 400,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        }).toBlob(function(blob) {
            // Convert to base64
            const reader = new FileReader();
            reader.onloadend = function() {
                // Simpan base64 ke hidden input
                document.getElementById('foto-cropped').value = reader.result;
                document.getElementById('remove_foto').value = '0';
                
                // Update preview
                const container = document.getElementById('photoPreview');
                const url = URL.createObjectURL(blob);
                container.innerHTML = `
                    <img id="previewImage" src="${url}" alt="Preview" class="w-full h-full object-cover">
                `;
                
                // Show remove button jika ada
                const removeBtn = document.getElementById('removePhotoBtn');
                if (removeBtn) removeBtn.classList.remove('hidden');
                
                closeCropModal();
                
                // Toast notification
                showToast('Foto berhasil di-crop!');
            };
            reader.readAsDataURL(blob);
        }, 'image/jpeg', 0.9);
    }

    // Remove photo function
    function removePhoto() {
        document.getElementById('foto-input').value = '';
        document.getElementById('foto-cropped').value = '';
        document.getElementById('remove_foto').value = '1';
        
        // Reset ke placeholder
        const container = document.getElementById('photoPreview');
        container.innerHTML = `
            <div class="w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                <span class="text-white text-5xl font-bold">{{ strtoupper(substr($pasien->nama_pasien, 0, 1)) }}</span>
            </div>
        `;
        
        const removeBtn = document.getElementById('removePhotoBtn');
        if (removeBtn) removeBtn.classList.add('hidden');
    }

    // Toast notification
    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-[9999] transform transition-all duration-300';
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';
            setTimeout(() => toast.remove(), 300);
        }, 2000);
    }
</script>
@endsection

