@extends('layouts.editprofiledokter')

@section('title', 'Edit Biodata Dokter- WarasWaris')

@section('content')

@php
    $tanggalLahirValue = old('tanggal_lahir_dokter');
    if ($tanggalLahirValue === null && $dokter->tanggal_lahir_dokter) {
        try {
            $tanggalLahirValue = \Carbon\Carbon::parse($dokter->tanggal_lahir_dokter)->format('Y-m-d');
        } catch (\Exception $e) {
            $tanggalLahirValue = $dokter->tanggal_lahir_dokter; // fallback tampilkan apa adanya
        }
    }

    $sipPath     = $dokter->sip_path ?? null;
    $sipFileName = $sipPath ? basename($sipPath) : null;
@endphp

<form action="{{ route('dokter.profil.update') }}" 
      method="POST" 
      enctype="multipart/form-data"
      class="space-y-6">
    @csrf
    @method('PUT')

    <div class="editprofile-container">
        <div class="editprofile-header">
            <a href="{{ route('dokter.dashboard') }}">
                <i class="icon-back fa-solid fa-x"></i>
            </a>
            <h1 class="editprofile-title">Biodata Dokter</h1>
        </div>
        
        <div class="editprofile-top">
            <div class="editprofile-left">
                <div class="editprofile-field">
                    <label class="editprofile-label">
                        Nama Lengkap
                    </label>
                    <input type="text" name="nama_dokter" value="{{ old('nama_dokter', $dokter->nama_dokter) }}" required class="editprofile-input">
                </div>
            
                <div class="editprofile-field">
                    <label class="editprofile-label">
                        Tanggal Lahir
                    </label>
                    <input 
                        type="date" 
                        name="tanggal_lahir_dokter" 
                        value="{{ $tanggalLahirValue }}" 
                        max="{{ date('Y-m-d') }}" 
                        class="editprofile-input">
                </div>
            </div>
        
            <div class="w-100 d-flex flex-column align-items-center justify-content-start pt-2">
                @php
                    $fotoUrl = $fotoUrl ?? ($dokter->foto_path ? asset($dokter->foto_path) : null);
                    $initial = strtoupper(mb_substr($dokter->nama_dokter ?? 'D', 0, 1));
                @endphp
                <div class="position-relative mb-3 doctor-photo-shell">
                    <div id="doctorPhotoPreview" class="doctor-photo-circle shadow">
                        <div id="fotoFallback" class="doctor-photo-fallback {{ $fotoUrl ? 'd-none' : '' }}">
                            <span class="doctor-photo-initial">{{ $initial }}</span>
                        </div>
                        <img id="doctorPreviewImage"
                             src="{{ $fotoUrl ?: '' }}"
                             alt="{{ $dokter->nama_dokter }}"
                             class="doctor-photo-img {{ $fotoUrl ? '' : 'd-none' }}">
                    </div>
                    <label for="doctorFotoInput" class="doctor-camera-btn">
                        <svg class="doctor-camera-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </label>
                </div>
                <p class="text-sm fw-semibold text-secondary mb-1">Ubah Foto</p>
                <p class="text-xs text-muted text-center px-4 mb-3">Klik ikon kamera untuk mengganti foto (JPG/PNG, max 2MB)</p>

                <input type="file" name="foto" accept="image/jpeg,image/jpg,image/png" class="d-none" id="doctorFotoInput">
                <input type="hidden" name="remove_foto" id="remove_foto" value="0">
                <input type="hidden" name="foto_cropped" id="doctor-foto-cropped">

                @if($fotoUrl)
                <button type="button" id="removeDoctorPhotoBtn" class="btn btn-link text-danger fw-semibold p-0">
                    Hapus Foto
                </button>
                @endif
            </div>
        </div>

        <div class="editprofile-sip-section">
            <label class="editprofile-label">
                Dokumen SIP
            </label>
            <div id="drop-area" class="editprofile-drop-area">
                <i class="ri-upload-2-line"></i>
                <input type="file" id="fileInput" name="sip_file" accept=".pdf,.jpg,.jpeg,.png" hidden>
                <p id="dropText" class="editprofile-drop-text">
                    {{ $sipFileName ?? 'Drop file here' }}
                </p>
                <p class="editprofile-drop-description"> pdf/jpg/png, maks 2MB</p>
            </div>
            @if($sipPath)
                <p class="mt-2 text-sm text-gray-600">
                    File saat ini: 
                    <a href="{{ route('dokter.sip.download') }}" class="text-blue-600 underline" target="_blank" rel="noopener">
                        {{ $sipFileName }}
                    </a>
                </p>
            @endif
        </div>
        
        <div class="editprofile-submit">
            <button type="submit" class="editprofile-button">
                Simpan
            </button>
        </div>
    </div>
</form>

<!-- Modal Crop  -->
<div id="doctorCropModal" class="doctor-crop-modal d-none">
    <div class="doctor-crop-card">
        <div class="p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Crop Foto</h3>
                <button type="button" onclick="closeDoctorCropModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Aspect Ratio Buttons -->
            <div class="flex gap-2 mb-4 flex-wrap">
                <button type="button" class="aspect-btn doctor-aspect-btn active" data-ratio="1" onclick="setDoctorAspect(1, this)">
                    1:1 Bulat
                </button>
                <button type="button" class="aspect-btn doctor-aspect-btn" data-ratio="1.5" onclick="setDoctorAspect(1.5, this)">
                    3:2 Portrait
                </button>
                <button type="button" class="aspect-btn doctor-aspect-btn" data-ratio="0.75" onclick="setDoctorAspect(0.75, this)">
                    4:3 Landscape
                </button>
                <button type="button" class="aspect-btn doctor-aspect-btn" data-ratio="NaN" onclick="setDoctorAspect(NaN, this)">
                    Bebas
                </button>
            </div>

            <!-- Cropper Container -->
            <div class="mb-4" style="max-height: 400px; overflow: hidden;">
                <img id="doctorCropperImage" src="" style="max-width: 100%; display: block;">
            </div>

            <!-- Controls -->
            <div class="flex gap-2 mb-4 flex-wrap">
                <button type="button" onclick="doctorRotateLeft()" class="btn-crop-control">Putar Kiri</button>
                <button type="button" onclick="doctorRotateRight()" class="btn-crop-control">Putar Kanan</button>
                <button type="button" onclick="doctorFlipH()" class="btn-crop-control">Flip</button>
                <button type="button" onclick="doctorZoomIn()" class="btn-crop-control">Zoom In</button>
                <button type="button" onclick="doctorZoomOut()" class="btn-crop-control">Zoom Out</button>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button type="button" onclick="closeDoctorCropModal()" class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 rounded-xl font-semibold">
                    Batal
                </button>
                <button type="button" onclick="applyDoctorCrop()" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold">
                    Terapkan
                </button>
            </div>
        </div>
    </div>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<style>
    
    .doctor-crop-modal {
    position: fixed;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.75);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.doctor-crop-modal.active {
    display: flex !important;
}

.doctor-crop-card {
    background-color: white;
    border-radius: 1rem;
    max-width: 42rem;
    width: 100%;
    max-height: 90vh;
    overflow: auto;
}

/* Utility classes yang digunakan di HTML */
.flex {
    display: flex;
}

.justify-between {
    justify-content: space-between;
}

.items-center {
    align-items: center;
}

.gap-2 {
    gap: 0.5rem;
}

.flex-wrap {
    flex-wrap: wrap;
}

.flex-1 {
    flex: 1 1 0%;
}

.mb-4 {
    margin-bottom: 1rem;
}

.p-6 {
    padding: 1.5rem;
}

.px-4 {
    padding-left: 1rem;
    padding-right: 1rem;
}

.py-3 {
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
}

.text-xl {
    font-size: 1.25rem;
    line-height: 1.75rem;
}

.text-gray-800 {
    color: rgb(31, 41, 55);
}

.text-gray-500 {
    color: rgb(107, 114, 128);
}

.text-white {
    color: white;
}

.font-bold {
    font-weight: 700;
}

.font-semibold {
    font-weight: 600;
}

.w-6 {
    width: 1.5rem;
}

.h-6 {
    height: 1.5rem;
}

.bg-gray-200 {
    background-color: rgb(229, 231, 235);
}

.bg-gray-300 {
    background-color: rgb(209, 213, 219);
}

.bg-blue-600 {
    background-color: rgb(37, 99, 235);
}

.bg-blue-700 {
    background-color: rgb(29, 78, 216);
}

.rounded-xl {
    border-radius: 0.75rem;
}

.hover\:text-gray-700:hover {
    color: rgb(55, 65, 81);
}

.hover\:bg-gray-300:hover {
    background-color: rgb(209, 213, 219);
}

.hover\:bg-blue-700:hover {
    background-color: rgb(29, 78, 216);
}

/* Aspect Ratio Buttons */
.aspect-btn {
    padding: 0.5rem 1rem;
    background-color: rgb(243, 244, 246);
    border: 2px solid transparent;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 0.8125rem;
    font-weight: 500;
}

.aspect-btn:hover {
    background-color: rgb(229, 231, 235);
}

.aspect-btn.active {
    background-color: rgb(59, 130, 246);
    color: white;
    border-color: rgb(37, 99, 235);
}

/* Crop Control Buttons */
.btn-crop-control {
    padding: 0.5rem 0.75rem;
    background-color: rgb(243, 244, 246);
    border-radius: 0.5rem;
    font-size: 0.8125rem;
    cursor: pointer;
    transition: all 0.3s;
    border: none;
}

.btn-crop-control:hover {
    background-color: rgb(229, 231, 235);
}

    .doctor-photo-shell { width: 160px; height: 160px; }
    .doctor-photo-circle {
        width: 160px;
        height: 160px;
        border-radius: 50%;
        overflow: hidden;
        position: relative;
        background: #f8f9fa;
    }
    .doctor-photo-img { width: 100%; height: 100%; object-fit: cover; }
    .doctor-photo-fallback {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .doctor-photo-initial { color: #fff; font-size: 48px; font-weight: 700; }
    .doctor-camera-btn {
        position: absolute;
        bottom: 6px;
        right: 6px;
        width: 46px;
        height: 46px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 50%;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .doctor-camera-btn:hover { background: #eef2ff; }
    .doctor-camera-icon { width: 24px; height: 24px; color: #2563eb; }
</style>

<script>
const dropArea = document.getElementById("drop-area");
const fileInput = document.getElementById("fileInput");
const dropText = document.getElementById("dropText");

dropArea.addEventListener("click", () => fileInput.click());

// Saat drag di atas
dropArea.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropArea.classList.add("dragover");
});

// Saat drag keluar
dropArea.addEventListener("dragleave", () => {
    dropArea.classList.remove("dragover");
});

// Saat file di-drop
dropArea.addEventListener("drop", (e) => {
    e.preventDefault();
    dropArea.classList.remove("dragover");

    const files = e.dataTransfer.files;
    fileInput.files = files; // Masukkan ke input file

    dropText.textContent = files[0].name; // Tampilkan nama file
});

fileInput.addEventListener("change", function () {
    if (this.files.length > 0) {
        dropText.textContent = this.files[0].name;
    }
});

// Preview foto dokter (mirip edit biodata pasien)
const doctorFotoInput = document.getElementById('doctorFotoInput');
const doctorPreviewImage = document.getElementById('doctorPreviewImage');
const doctorFallback = document.getElementById('fotoFallback');
const doctorRemoveInput = document.getElementById('remove_foto');
const removeDoctorPhotoBtn = document.getElementById('removeDoctorPhotoBtn');
let doctorCropper = null;

if (doctorFotoInput) {
    doctorFotoInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (!file) return;

        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran foto maksimal 2MB!');
            event.target.value = '';
            return;
        }

        if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
            alert('Format foto harus JPG, JPEG, atau PNG!');
            event.target.value = '';
            return;
        }

        openDoctorCropModal(file);
    });
}

if (removeDoctorPhotoBtn) {
    removeDoctorPhotoBtn.addEventListener('click', function () {
        if (doctorFotoInput) doctorFotoInput.value = '';
        doctorPreviewImage.src = '';
        doctorPreviewImage.classList.add('d-none');
        if (doctorFallback) doctorFallback.classList.remove('d-none');
        if (doctorRemoveInput) doctorRemoveInput.value = '1';
        removeDoctorPhotoBtn.classList.add('hidden');
    });
}

// Cropper helpers
function openDoctorCropModal(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById('doctorCropperImage');
        img.src = e.target.result;
        const modal = document.getElementById('doctorCropModal');
        modal.classList.remove('d-none');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';

        setTimeout(() => {
            if (doctorCropper) doctorCropper.destroy();
            doctorCropper = new Cropper(img, {
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

function closeDoctorCropModal() {
    const modal = document.getElementById('doctorCropModal');
    modal.classList.remove('active');
    modal.classList.add('d-none');
    document.body.style.overflow = '';
    if (doctorCropper) {
        doctorCropper.destroy();
        doctorCropper = null;
    }
    if (doctorFotoInput) doctorFotoInput.value = '';
}

function setDoctorAspect(ratio, btn) {
    document.querySelectorAll('.doctor-aspect-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    if (doctorCropper) doctorCropper.setAspectRatio(ratio);
}

function doctorRotateLeft() { if (doctorCropper) doctorCropper.rotate(-45); }
function doctorRotateRight() { if (doctorCropper) doctorCropper.rotate(45); }
function doctorFlipH() {
    if (doctorCropper) {
        const scaleX = doctorCropper.getData().scaleX || 1;
        doctorCropper.scaleX(-scaleX);
    }
}
function doctorZoomIn() { if (doctorCropper) doctorCropper.zoom(0.1); }
function doctorZoomOut() { if (doctorCropper) doctorCropper.zoom(-0.1); }

function applyDoctorCrop() {
    if (!doctorCropper) return;

    doctorCropper.getCroppedCanvas({
        width: 400,
        height: 400,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
    }).toBlob(function(blob) {
        const reader = new FileReader();
        reader.onloadend = function() {
            document.getElementById('doctor-foto-cropped').value = reader.result;
            if (doctorRemoveInput) doctorRemoveInput.value = '0';

            const url = URL.createObjectURL(blob);
            doctorPreviewImage.src = url;
            doctorPreviewImage.classList.remove('d-none');
            if (doctorFallback) doctorFallback.classList.add('d-none');
            if (removeDoctorPhotoBtn) removeDoctorPhotoBtn.classList.remove('hidden');

            closeDoctorCropModal();
        };
        reader.readAsDataURL(blob);
    }, 'image/jpeg', 0.9);
}
</script>
