@extends('layouts.dokter')

@section('title', 'Daftar Pasien - WarasWaris')

@section('content')
<div class="patientlist-container">
  <!-- Header: Judul + Search + Total -->
    <div class="patientlist-header">
        <div class="patientlist-title-section">
            <svg width="50" height="50" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.1465 3.90234C15.29 3.90234 13.5095 4.63984 12.1967 5.9526C10.884 7.26535 10.1465 9.04583 10.1465 10.9023V41.9023H54.1465V10.9023C54.1465 9.04583 53.409 7.26535 52.0962 5.9526C50.7835 4.63984 49.003 3.90234 47.1465 3.90234H17.1465Z" fill="url(#paint0_linear_236_496)"/>
                <path d="M20.1465 41.9023V33.9023C20.1465 32.8415 20.5679 31.8241 21.3181 31.0739C22.0682 30.3238 23.0856 29.9023 24.1465 29.9023H40.1465C41.2073 29.9023 42.2248 30.3238 42.9749 31.0739C43.7251 31.8241 44.1465 32.8415 44.1465 33.9023V41.9023H20.1465Z" fill="url(#paint1_linear_236_496)"/>
                <path d="M40.1465 19.9023C40.1465 22.0241 39.3036 24.0589 37.8033 25.5592C36.303 27.0595 34.2682 27.9023 32.1465 27.9023C30.0248 27.9023 27.9899 27.0595 26.4896 25.5592C24.9893 24.0589 24.1465 22.0241 24.1465 19.9023C24.1465 17.7806 24.9893 15.7458 26.4896 14.2455C27.9899 12.7452 30.0248 11.9023 32.1465 11.9023C34.2682 11.9023 36.303 12.7452 37.8033 14.2455C39.3036 15.7458 40.1465 17.7806 40.1465 19.9023Z" fill="url(#paint2_linear_236_496)"/>
                <path d="M47.1465 59.9023C49.003 59.9023 50.7835 59.1648 52.0962 57.8521C53.409 56.5393 54.1465 54.7589 54.1465 52.9023V39.9023H10.1465V52.9023C10.1465 54.7589 10.884 56.5393 12.1967 57.8521C13.5095 59.1648 15.29 59.9023 17.1465 59.9023H47.1465Z" fill="url(#paint3_linear_236_496)"/>
                <path d="M47.1465 59.9023C49.003 59.9023 50.7835 59.1648 52.0962 57.8521C53.409 56.5393 54.1465 54.7589 54.1465 52.9023V39.9023H10.1465V52.9023C10.1465 54.7589 10.884 56.5393 12.1967 57.8521C13.5095 59.1648 15.29 59.9023 17.1465 59.9023H47.1465Z" fill="url(#paint4_linear_236_496)" fill-opacity="0.7"/>
                <path d="M20.1465 47.9023C19.6161 47.9023 19.1073 48.1131 18.7323 48.4881C18.3572 48.8632 18.1465 49.3719 18.1465 49.9023C18.1465 50.4328 18.3572 50.9415 18.7323 51.3166C19.1073 51.6916 19.6161 51.9023 20.1465 51.9023H44.1465C44.6769 51.9023 45.1856 51.6916 45.5607 51.3166C45.9358 50.9415 46.1465 50.4328 46.1465 49.9023C46.1465 49.3719 45.9358 48.8632 45.5607 48.4881C45.1856 48.1131 44.6769 47.9023 44.1465 47.9023H20.1465Z" fill="url(#paint5_linear_236_496)"/>
                <defs>
                    <linearGradient id="paint0_linear_236_496" x1="25.8605" y1="3.90234" x2="38.9005" y2="41.1883" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#B3E0FF"/>
                        <stop offset="1" stop-color="#8CD0FF"/>
                    </linearGradient>
                    <linearGradient id="paint1_linear_236_496" x1="25.8545" y1="31.4983" x2="29.7305" y2="43.8783" gradientUnits="userSpaceOnUse">
                        <stop offset="0.125" stop-color="#9C6CFE"/>
                        <stop offset="1" stop-color="#7A41DC"/>
                    </linearGradient>
                    <linearGradient id="paint2_linear_236_496" x1="27.9505" y1="14.0283" x2="36.1065" y2="27.0503" gradientUnits="userSpaceOnUse">
                        <stop offset="0.125" stop-color="#9C6CFE"/>
                        <stop offset="1" stop-color="#7A41DC"/>
                    </linearGradient>
                    <linearGradient id="paint3_linear_236_496" x1="-0.853515" y1="-0.0576557" x2="55.6925" y2="58.2703" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#36DFF1"/>
                        <stop offset="1" stop-color="#2764E7"/>
                    </linearGradient>
                    <linearGradient id="paint4_linear_236_496" x1="32.1465" y1="21.5683" x2="36.6805" y2="69.3703" gradientUnits="userSpaceOnUse">
                        <stop offset="0.619" stop-color="#FF6CE8" stop-opacity="0"/>
                        <stop offset="1" stop-color="#FF6CE8"/>
                    </linearGradient>
                    <linearGradient id="paint5_linear_236_496" x1="23.5305" y1="48.2103" x2="24.0205" y2="56.5143" gradientUnits="userSpaceOnUse">
                        <stop stop-color="white"/>
                        <stop offset="1" stop-color="#B3E0FF"/>
                    </linearGradient>
                </defs>
            </svg>
            <div class="patientlist-title-wrapper">
                <h2 class="patientlist-title">Daftar Pasien</h2>
                <p class="patientlist-subtitle">Klinik WarasWaris</p>
            </div>
        </div>

        <div class="patientlist-search-section">
            <div class="patientlist-search">
                <input type="text" id="searchInput" placeholder="Cari" class="patientlist-search-input">
                <i class="ri-search-line"></i>
            </div>
            <div class="patientlist-total">
                Total Pasien: {{ $total_pasien }}
            </div>
        </div>
    </div>

    <!-- Header kolom -->
    <div class="patientlist-table-header">
        <div class="patientlist-col-photo"></div>
        <div class="patientlist-col-name">Nama Pasien</div>
        <div class="patientlist-col-guardian">Wali Pasien</div>
        <div class="patientlist-col-lastvisit">Kunjungan Terakhir</div>
        <div class="patientlist-col-contact">Kontak</div>
        <div class="patientlist-col-detail"></div>
    </div>

    <!-- Rows -->
    <div class="patientlist-table-body">
        @forelse($pasien_list as $pasien)
            @php
                $tanggal = $pasien->reservasi_terbaru?->tanggal_reservasi;
                $kunjungan_terakhir = $tanggal ? \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') : '-';
                $wali = $pasien->nama_wali ?? '-';
                $wa   = preg_replace('/\D/', '', (string)($pasien->no_telepon ?? ''));
                $initial = strtoupper(mb_substr($pasien->nama_pasien ?? '-', 0, 1));
            @endphp

            <div class="patientlist-table-row">
                {{-- foto --}}
                <div class="patientlist-col-photo">
                    <x-avatar-pasien :pasien="$pasien" size="lg" />
                </div>

                <!-- Nama + avatar -->
                <div class="patientlist-col-name-value">{{ $pasien->nama_pasien }}</div>

                <!-- Wali -->
                <div class="patientlist-col-guardian-value">{{ $wali }}</div>

                <!-- Kunjungan terakhir -->
                <div class="patientlist-col-lastvisit-value">{{ $kunjungan_terakhir }}</div>

                <!-- Kontak -->
                <div class="patientlist-col-contact-value">
                    @if($wa)
                        <a href="https://wa.me/{{ $wa }}" target="_blank" title="WhatsApp" class="patientlist-whatsapp-link">
                            <i class="ri-whatsapp-fill" style="color: #00C42E; font-size: 30px; margin-left: 10px;"></i>
                        </a>
                    @else
                        <span class="patientlist-whatsapp-disabled">
                            <i class="ri-whatsapp-fill" style="color: #aaa; font-size: 30px; margin-left: 10px;"></i>
                        </span>
                    @endif
                </div>

                {{-- detail --}}
                <div class="patientlist-col-detail-value">
                    <a href="javascript:void(0)" onclick="openPasienModal({{ $pasien->id }})" class="patientlist-detail-link">Lebih Detail</a>
                </div>
            </div>
        
        @empty
            <div class="patientlist-no-data">Belum ada data pasien.</div>
        @endforelse
    </div>
</div>

<!-- Modal Lihat Detail -->
<div id="pasienOverlay" class="patientlist-modal-overlay" hidden>
    <div id="pasienBox" role="dialog" aria-modal="true" aria-labelledby="pasienTitle" class="patientlist-modal-box">
        <div class="patientlist-modal-header">
            <button type="button" class="patientlist-modal-close-btn" onclick="closePasienModal()"><i class="ri-close-line"></i></button>
            <h3 id="pasienTitle" class="patientlist-modal-title">Biodata Pasien</h3>
        </div>

        <div id="pasien-loading" class="patientlist-modal-loading">Memuat data…</div>
        <div id="pasien-error" class="patientlist-modal-error">Terjadi kesalahan saat memuat data.</div>

        <div id="pasien-content" hidden class="patientlist-modal-content">
            <div class="patientlist-modal-biodata">
                <div class="patientlist-col-photo">
                    <div id="p-avatar"></div>
                </div>

                <div class="patientlist-modal-data-col">
                    <div class="patientlist-modal-identity">
                        <div class="patientlist-modal-identity-title">
                            Nama
                            <span id="p-nama-pasien" class="patientlist-modal-identity-value">-</span>
                        </div>
                        <div class="patientlist-modal-identity-divider">
                            |
                        </div>
                        <div class="patientlist-modal-identity-title">
                            Wali
                            <span id="p-nama-wali" class="patientlist-modal-identity-value">-</span>
                        </div>
                    </div>
          
                    <div class="patientlist-modal-details">
                        <div class="patientlist-modal-detail-item">
                            <strong>Jenis Kelamin</strong>
                            <span id="p-jenis-kelamin-pasien">-</span>
                        </div>
                        <div class="patientlist-modal-detail-item">
                            <strong>Tanggal Lahir</strong>
                            <span id="p-tanggal-lahir-pasien">-</span>
                        </div>
                        <div class="patientlist-modal-detail-item">
                            <strong>Umur</strong>
                            <span id="p-umur">-</span>
                        </div>
                        <div class="patientlist-modal-detail-item">
                            <strong>Golongan Darah</strong>
                            <span id="p-golongan-darah">-</span>
                        </div>
                        <div class="patientlist-modal-detail-item">
                            <strong>Pekerjaan</strong>
                            <span id="p-pekerjaan">-</span>
                        </div>
                        <div class="patientlist-modal-detail-item">
                            <strong>Alamat</strong>
                            <span id="p-alamat">-</span>
                        </div>
                        <div class="patientlist-modal-detail-item">
                            <strong>Nomor Telepon</strong>
                            <span id="p-no-telepon">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="patientlist-modal-notes">
                <strong>Catatan Pasien</strong> <br>
                <span id="p-catatan-pasien">-</span>
            </div>
        </div>

        <div class="patientlist-modal-button-area">
            <a href="{{ route('dokter.riwayat_rekam_medis') }}" id="btn-rekam-medis" class="patientlist-modal-medicalrecord-btn">
                Lihat Rekam Medis
            </a>
        </div>
    </div>
</div>

<script>
    const assetBase = "{{ asset('') }}";
    const input = document.getElementById('searchInput');
    const riwayatRekamMedisBaseURL = "{{ route('dokter.riwayat_rekam_medis') }}";

    // Enter otomatis submit (default behavior form), kita hanya urus reset.
    input.addEventListener('input', () => {
        if (input.value.trim() === '') {
        // kosong → reset ke daftar penuh (tanpa query ?q=)
        }
    });

    // Bonus: Esc untuk cepat clear + reset
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
        e.preventDefault();
        input.value = '';
        window.location.href = baseURL;
        }
    });



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
    renderPasienModalAvatar(null, '');

    // Show overlay
    overlay.hidden = false;
    document.body.style.overflow = 'hidden';

    // URL ke controller detail_pasien
    const url = "{{ route('dokter.pasien.detail', ['id' => '__ID__']) }}".replace('__ID__', pasienId);
    console.log('Fetching URL:', url);

    fetch(url, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      credentials: 'same-origin'
    })
    .then(async (response) => {
      console.log('Response status:', response.status);
      const text = await response.text();
      if (!response.ok) {
        console.error('Error response:', text);
        throw new Error(`HTTP ${response.status}: ${text.substring(0, 200)}`);
      }
      try { return JSON.parse(text); }
      catch { throw new Error('Response bukan JSON: ' + text.substring(0, 200)); }
    })
    .then((data) => {
      console.log('Data received:', data);

      // Isi konten modal (tanpa keluhan)
      document.getElementById('p-nama-pasien').textContent    = data.nama_pasien ?? '-';
      document.getElementById('p-nama-wali').textContent      = data.nama_wali ?? '-';
      document.getElementById('p-jenis-kelamin-pasien').textContent  = data.jenis_kelamin_pasien ?? '-';
      document.getElementById('p-tanggal-lahir-pasien').textContent  = data.tanggal_lahir_pasien ?? '-';
      document.getElementById('p-umur').textContent           = (data.umur != null) ? (data.umur + ' tahun') : '-';
      document.getElementById('p-golongan-darah').textContent = data.golongan_darah ?? '-';
      document.getElementById('p-pekerjaan').textContent      = data.pekerjaan ?? '-';
      document.getElementById('p-alamat').textContent         = data.alamat ?? '-';
      document.getElementById('p-no-telepon').textContent     = data.no_telepon ?? '-';
      document.getElementById('p-catatan-pasien').textContent        = data.catatan_pasien ?? '-';
      updateRekamMedisLink(data.id ?? null);
      renderPasienModalAvatar(data.foto_path ?? null, data.nama_pasien ?? '-');

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

  function updateRekamMedisLink(pasienId) {
    const btn = document.getElementById('btn-rekam-medis');
    if (!btn) return;
    let target = riwayatRekamMedisBaseURL;
    if (pasienId) {
      const separator = target.includes('?') ? '&' : '?';
      target = `${target}${separator}pasien_id=${encodeURIComponent(pasienId)}`;
    }
    btn.href = target;
  }

  function renderPasienModalAvatar(fotoPath, namaPasien) {
    const avatarEl = document.getElementById('p-avatar');
    if (!avatarEl) return;

    const initial = (namaPasien || '?').toString().trim().charAt(0).toUpperCase() || '?';
    if (fotoPath) {
      const fotoUrl = fotoPath.startsWith('http') ? fotoPath : assetBase + fotoPath;
      avatarEl.innerHTML = `
        <img 
          src="${fotoUrl}" 
          alt="${namaPasien || 'Pasien'}"
          class= "patientlist-avatar"
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

document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const patientItems = document.querySelectorAll('.patientlist-table-row');
    
    patientItems.forEach(item => {
        const patientName = item.querySelector('.patientlist-col-name-value').textContent.toLowerCase();
        
        if (patientName.includes(searchValue)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>
@endsection