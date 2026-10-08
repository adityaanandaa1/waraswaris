@props([
    'name' => 'foto',
    'currentPhoto' => null,
    'aspectRatio' => '1',
    'width' => '400',
    'height' => '400',
    'label' => 'Ubah Foto',
    'hint' => 'Klik untuk crop foto'
])

<div class="photo-crop-wrapper">
    <!-- Hidden Inputs -->
    <input type="hidden" name="{{ $name }}_cropped" id="{{ $name }}_cropped">
    <input type="file" 
           id="{{ $name }}_input" 
           accept="image/jpeg,image/jpg,image/png" 
           class="hidden">

    <!-- Photo Display -->
    <div class="relative mb-4" id="{{ $name }}_display">
        @if($currentPhoto)
            <div class="w-40 h-40 rounded-full overflow-hidden shadow-lg">
                {{-- Adjust path sesuai struktur folder kamu --}}
                <img src="{{ asset($currentPhoto) }}" 
                     alt="Foto" 
                     class="w-full h-full object-cover" 
                     id="{{ $name }}_preview">
            </div>
        @else
            <div class="w-40 h-40 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center shadow-lg">
                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        @endif

        <!-- Camera Icon Button -->
        <button type="button" 
                onclick="document.getElementById('{{ $name }}_input').click()" 
                class="absolute bottom-2 right-2 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-blue-50 transition">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </button>
    </div>

    <p class="text-sm font-medium text-gray-700 mb-1">{{ $label }}</p>
    <p class="text-xs text-gray-500 text-center px-4 mb-4">{{ $hint }}</p>
</div>

<!-- Modal Crop -->
<div id="{{ $name }}_modal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-auto">
        <div class="p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">✂️ Crop Foto</h3>
                <button type="button" 
                        onclick="closeCropModal_{{ $name }}()" 
                        class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Aspect Ratio Buttons -->
            <div class="flex gap-2 mb-4 flex-wrap">
                <button type="button" class="aspect-btn-{{ $name }} active" data-ratio="1" onclick="setAspectRatio_{{ $name }}(1, this)">
                    1:1 Bulat
                </button>
                <button type="button" class="aspect-btn-{{ $name }}" data-ratio="1.5" onclick="setAspectRatio_{{ $name }}(1.5, this)">
                    3:2 Portrait
                </button>
                <button type="button" class="aspect-btn-{{ $name }}" data-ratio="0.75" onclick="setAspectRatio_{{ $name }}(0.75, this)">
                    4:3 Landscape
                </button>
                <button type="button" class="aspect-btn-{{ $name }}" data-ratio="NaN" onclick="setAspectRatio_{{ $name }}(NaN, this)">
                    Bebas
                </button>
            </div>

            <!-- Cropper Image -->
            <div class="mb-4" style="max-height: 400px; overflow: hidden;">
                <img id="{{ $name }}_cropper_img" src="" style="max-width: 100%; display: block;">
            </div>

            <!-- Controls -->
            <div class="flex gap-2 mb-4 flex-wrap">
                <button type="button" onclick="rotateLeft_{{ $name }}()" class="btn-control">↺ Putar Kiri</button>
                <button type="button" onclick="rotateRight_{{ $name }}()" class="btn-control">↻ Putar Kanan</button>
                <button type="button" onclick="flipH_{{ $name }}()" class="btn-control">⇄ Flip</button>
                <button type="button" onclick="zoomIn_{{ $name }}()" class="btn-control">🔍+ Zoom</button>
                <button type="button" onclick="zoomOut_{{ $name }}()" class="btn-control">🔍- Zoom</button>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button type="button" 
                        onclick="closeCropModal_{{ $name }}()" 
                        class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 rounded-xl font-semibold">
                    Batal
                </button>
                <button type="button" 
                        onclick="applyCrop_{{ $name }}()" 
                        class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold">
                    ✓ Terapkan
                </button>
            </div>
        </div>
    </div>
</div>

@once
<!-- Cropper.js CSS & JS (Load sekali aja) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

<style>
    .aspect-btn-{{ $name }} {
        padding: 8px 16px;
        background: #f3f4f6;
        border: 2px solid transparent;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 13px;
        font-weight: 500;
    }
    .aspect-btn-{{ $name }}:hover {
        background: #e5e7eb;
    }
    .aspect-btn-{{ $name }}.active {
        background: #3b82f6;
        color: white;
        border-color: #2563eb;
    }
    .btn-control {
        padding: 8px 12px;
        background: #f3f4f6;
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s;
        border: none;
    }
    .btn-control:hover {
        background: #e5e7eb;
    }
    #{{ $name }}_modal.active {
        display: flex !important;
    }
</style>
@endonce

<script>
    (function() {
        let cropper_{{ $name }} = null;
        let croppedBlob_{{ $name }} = null;

        const input = document.getElementById('{{ $name }}_input');
        const modal = document.getElementById('{{ $name }}_modal');
        const cropperImg = document.getElementById('{{ $name }}_cropper_img');
        const preview = document.getElementById('{{ $name }}_preview');
        const hiddenInput = document.getElementById('{{ $name }}_cropped');
        const display = document.getElementById('{{ $name }}_display');

        // File input change
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validasi format
            if (!file.type.match(/^image\/(jpeg|jpg|png)$/)) {
                alert('❌ Hanya file JPG/PNG yang diperbolehkan!');
                input.value = '';
                return;
            }

            // Validasi ukuran (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('❌ Ukuran file maksimal 5MB!');
                input.value = '';
                return;
            }

            // Baca & tampilkan di cropper
            const reader = new FileReader();
            reader.onload = function(e) {
                cropperImg.src = e.target.result;
                openCropModal_{{ $name }}();
            };
            reader.readAsDataURL(file);
        });

        // Open modal
        window.openCropModal_{{ $name }} = function() {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            setTimeout(() => {
                if (cropper_{{ $name }}) {
                    cropper_{{ $name }}.destroy();
                }
                
                cropper_{{ $name }} = new Cropper(cropperImg, {
                    aspectRatio: {{ $aspectRatio }},
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

        // Close modal
        window.closeCropModal_{{ $name }} = function() {
            modal.classList.remove('active');
            document.body.style.overflow = '';
            if (cropper_{{ $name }}) {
                cropper_{{ $name }}.destroy();
                cropper_{{ $name }} = null;
            }
            input.value = '';
        };

        // Set aspect ratio
        window.setAspectRatio_{{ $name }} = function(ratio, btn) {
            document.querySelectorAll('.aspect-btn-{{ $name }}').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            if (cropper_{{ $name }}) {
                cropper_{{ $name }}.setAspectRatio(ratio);
            }
        };

        // Controls
        window.rotateLeft_{{ $name }} = function() {
            if (cropper_{{ $name }}) cropper_{{ $name }}.rotate(-45);
        };

        window.rotateRight_{{ $name }} = function() {
            if (cropper_{{ $name }}) cropper_{{ $name }}.rotate(45);
        };

        window.flipH_{{ $name }} = function() {
            if (cropper_{{ $name }}) {
                const scaleX = cropper_{{ $name }}.getData().scaleX || 1;
                cropper_{{ $name }}.scaleX(-scaleX);
            }
        };

        window.zoomIn_{{ $name }} = function() {
            if (cropper_{{ $name }}) cropper_{{ $name }}.zoom(0.1);
        };

        window.zoomOut_{{ $name }} = function() {
            if (cropper_{{ $name }}) cropper_{{ $name }}.zoom(-0.1);
        };

        // Apply crop
        window.applyCrop_{{ $name }} = function() {
            if (!cropper_{{ $name }}) return;

            cropper_{{ $name }}.getCroppedCanvas({
                width: {{ $width }},
                height: {{ $height }},
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            }).toBlob(function(blob) {
                croppedBlob_{{ $name }} = blob;
                
                // Convert to base64
                const reader = new FileReader();
                reader.onloadend = function() {
                    hiddenInput.value = reader.result;
                    
                    // Update preview
                    const url = URL.createObjectURL(blob);
                    
                    if (preview) {
                        preview.src = url;
                    } else {
                        // Create new preview if not exists
                        display.innerHTML = `
                            <div class="w-40 h-40 rounded-full overflow-hidden shadow-lg">
                                <img src="${url}" alt="Preview" class="w-full h-full object-cover" id="{{ $name }}_preview">
                            </div>
                            <button type="button" 
                                    onclick="document.getElementById('{{ $name }}_input').click()" 
                                    class="absolute bottom-2 right-2 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-blue-50 transition">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </button>
                        `;
                    }
                    
                    closeCropModal_{{ $name }}();
                    
                    // Show success message
                    showToast_{{ $name }}('✅ Foto berhasil di-crop!');
                };
                reader.readAsDataURL(blob);
            }, 'image/jpeg', 0.9);
        };

        // Toast notification
        window.showToast_{{ $name }} = function(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-[999] transform transition-all duration-300';
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
                setTimeout(() => toast.remove(), 300);
            }, 2000);
        };
    })();
</script>