@extends('layouts.dokter')

@section('title', 'Laporan - WarasWaris')

@section('content')
<div class="main">
    <div class="report-title-section">
        <svg width="45" height="40" viewBox="0 0 45 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M44.456 34.1013C44.722 31.1938 45 26.5787 45 20C45 13.4213 44.722 8.80622 44.456 5.89867C44.189 2.976 41.652 0.720889 38.364 0.483555C35.093 0.247111 29.902 0 22.5 0C15.099 0 9.907 0.247111 6.636 0.483555C3.348 0.720889 0.811 2.976 0.544 5.89867C0.278 8.80622 0 13.4213 0 20C0 26.5787 0.278 31.1938 0.544 34.1013C0.811 37.024 3.348 39.2791 6.636 39.5164C9.907 39.7529 15.099 40 22.5 40C29.901 40 35.093 39.7529 38.364 39.5164C41.652 39.2791 44.189 37.024 44.456 34.1013ZM25.186 30.8231C25.9318 31.3086 26.8543 31.5299 27.7737 31.4438C28.693 31.3576 29.5434 30.9703 30.159 30.3573C33.935 26.5849 36.358 23.2196 37.57 21.3636C38.205 20.392 38.374 19.1876 37.427 18.4436C37.1703 18.2419 36.8946 18.0602 36.603 17.9004C35.379 17.2302 34.01 17.8978 33.093 18.8818C31.59 20.496 29.196 23.0302 27.475 24.6773C27.3152 24.8325 27.0961 24.929 26.8604 24.9481C26.6248 24.9671 26.3897 24.9074 26.201 24.7804C24.671 23.7573 22.765 22.3004 21.243 21.1022C19.721 19.9031 17.428 19.8578 15.969 21.1173C13.471 23.2756 10.894 25.8044 8.744 28.0418C7.377 29.464 7.214 31.544 8.791 32.784C9.20997 33.1115 9.65221 33.4146 10.115 33.6916C11.972 34.7938 14.285 33.9262 15.459 32.2373C16.466 30.7884 17.717 29.0507 18.844 27.6711C18.9222 27.5766 19.0211 27.4971 19.1347 27.4374C19.2483 27.3777 19.3743 27.3391 19.5051 27.3237C19.6359 27.3083 19.7689 27.3166 19.8961 27.3481C20.0232 27.3795 20.1419 27.4335 20.245 27.5067C21.8704 28.6371 23.5176 29.7433 25.186 30.8231ZM7.5 8.44444C7.5 7.97295 7.71071 7.52076 8.08579 7.18737C8.46086 6.85397 8.96957 6.66667 9.5 6.66667H19.5C20.0304 6.66667 20.5391 6.85397 20.9142 7.18737C21.2893 7.52076 21.5 7.97295 21.5 8.44444C21.5 8.91594 21.2893 9.36812 20.9142 9.70152C20.5391 10.0349 20.0304 10.2222 19.5 10.2222H9.5C8.96957 10.2222 8.46086 10.0349 8.08579 9.70152C7.71071 9.36812 7.5 8.91594 7.5 8.44444ZM9.5 13.7778C8.96957 13.7778 8.46086 13.9651 8.08579 14.2985C7.71071 14.6319 7.5 15.0841 7.5 15.5556C7.5 16.0271 7.71071 16.4792 8.08579 16.8126C8.46086 17.146 8.96957 17.3333 9.5 17.3333H15.5C16.0304 17.3333 16.5391 17.146 16.9142 16.8126C17.2893 16.4792 17.5 16.0271 17.5 15.5556C17.5 15.0841 17.2893 14.6319 16.9142 14.2985C16.5391 13.9651 16.0304 13.7778 15.5 13.7778H9.5Z" fill="#809FF5"/>
        </svg>
        <div class="report-title-wrapper">
            <h2 class="judul">Laporan Klinik</h2>
            <p class="subjudul">Klinik WarasWaris</p>
        </div>
    </div>

    <form method="GET" action="{{ route('dokter.laporan') }}">
        <div class="header">
            <div class="header-left">
                <x-datepicker name="tanggal">
                    <x-slot:icon>
                        <svg width="11" height="7" viewBox="0 0 11 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.26783 6.75L3.8147e-06 0L10.5357 0L5.26783 6.75Z" fill="#5A81FA"/>
                        </svg>
                    </x-slot:icon>
                </x-datepicker>
                <button type="submit" class="btn-filter">
                    Filter
                </button>
            </div>
            <div class="header-right">
                <div class="search">
                    <input id="searchInput" 
                        type="text" 
                        name="search" 
                        value="{{ $search ?? '' }}"
                        placeholder="Cari nama pasien..."
                        autocomplete="off">
                </div>
            </div>
        </div>
    </form>

    <div class="stats-container">
        <div class="stat-card jam-praktik">
            <div class="stat-header">Jam Praktik</div>
            <div class="stat-value time-value">
                @if($jadwal && $jadwal->is_active)
                    {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                @else
                    LIBUR
                @endif
            </div>
        </div>

        <div class="stat-card total-reservasi-card">
            <div class="stat-header">Total Reservasi</div>
            <div class="stat-value highlight-pink">{{ $total_reservasi }}</div>
        </div>

        <div class="stat-card pasien-terlayani-card">
            <div class="stat-header">Pasien Terlayani</div>
            <div class="stat-value highlight-ungu">{{ $pasien_terlayani }}</div>
        </div>

        <div class="stat-card pasien-tidak-datang-card">
            <div class="stat-header">Pasien Tidak Datang</div>
            <div class="stat-value highlight-biru">{{ $pasien_tidak_datang }}</div>
        </div>
    </div>

    <div class="patient-list-container">
        <div id="tableContainer">
            @if($daftar_reservasi->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Tidak ada data pasien pada tanggal ini</p>
                </div>
            @else
                @foreach($daftar_reservasi as $reservasi)
                <div class="patient-card">
                    <div class="patient-number">{{ $reservasi->nomor_antrian }}</div>
                    <div class="patient-info">
                        <div class="patient-name">{{ $reservasi->data_pasien->nama_pasien }}</div>
                    </div>
                    <div class="patient-contact">
                        <a href="https://wa.me/{{ $reservasi->data_pasien->no_telepon }}" target="_blank" class="whatsapp-btn">
                            <i class="ri-whatsapp-fill" style="color: #00C42E; font-size: 30px; margin-left: 10px;"></i>
                        </a>
                    </div>

                    <div class="patient-action">
                        @if($reservasi->status == 'selesai' && $reservasi->rekam_medis)
                            <button class="btn-detail" onclick="openPasienModal({{ $reservasi->data_pasien->id }})">
                                <i class="fas fa-eye"></i> Lebih Detail
                            </button>
                        @elseif($reservasi->status == 'batal')
                            <button class="btn-cancel" disabled>
                                Dibatalkan
                            </button>
                        @else
                            <button class="btn-detail2" disabled>
                                Belum Selesai
                            </button>
                        @endif
                    </div>

                    <div class="patient-status">
                        @switch($reservasi->status)
                            @case('menunggu')
                                <span class="status-badge status-waiting">Sisa Reservasi</span>
                                @break
                            @case('sedang_dilayani')
                                <span class="status-badge status-progress">Sedang Dilayani</span>
                                @break
                            @case('selesai')
                                <span class="status-badge status-complete">Selesai</span>
                                @break
                            @case('batal')
                                <span class="status-badge status-cancel">Dibatalkan</span>
                                @break
                        @endswitch
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<!-- Modal Rekam Medis -->
<div id="pasienOverlay" class="pasien-overlay" hidden>
    <div id="pasienBox" role="dialog" aria-modal="true" aria-labelledby="pasienTitle" class="pasien-box">
        <div class="pasien-header">
            <button type="button" class="pasien-close-btn" onclick="closePasienModal()"><i class="ri-close-line"></i></button>
            <h3 id="pasienTitle" class="pasien-title">Biodata Pasien</h3>
        </div>

        <div id="pasien-loading" class="pasien-loading">Memuat data…</div>
        <div id="pasien-error" class="pasien-error">Terjadi kesalahan saat memuat data.</div>

        <div id="pasien-content" hidden class="pasien-content">
            <div class="pasien-biodata">
                <div class="col-foto">
                    <div id="lap-avatar"></div>
                </div>

                <div class="col-data">
                    <div class="pasien-identitas">
                        <div class="pasien-identitas-title">
                            Nama
                            <span id="p-nama-pasien" class="pasien-identitas-nama">-</span>
                        </div>
                        <div class="divider-identitas">|</div>
                        <div class="pasien-identitas-title">
                            Wali
                            <span id="p-nama-wali" class="pasien-identitas-nama">-</span>
                        </div>
                    </div>
                    
                    <div class="pasien-detail">
                        <div class="pasien-detail-label">
                            <strong>Jenis Kelamin</strong>
                            <span id="p-jenis-kelamin-pasien">-</span>
                        </div>
                        <div class="pasien-detail-label">
                            <strong>Tanggal Lahir</strong>
                            <span id="p-tanggal-lahir-pasien">-</span>
                        </div>
                        <div class="pasien-detail-label">
                            <strong>Umur</strong>
                            <span id="p-umur">-</span>
                        </div>
                        <div class="pasien-detail-label">
                            <strong>Gol. Darah</strong>
                            <span id="p-golongan-darah">-</span>
                        </div>
                        <div class="pasien-detail-label">
                            <strong>Pekerjaan</strong>
                            <span id="p-pekerjaan">-</span>
                        </div>
                        <div class="pasien-detail-label">
                            <strong>Alamat</strong>
                            <span id="p-alamat">-</span>
                        </div>
                        <div class="pasien-detail-label">
                            <strong>No. Telepon</strong>
                            <span id="p-no-telepon">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pasien-catatan">
                <strong>Catatan Pasien</strong> <br>
                <span id="p-catatan-pasien">-</span>
            </div>
        </div>

    </div>
</div>

<script>
const assetBase = "{{ asset('') }}";
// ========================================
// AJAX SEARCH (TANPA RELOAD HALAMAN)
// ========================================
(function() {
    const searchInput = document.getElementById('searchInput');
    const tanggalInput = document.getElementById('tanggalInput');
    const tableContainer = document.getElementById('tableContainer');
    let searchTimeout;

    // Fungsi untuk melakukan pencarian dengan AJAX
    function doSearch() {
        const searchValue = searchInput.value.trim();
        const tanggal = tanggalInput.value;

        // Buat URL untuk AJAX request
        const url = new URL('{{ route("dokter.laporan") }}');
        url.searchParams.set('tanggal', tanggal);
        if (searchValue) {
            url.searchParams.set('search', searchValue);
        }

        // Kirim AJAX request
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            if (data.success) {
                updateTable(data.data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            tableContainer.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>Terjadi kesalahan saat memuat data</p>
                </div>
            `;
        });
    }

    // Fungsi untuk update tabel dengan data hasil search
    function updateTable(data) {
        if (data.length === 0) {
            tableContainer.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Tidak ada data pasien yang sesuai</p>
                </div>
            `;
            return;
        }

        let cards = '';
        data.forEach(reservasi => {
            const statusBadge = getStatusBadge(reservasi.status);
            const actionButton = getActionButton(reservasi);

            cards += `
                <div class="patient-card">
                    <div class="patient-number">${reservasi.nomor_antrian}</div>
                    <div class="patient-info">
                        <div class="patient-name">${reservasi.nama_pasien}</div>
                    </div>
                    <div class="patient-contact">
                        <a href="https://wa.me/${reservasi.no_telepon}" target="_blank" class="whatsapp-btn">
                            <i class="ri-whatsapp-fill" style="color: #00C42E; font-size: 30px; margin-left: 10px;"></i>
                        </a>
                    </div>
                    <div class="patient-action">${actionButton}</div>
                    <div class="patient-status">${statusBadge}</div>
                </div>
            `;
        });

        tableContainer.innerHTML = cards;
    }

    function getStatusBadge(status) {
        switch(status) {
            case 'menunggu':
                return '<span class="status-badge status-waiting">Sisa Reservasi</span>';
            case 'sedang_dilayani':
                return '<span class="status-badge status-progress">Sedang Dilayani</span>';
            case 'selesai':
                return '<span class="status-badge status-complete">Selesai</span>';
            case 'batal':
                return '<span class="status-badge status-cancel">Dibatalkan</span>';
            default:
                return '';
        }
    }

    function getActionButton(reservasi) {
        if (reservasi.status === 'selesai' && reservasi.rekam_medis_id) {
            return `<button class="btn-detail" onclick="openPasienModal(${reservasi.id_pasien})">
                        <i class="fas fa-eye"></i> Lebih Detail
                    </button>`;
        } else if (reservasi.status === 'batal') {
            return '<button class="btn-cancel" disabled>Dibatalkan</button>';
        } else {
            return '<button class="btn-detail2" disabled>Belum Selesai</button>';
        }
    }

    // Event listener untuk search input (dengan debounce)
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(doSearch, 300); // Delay 300ms
    });

    // Clear search dengan ESC
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            e.preventDefault();
            searchInput.value = '';
            doSearch();
        }
    });

    // PENTING: Tanggal input TIDAK pakai AJAX, langsung submit form
    // Tidak ada event listener untuk tanggalInput.addEventListener('change')
})();

// ========================================
// AUTO-RELOAD HANYA UNTUK HARI INI
// ========================================
@if($tanggal_dipilih->isToday())
setInterval(() => {
    // Jangan reload jika modal sedang terbuka
    const modal = document.getElementById('pasienOverlay');
    if (modal && !modal.hidden) return;
    
    location.reload();
}, 30000); // Reload setiap 30 detik
@endif

// ========================================
// MODAL BIODATA PASIEN
// ========================================
function openPasienModal(pasienId) {
    const overlay = document.getElementById('pasienOverlay');
    const loadEl  = document.getElementById('pasien-loading');
    const errEl   = document.getElementById('pasien-error');
    const cntEl   = document.getElementById('pasien-content');

    // Reset display
    errEl.textContent = '';
    errEl.style.display = 'none';
    loadEl.hidden = false;
    cntEl.hidden  = true;
    renderLapAvatar(null, '');

    // Show overlay
    overlay.hidden = false;
    document.body.style.overflow = 'hidden';

    // URL ke controller detail_pasien
    const url = "{{ route('dokter.pasien.detail', ['id' => '__ID__']) }}".replace('__ID__', pasienId);

    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(async (response) => {
        const text = await response.text();
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${text.substring(0, 200)}`);
        }
        try { return JSON.parse(text); }
        catch { throw new Error('Response bukan JSON: ' + text.substring(0, 200)); }
    })
    .then((data) => {
        // Isi konten modal
        document.getElementById('p-nama-pasien').textContent = data.nama_pasien ?? '-';
        document.getElementById('p-nama-wali').textContent = data.nama_wali ?? '-';
        document.getElementById('p-jenis-kelamin-pasien').textContent = data.jenis_kelamin_pasien ?? '-';
        document.getElementById('p-tanggal-lahir-pasien').textContent = data.tanggal_lahir_pasien ?? '-';
        document.getElementById('p-umur').textContent = (data.umur != null) ? (data.umur + ' tahun') : '-';
        document.getElementById('p-golongan-darah').textContent = data.golongan_darah ?? '-';
        document.getElementById('p-pekerjaan').textContent = data.pekerjaan ?? '-';
        document.getElementById('p-alamat').textContent = data.alamat ?? '-';
        document.getElementById('p-no-telepon').textContent = data.no_telepon ?? '-';
        document.getElementById('p-catatan-pasien').textContent = data.catatan_pasien ?? '-';
        renderLapAvatar(data.foto_path ?? null, data.nama_pasien ?? '-');

        loadEl.hidden = true;
        cntEl.hidden  = false;
    })
    .catch((error) => {
        console.error('Fetch error:', error);
        loadEl.hidden = true;
        errEl.textContent = 'Gagal memuat detail pasien: ' + error.message;
        errEl.style.display = 'block';
    });

    // Tutup saat klik area luar
    overlay.onclick = function (ev) {
        if (ev.target === overlay) closePasienModal();
    };

    // Tutup dengan ESC
    document.addEventListener('keydown', escCloserPasien);
}

function escCloserPasien(ev) {
    if (ev.key === 'Escape') {
        closePasienModal();
    }
}

function closePasienModal() {
    const overlay = document.getElementById('pasienOverlay');
    overlay.hidden = true;
    document.body.style.overflow = '';
    document.removeEventListener('keydown', escCloserPasien);
}

function renderLapAvatar(fotoPath, namaPasien) {
    const avatarEl = document.getElementById('lap-avatar');
    if (!avatarEl) return;

    const initial = (namaPasien || '?').toString().trim().charAt(0).toUpperCase() || '?';
    if (fotoPath) {
        const fotoUrl = fotoPath.startsWith('http') ? fotoPath : assetBase + fotoPath;
        avatarEl.innerHTML = `
            <img 
                src="${fotoUrl}" 
                alt="${namaPasien || 'Pasien'}"
                class="patientlist-avatar"
                onerror="this.onerror=null; this.parentElement.innerHTML='<div style=&quot;width:100%;height:100%;background:linear-gradient(135deg,#60a5fa,#2563eb);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;&quot;>${initial}</div>';"
            >
        `;
    } else {
        avatarEl.innerHTML = `
            <div class="patientlist-avatar-initial">
                ${initial}
            </div>
        `;
    }
}
</script>
@endsection
