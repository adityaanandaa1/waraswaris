@extends('layouts.app')

@section('title', 'Dashboard - WarasWaris')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
    
</style>
@endpush

@section('content')
<div class="dashboard-container">
    
    <!-- SIDEBAR KIRI - NAVIGASI -->
    <div class="sidebar-nav">
        <button class="nav-btn dashboard active" onclick="switchTab('dashboard')">
           Dashboard
        </button>
        <button class="nav-btn riwayat" onclick="switchTab('riwayat')">
            Riwayat
        </button>
        <button class="nav-btn reservasi" onclick="switchTab('reservasi')">
            Reservasi
        </button>
    </div>

    <!-- MAIN CONTENT TENGAH -->
    <div class="main-content bg-dashboard" id="mainContent">
        
        <!-- TAB 1: DASHBOARD -->
        <div class="content-section active" id="tab-dashboard">
            <div class="content-header">
                <div>
                    <h1>Dashboard</h1>
                    <div class="date" id="currentDate"></div>
                </div>
                <a href="{{ route('pasien.edit_biodata', $pasien_aktif->id) }}" class="btn-edit-dashboard">
                    <i class="ri-settings-5-fill"></i>
                </a>
            </div>

            <div class="welcome-banner">
                <img src="{{ asset('images/dbpasien.png') }}" alt="Dokter" onerror="this.style.display='none'">
                <div class="welcome-text">
                    <h2>Selamat Datang!</h2>
                    <p>Konsultasikan kesehatanmu sekarang juga!</p>
                </div>
            </div>

            <div class="calendar-container">
                <div class="calendar-header" id="calendarMonth"></div>
                <div class="calendar-days">
                    <div class="day-name">Senin</div>
                    <div class="day-name">Selasa</div>
                    <div class="day-name">Rabu</div>
                    <div class="day-name">Kamis</div>
                    <div class="day-name">Jumat</div>
                    <div class="day-name">Sabtu</div>
                    <div class="day-name">Minggu</div>
                </div>
                <div class="calendar-dates" id="calendarDates"></div>
            </div>
        </div>

        <!-- TAB 2: RIWAYAT -->
        <div class="content-section" id="tab-riwayat">
            <div class="content-header2">
                    <h1>Riwayat Reservasi</h1>
                    <div class="date" id="currentDate2"></div>
            </div>

            <div class="riwayat-header">
                <h3>Lihat kembali riwayat reservasi anda!</h3>
                <p>Data ini akan muncul setelah dokter menyelesaikan rekam medis Anda</p>
            </div>

            @if($reservasis->isEmpty())
                <div class="empty-state">
                    <h3>Belum Ada Riwayat</h3>
                    <p>Anda belum pernah melakukan reservasi</p>
                </div>
            @else
                @foreach($reservasis as $reservasi)
                    <div class="riwayat-item" onclick="window.location.href='{{ route('pasien.riwayat.detail', $reservasi->id) }}'">
                        <div class="riwayat-item-content">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div class="riwayat-icon">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="riwayat-info">
                                    <h4> {{ \Carbon\Carbon::parse($reservasi->tanggal_reservasi)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</h4>
                                    <p>Ketuk untuk melihat lebih detail tentang catatan riwayat pemeriksaan</p>
                                </div>
                            </div>
                            <svg width="24" height="24" fill="none" stroke="gray" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- TAB 3: RESERVASI -->
        <div class="content-section" id="tab-reservasi">
            <div class="content-header2">
                    <h1>Reservasi Pemeriksaan</h1>
                    <div class="date" id="currentDate3"></div>
            </div>

            @if($klinik_tutup)
                <div class="reservasi-cards">
                    <div class="info-card-time">
                        <div class="title">Jam Praktik</div>
                        <div class="time-row">
                            <div class="time-group">
                                <label>Buka</label>
                                <div class="time" style="color:#ef4444;">Libur</div>
                            </div>
                            <div class="separator">-</div>
                            <div class="time-group">
                                <label>Tutup</label>
                                <div class="time" style="color:#ef4444;">Libur</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <h3>Klinik Tutup</h3>
                    <p>Klinik tidak beroperasi pada hari <strong>{{ $nama_hari }}</strong></p>
                </div>
            @else
                <div class="reservasi-banner">
                    <img src="{{ asset('images/reservasi.png') }}" alt="Animasi Dokter" width="330">
                    <div class="reservasi-text">
                        <h2>Kesehatan Anda, satu klik lebih dekat!</h2>
                        <p >Isi keluhan, reservasi dan datangi klinik sekarang!</p>
                    </div>
                </div>

                <div class="reservasi-cards">
                    <div class="info-card-time">
                    <div class="title">Jam Praktik</div>
                    <div class="time-row">
                        <div class="time-group">
                            <label>Buka</label>
                            <div class="time">{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H.i') }}</div>
                        </div>
                        <div class="separator">-</div>
                        <div class="time-group">
                            <label>Tutup</label>
                            <div class="time">{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H.i') }}</div>
                        </div>
                    </div>
                </div>
                    
                    <div class="info-card">
                        <label>Nomor Antrian Sekarang</label>
                        <div class="value" style="color: {{ $antrian->nomor_sekarang > 0 ? '#667eea' : '#cbd5e0' }}">
                            {{ $antrian->nomor_sekarang > 0 ? $antrian->nomor_sekarang : '-' }}
                         </div>
                    </div>
                    <div class="info-card">
                        <label>Nomor Antrian Anda</label>
                        <div class="value" style="color: {{ $reservasi_aktif ? '#48bb78' : '#cbd5e0' }}">
                            {{ $reservasi_aktif ? $reservasi_aktif->nomor_antrian : '-' }}
                        </div>
                    </div>
                </div>

                @if($reservasi_aktif)
                    <!-- Sudah Reservasi -->
                    <div class="keluhan-box">
                        <h4>
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Keluhan
                        </h4>
                        <div style="background: #f7fafc; padding: 15px; border-radius: 12px; color: #4a5568; min-height: 100px; border: 2px solid #e2e8f0;">
                            {{ $reservasi_aktif->keluhan ?? 'Tidak ada keluhan spesifik' }}
                        </div>
                        
                        <p class="note" style="color: #e53e3e; font-weight: 600;">
                            * Keluhan tidak dapat diubah setelah reservasi dikonfirmasi
                        </p>

                     <!-- Info Nomor Antrian -->
                    <div style="background: #e6fffa; border: 2px solid #48bb78; border-radius: 12px; padding: 15px; margin: 20px 0; text-align: center;">
                        <p style="color: #2f855a; font-size: 14px; margin-bottom: 5px;">Reservasi Berhasil</p>
                        <p style="color: #2f855a; font-size: 18px; font-weight: 700;">
                            Nomor Antrian Anda: <span style="font-size: 32px;">{{ $reservasi_aktif->nomor_antrian }}</span>
                        </p>
                        <p style="color: #2f855a; font-size: 12px; margin-top: 5px;">
                            Status: <strong>{{ ucfirst($reservasi_aktif->status) }}</strong>
                        </p>
                    </div>

                    

                    <form action="{{ route('pasien.batalkan_reservasi', $reservasi_aktif->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan?')">
                        @csrf
                        <button type="submit" class="btn-primary btn-cancel">
                            Batalkan Reservasi
                        </button>
                    </form>

                    </div>
                @else
                    <!-- Form Reservasi -->
                    <form action="{{ route('pasien.buat_reservasi') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tanggal_reservasi" value="{{ today()->format('Y-m-d') }}">

                        <div class="keluhan-box">
                            <h4>
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Keluhan
                            </h4>
                            <textarea name="keluhan" rows="4" maxlength="500" required placeholder="Contoh: Demam 3 hari, batuk, pilek...">{{ old('keluhan') }}</textarea>
                            <p class="note">* Keluhan wajib diisi (maksimal 500 karakter)</p>

                             <button type="submit" class="btn-primary">
                                Reservasi
                            </button>
                        </div>

                       
                    </form>
                @endif
            @endif
        </div>

    </div>

    <!-- SIDEBAR KANAN -->
    <div class="sidebar-right">
        <div class="profile-card">
            <div class="profile-header" onclick="toggleProfileDropdown()">
                <h3>Profil Saya</h3>
                <span>
                    <svg width="23" height="12" viewBox="0 0 23 12" fill="none">
                        <path d="M11.5 12L23 1.61539L21.0833 0L11.5 8.53846L1.91667 0L0 1.61539L11.5 12Z" fill="white"/>
                    </svg>
                </span>
            </div>

            <div class="profile-dropdown" id="profileDropdown">
                <div class="profile-list" id="profileList"></div>
                <a href="{{ route('pasien.tambah_biodata') }}" class="add-member-btn">
                    + Tambah Anggota Keluarga
                </a>
            </div>

            <div class="profile-content">
                <x-avatar-pasien :pasien="$pasien_aktif" class="profile-image" />
                <div class="profile-info">
                    <h4 id="patientName">Loading...</h4>
                    <div class="phone" id="patientPhone">Loading...</div>
                    <div class="profile-details">
                        <div class="detail-item">
                            <label>Jenis Kelamin</label>
                            <value id="gender">-</value>
                        </div>
                        <div class="divider"></div>
                        <div class="detail-item">
                            <label>Umur</label>
                            <value id="age">-</value>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="reminders-card">
            <h3>Reminders</h3>
            <div id="remindersList">
                <div class="loading">Memuat pengingat...</div>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                Keluar Akun
            </button>
        </form>
    </div>

</div>

<script>
    // Data dari Laravel
    const pasienAktif = @json($pasien_aktif);
    const allPasiens = @json($pasiens);

    // Switch Tab Function
    function switchTab(tabName) {
        // Update navigation buttons
        document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');

        // Update content sections
        document.querySelectorAll('.content-section').forEach(section => section.classList.remove('active'));
        document.getElementById('tab-' + tabName).classList.add('active');

        // Update main content background
        const mainContent = document.getElementById('mainContent');
        mainContent.className = 'main-content bg-' + tabName;
    }

    // Format tanggal Indonesia
    function formatDate(date) {
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        return date.toLocaleDateString('id-ID', options);
    }

    // Set tanggal saat ini
    const currentDateStr = formatDate(new Date());
    document.getElementById('currentDate').textContent = currentDateStr;
    document.getElementById('currentDate2').textContent = currentDateStr;
    document.getElementById('currentDate3').textContent = currentDateStr;

    // Load Patient Profile
    function loadPatientProfile() {
        const pasien = pasienAktif;
        
        // Update foto/inisial
        const profileImage = document.getElementById('profileInitial');
        if (profileImage) {
            if (pasien.foto_path) {
                // Jika ada foto, tampilkan foto
                const fotoUrl = "{{ asset('') }}" + pasien.foto_path;
                profileImage.innerHTML = `
                    <img src="${fotoUrl}" 
                        alt="${pasien.nama_pasien}"
                        class="w-full h-full object-cover"
                        onerror="this.onerror=null; this.parentElement.innerHTML='${pasien.nama_pasien.charAt(0).toUpperCase()}';">
                `;
            } else {
                // Fallback ke inisial
                const initial = pasien.nama_pasien ? pasien.nama_pasien.charAt(0).toUpperCase() : '?';
                profileImage.textContent = initial;
            }
        }
        
        // Update data lainnya (tetap sama)
        document.getElementById('patientName').textContent = pasien.nama_pasien || '-';
        document.getElementById('patientPhone').textContent = pasien.no_telepon || '-';
        
        const gender = pasien.jenis_kelamin === 'Laki-laki' ? 'Laki-laki' : 
                    (pasien.jenis_kelamin === 'Perempuan' ? 'Perempuan' : '-');
        document.getElementById('gender').textContent = gender;
        
        if (pasien.tanggal_lahir_pasien) {
            const birthDate = new Date(pasien.tanggal_lahir_pasien);
            const age = new Date().getFullYear() - birthDate.getFullYear();
            document.getElementById('age').textContent = age + ' Tahun';
        } else {
            document.getElementById('age').textContent = '-';
        }
    }

    // Toggle Profile Dropdown
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        dropdown.classList.toggle('active');
        
        if (dropdown.classList.contains('active')) {
            loadProfileList();
        }
    }

    // Load Profile List
    function loadProfileList() {
        const profileList = document.getElementById('profileList');
        let html = '';
        
        allPasiens.forEach(pasien => {
            const isActive = pasien.id === pasienAktif.id ? 'active' : '';
            const badge = pasien.is_primary ? '<span class="badge">Utama</span>' : '';
            
            // Avatar untuk list
            let avatar = '';
            if (pasien.foto_path) {
                const fotoUrl = "{{ asset('') }}" + pasien.foto_path;
                avatar = `<img src="${fotoUrl}" class="w-8 h-8 rounded-full object-cover" alt="${pasien.nama_pasien}">`;
            } else {
                const initial = pasien.nama_pasien.charAt(0).toUpperCase();
                avatar = `<div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-sm font-bold">${initial}</div>`;
            }
            
            html += `
                <form action="{{ route('pasien.ganti_profil', '') }}/${pasien.id}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="profile-item ${isActive}">
                        ${avatar}
                        <span>${pasien.nama_pasien}</span>
                        ${badge}
                    </button>
                </form>
            `;
        });
        
        profileList.innerHTML = html;
    }

    // Load Reminders
    function loadReminders() {
        const remindersList = document.getElementById('remindersList');
        
        @if(isset($reminder_aktif))
            remindersList.innerHTML = `
                <div class="reminder-item">
                    <strong>Antrian Hampir Tiba!</strong>
                    <small>Nomor Anda: #{{ $reminder_aktif->nomor_antrian }}<br>
                    Sisa {{ $reminder_aktif->selisih }} antrian lagi</small>
                </div>
            `;
        @else
            remindersList.innerHTML = '<div class="loading">Tidak ada pengingat</div>';
        @endif
    }

    // Generate Calendar
    function generateCalendar() {
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth();
        const currentDate = today.getDate();

        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        document.getElementById('calendarMonth').textContent = monthNames[month];

        const currentDay = today.getDay();
        const dayNames = document.querySelectorAll('.day-name');

        let adjustedIndex = currentDay - 1;
        if (adjustedIndex < 0) adjustedIndex = 6;

        dayNames.forEach((day, index) => {
            if (index === adjustedIndex) {
                day.classList.add('current');
            }
        });

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const daysInPrevMonth = new Date(year, month, 0).getDate();

        const calendarDates = document.getElementById('calendarDates');
        let html = '';

        const startDay = firstDay === 0 ? 6 : firstDay - 1;
        for (let i = startDay; i > 0; i--) {
            html += `<div class="date-cell prev-month">${daysInPrevMonth - i + 1}</div>`;
        }

        for (let i = 1; i <= daysInMonth; i++) {
            const isToday = i === currentDate ? 'current' : '';
            html += `<div class="date-cell ${isToday}">${i}</div>`;
        }

        const remainingCells = 42 - (startDay + daysInMonth);
        for (let i = 1; i <= remainingCells; i++) {
            html += `<div class="date-cell next-month">${i}</div>`;
        }

        calendarDates.innerHTML = html;
    }

    // Show Alert Messages
    @if(session('success'))
        showAlert('{{ session("success") }}', 'success');
    @endif

    @if($errors->any())
        showAlert('{{ $errors->first() }}', 'error');
    @endif

    function showAlert(message, type) {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type}`;
        alert.textContent = message;
        document.body.appendChild(alert);

        setTimeout(() => {
            alert.remove();
        }, 3000);
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('profileDropdown');
        const header = document.querySelector('.profile-header');
        
        if (dropdown.classList.contains('active') && 
            !dropdown.contains(event.target) && 
            !header.contains(event.target)) {
            dropdown.classList.remove('active');
        }
    });

    // Initialize
    window.onload = function() {
        loadPatientProfile();
        loadReminders();
        generateCalendar();
    };
</script>
@endsection
