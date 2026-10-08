@extends('layouts.resepsionis')

@section('title', 'Dashboard -  WarasWaris')

@section('content')

@php($hari_ini = $hari_ini ?? \Carbon\Carbon::today()->locale('id'))
@php($nama_hari = $nama_hari ?? $hari_ini->translatedFormat('l'))
@php($isLibur = $jadwal && !$jadwal->is_active)

<div class="dashboard-doctor">
    <div class="dashboard-welcome-section">
        <div class="dashboard-welcome-date">
            <svg width="23" height="22" viewBox="0 0 28 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26.4685 3.49949H22.9686V6.12445C22.9686 6.37724 22.9188 6.62755 22.8221 6.8611C22.7253 7.09465 22.5835 7.30686 22.4048 7.48561C22.226 7.66436 22.0138 7.80615 21.7803 7.90289C21.5467 7.99963 21.2964 8.04942 21.0436 8.04942C20.7908 8.04942 20.5405 7.99963 20.307 7.90289C20.0734 7.80615 19.8612 7.66436 19.6825 7.48561C19.5037 7.30686 19.3619 7.09465 19.2652 6.8611C19.1685 6.62755 19.1187 6.37724 19.1187 6.12445V3.49949H8.92508V6.12445C8.92508 6.63498 8.72227 7.1246 8.36127 7.48561C8.00027 7.84661 7.51065 8.04942 7.00011 8.04942C6.48958 8.04942 5.99996 7.84661 5.63895 7.48561C5.27795 7.1246 5.07514 6.63498 5.07514 6.12445V3.49949H1.5752C1.36697 3.49712 1.16039 3.53653 0.967657 3.61539C0.774927 3.69425 0.599969 3.81096 0.453129 3.95862C0.30629 4.10627 0.190552 4.28188 0.11276 4.47504C0.0349679 4.6682 -0.00329822 4.87501 0.000224916 5.08321V24.6654C-0.00325142 24.8699 0.0336005 25.0732 0.108676 25.2635C0.183751 25.4537 0.295579 25.6274 0.437772 25.7745C0.579965 25.9215 0.749737 26.0391 0.937393 26.1206C1.12505 26.202 1.32691 26.2457 1.53145 26.2491H26.4685C26.6731 26.2457 26.875 26.202 27.0626 26.1206C27.2503 26.0391 27.42 25.9215 27.5622 25.7745C27.7044 25.6274 27.8162 25.4537 27.8913 25.2635C27.9664 25.0732 28.0033 24.8699 27.9998 24.6654V5.08321C28.0033 4.87868 27.9664 4.67546 27.8913 4.48516C27.8162 4.29487 27.7044 4.12123 27.5622 3.97416C27.42 3.82709 27.2503 3.70948 27.0626 3.62803C26.875 3.54659 26.6731 3.50291 26.4685 3.49949ZM7.00011 20.9992H5.25014V19.2492H7.00011V20.9992ZM7.00011 16.6243H5.25014V14.8743H7.00011V16.6243ZM7.00011 12.2493H5.25014V10.4994H7.00011V12.2493ZM12.25 20.9992H10.5001V19.2492H12.25V20.9992ZM12.25 16.6243H10.5001V14.8743H12.25V16.6243ZM12.25 12.2493H10.5001V10.4994H12.25V12.2493ZM17.4999 20.9992H15.75V19.2492H17.4999V20.9992ZM17.4999 16.6243H15.75V14.8743H17.4999V16.6243ZM17.4999 12.2493H15.75V10.4994H17.4999V12.2493ZM22.7499 20.9992H20.9999V19.2492H22.7499V20.9992ZM22.7499 16.6243H20.9999V14.8743H22.7499V16.6243ZM22.7499 12.2493H20.9999V10.4994H22.7499V12.2493Z" fill="white"/>
                <path d="M7.0002 6.99977C7.23226 6.99977 7.45482 6.90758 7.61891 6.74349C7.783 6.5794 7.87519 6.35684 7.87519 6.12478V0.874864C7.87519 0.642803 7.783 0.420247 7.61891 0.256155C7.45482 0.0920637 7.23226 -0.00012207 7.0002 -0.00012207C6.76814 -0.00012207 6.54558 0.0920637 6.38149 0.256155C6.2174 0.420247 6.12521 0.642803 6.12521 0.874864V6.12478C6.12521 6.35684 6.2174 6.5794 6.38149 6.74349C6.54558 6.90758 6.76814 6.99977 7.0002 6.99977Z" fill="white"/>
                <path d="M20.9999 6.99977C21.232 6.99977 21.4545 6.90758 21.6186 6.74349C21.7827 6.5794 21.8749 6.35684 21.8749 6.12478V0.874864C21.8749 0.642803 21.7827 0.420247 21.6186 0.256155C21.4545 0.0920637 21.232 -0.00012207 20.9999 -0.00012207C20.7679 -0.00012207 20.5453 0.0920637 20.3812 0.256155C20.2171 0.420247 20.1249 0.642803 20.1249 0.874864V6.12478C20.1249 6.35684 20.2171 6.5794 20.3812 6.74349C20.5453 6.90758 20.7679 6.99977 20.9999 6.99977Z" fill="white"/>
            </svg>
            <p id="today" class="dashboard-welcome-today"></p>
        </div>

        <div class="dashboard-welcome-greeting">
            <div class="dashboard-greeting-text">
                <div class="dashboard-greeting-title">
                    <h1 class="dashboard-greeting-title-subtitle">Selamat Datang</h1>
                    <h1 class="dashboard-greeting-title-subtitle"></h1>
                </div>
                <p class="dashboard-greeting-subtitle">Semoga harimu indah!</p>
            </div>
            <div class="dashboard-greeting-illustration">
            <img src="{{ asset('images/dokter.png') }}" alt="Dokter Waris" width="330">
            </div>
        </div>
    </div>

    <div class="dashboard-profile">
        <div class="dashboard-profile-header">
            <h1 class="dashboard-profile-title">Profil Dokter</h1>
        </div>

        <div class="dashboard-profile-body">
            <div class="dashboard-profile-photo">

            </div>
            <div class="dashboard-profile-info">
                <div class="dashboard-profile-identity">
                    <p class="dashboard-profile-name">
                        {{ optional($dokter_aktif)->nama_dokter ?? '-' }}
                    </p>
                    <p class="dashboard-profile-profession">Dokter Umum</p>
                    <div class="dashboard-profile-location">
                        <i class="ri-map-pin-2-fill" style="font-size: 14px; color: #5A81FA;"></i>
                        <p class="dashboard-location-text">Kalisat,  Jember</p>
                    </div>
                </div>

                <div class="dashboard-profile-details">
                    <div class="dashboard-detail-item">
                        <p class="dashboard-detail-label">Tanggal Lahir</p>
                        <p class="dashboard-detail-value">
                            {{ optional($dokter_aktif)->tanggal_lahir_dokter }}
                        </p>
                    </div>
                    <h1 class="dashboard-profile-separator">|</h1>
                    <div class="dashboard-detail-item">
                        <p class="dashboard-detail-label">SIP</p>
                        <a href="{{ route('resepsionis.sip.download') }}" class="dashboard-download-sip">
                            Unduh
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-schedule">
        <h2 class="dashboard-schedule-title">Jam Praktik</h2>
        <div class="dashboard-dropdown-schedule">
            <form id="formJadwal" action="{{ route('resepsionis.dashboard') }}" method="GET">
                <div>
                    <x-datepicker name="tanggal">
                        <x-slot:icon>
                            <svg width="11" height="7" viewBox="0 0 11 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.26783 6.75L3.8147e-06 0L10.5357 0L5.26783 6.75Z" fill="#5A81FA"/>
                            </svg>
                        </x-slot:icon>
                    </x-datepicker>
                </div>
            </form>
        </div>
        @if($jadwal)
            @if($isLibur)
            <div class="dashboard-schedule-time">
                <div class="dashboard-time-item">
                    <h1 class="dashboard-time-label">Status</h1>
                    <p class="dashboard-time-value" style="color:#ef4444;">Libur</p>
                </div>
                <h1 class="dashboard-time-separator">-</h1>
                <div class="dashboard-time-item">
                    <h1 class="dashboard-time-label">Tanggal</h1>
                    <p class="dashboard-time-value">{{ $hari_ini->translatedFormat('d F Y') }}</p>
                </div>
            </div>
            @else
            <div class="dashboard-schedule-time">
                <div class="dashboard-time-item">
                    <h1 class="dashboard-time-label">Buka</h1>
                    <p class="dashboard-time-value">{{ $jadwal->jam_mulai ?? '-' }}</p>
                </div>
                <h1 class="dashboard-time-separator">-</h1>
                <div class="dashboard-time-item">
                    <h1 class="dashboard-time-label">Tutup</h1>
                    <p class="dashboard-time-value">{{ $jadwal->jam_selesai ?? '-' }}</p>
                </div>
            </div>
            @endif
        @else
        <div class="time-item">
            <h1 class="time-label">Belum Ada Jadwal</h1>
            <p class="time-value">{{ $nama_hari }}</p>
        </div>
        @endif

        <button onclick="document.getElementById('modalJadwal').classList.remove('hidden')" type="button" class="dashboard-btn-schedule">
            Atur Ulang
        </button>
    </div>

    <div class="dashboard-queue">
        <h1 class="dashboard-queue-title">Nomor Antrean Berjalan</h1>
        <h1 class="dashboard-queue-number">{{ $antrian->nomor_sekarang ?? 0 }}</h1>
    </div>

    <div class="dashboard-report">
        <h1 class="dashboard-report-title">Statistik Laporan</h1>
        <div class="dashboard-report-row">
            <div class="dashboard-report-item">
                <h1 class="dashboard-report-number-1">{{ $total_reservasi }}</h1>
                <h2 class="dashboard-report-label">Total Reservasi</h2>
            </div> 

            <div class="dashboard-report-item">
                <h1 class="dashboard-report-number-2">{{ $pasien_terlayani }}</h1>
                <h2 class="dashboard-report-label">Pasien Terlayani</h2>
            </div>

            <div class="dashboard-report-item">
                <h1 class="dashboard-report-number-3">{{ $pasien_batal }}</h1>
                <h2 class="dashboard-report-label">Pasien Tidak Hadir</h2>
            </div>
        </div>
    </div>

    <div class="dashboard-calendar">
        <div class="dashboard-calendar-header">
            <h1 class="dashboard-calendar-title">Kalender Saya</h1>
            <x-monthpicker>
                <x-slot:icon>
                    <svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.66699 0.999979L0.333659 0.999979L4.00033 7.33331" fill="white"/>
                    </svg>
                </x-slot:icon>
            </x-monthpicker>
        </div>

        <div class="dashboard-calendar-body">
            <div class="dashboard-calendar-month" id="calendarMonth"></div>
            <div class="dashboard-calendar-weekdays">
                <div class="dashboard-weekday">Senin</div>
                <div class="dashboard-weekday">Selasa</div>
                <div class="dashboard-weekday">Rabu</div>
                <div class="dashboard-weekday">Kamis</div>
                <div class="dashboard-weekday">Jumat</div>
                <div class="dashboard-weekday">Sabtu</div>
                <div class="dashboard-weekday">Minggu</div>
            </div>
            <div class="dashboard-calendar-dates" id="calendarDates"></div>
        </div>
    </div>
</div>

<!-- Modal Atur Jadwal -->
<div id="modalJadwal" class="dashboard-modal-overlay hidden">
    <div class="dashboard-modal-container">
        <div class="dashboard-modal-header">
            <button onclick="document.getElementById('modalJadwal').classList.add('hidden')" 
                    class="dashboard-modal-close-btn">
                <svg class="dashboard-icon-close" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('resepsionis.update.jadwal') }}"  method="POST" class="dashboard-modal-form">
            @csrf
            @method('PUT')

            <div class="dashboard-form-group-date">
                <div>
                    <x-datepicker name="tanggal">
                        <x-slot:icon>
                            <svg width="23" height="21" viewBox="0 0 23 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21.1751 2.79989H18.3751V4.89987C18.3751 5.10211 18.3352 5.30236 18.2578 5.4892C18.1805 5.67604 18.067 5.84581 17.924 5.98881C17.781 6.13181 17.6112 6.24525 17.4244 6.32264C17.2376 6.40003 17.0373 6.43986 16.8351 6.43986C16.6328 6.43986 16.4326 6.40003 16.2458 6.32264C16.0589 6.24525 15.8891 6.13181 15.7461 5.98881C15.6031 5.84581 15.4897 5.67604 15.4123 5.4892C15.3349 5.30236 15.2951 5.10211 15.2951 4.89987V2.79989H7.14014V4.89987C7.14014 5.3083 6.97789 5.70001 6.68908 5.98881C6.40028 6.27762 6.00858 6.43986 5.60015 6.43986C5.19172 6.43986 4.80001 6.27762 4.51121 5.98881C4.2224 5.70001 4.06016 5.3083 4.06016 4.89987V2.79989H1.26017C1.09359 2.79799 0.928318 2.82952 0.774133 2.89261C0.619948 2.9557 0.47998 3.04907 0.362507 3.16719C0.245035 3.28532 0.152443 3.4258 0.0902088 3.58033C0.0279746 3.73486 -0.00263861 3.90031 0.000179935 4.06688V19.7328C-0.00260116 19.8964 0.0268807 20.059 0.0869414 20.2112C0.147002 20.3635 0.236465 20.5024 0.350221 20.62C0.463977 20.7377 0.599796 20.8318 0.749922 20.8969C0.900048 20.9621 1.06154 20.997 1.22517 20.9998H21.1751C21.3387 20.997 21.5002 20.9621 21.6503 20.8969C21.8004 20.8318 21.9363 20.7377 22.05 20.62C22.1638 20.5024 22.2532 20.3635 22.3133 20.2112C22.3733 20.059 22.4028 19.8964 22.4 19.7328V4.06688C22.4028 3.90325 22.3733 3.74067 22.3133 3.58843C22.2532 3.4362 22.1638 3.29728 22.05 3.17963C21.9363 3.06197 21.8004 2.96788 21.6503 2.90272C21.5002 2.83756 21.3387 2.80262 21.1751 2.79989ZM5.60015 16.7998H4.20015V15.3998H5.60015V16.7998ZM5.60015 13.2998H4.20015V11.8998H5.60015V13.2998ZM5.60015 9.79984H4.20015V8.39985H5.60015V9.79984ZM9.80012 16.7998H8.40013V15.3998H9.80012V16.7998ZM9.80012 13.2998H8.40013V11.8998H9.80012V13.2998ZM9.80012 9.79984H8.40013V8.39985H9.80012V9.79984ZM14.0001 16.7998H12.6001V15.3998H14.0001V16.7998ZM14.0001 13.2998H12.6001V11.8998H14.0001V13.2998ZM14.0001 9.79984H12.6001V8.39985H14.0001V9.79984ZM18.2001 16.7998H16.8001V15.3998H18.2001V16.7998ZM18.2001 13.2998H16.8001V11.8998H18.2001V13.2998ZM18.2001 9.79984H16.8001V8.39985H18.2001V9.79984Z" fill="#464646"/>
                                <path d="M5.60039 5.59997C5.78604 5.59997 5.96408 5.52622 6.09536 5.39494C6.22663 5.26367 6.30038 5.08562 6.30038 4.89997V0.699996C6.30038 0.514345 6.22663 0.336299 6.09536 0.205024C5.96408 0.0737493 5.78604 0 5.60039 0C5.41474 0 5.23669 0.0737493 5.10541 0.205024C4.97414 0.336299 4.90039 0.514345 4.90039 0.699996V4.89997C4.90039 5.08562 4.97414 5.26367 5.10541 5.39494C5.23669 5.52622 5.41474 5.59997 5.60039 5.59997Z" fill="#464646"/>
                                <path d="M16.7996 5.59997C16.9853 5.59997 17.1633 5.52622 17.2946 5.39494C17.4259 5.26367 17.4996 5.08562 17.4996 4.89997V0.699996C17.4996 0.514345 17.4259 0.336299 17.2946 0.205024C17.1633 0.0737493 16.9853 0 16.7996 0C16.614 0 16.4359 0.0737493 16.3046 0.205024C16.1734 0.336299 16.0996 0.514345 16.0996 0.699996V4.89997C16.0996 5.08562 16.1734 5.26367 16.3046 5.39494C16.4359 5.52622 16.614 5.59997 16.7996 5.59997Z" fill="#464646"/>
                            </svg>
                        </x-slot:icon>
                    </x-datepicker>
                </div>
            </div>

            <div class="dashboard-toggle-group">
                <input type="radio" id="buka" name="status" value="buka" {{ !$isLibur ? 'checked' : '' }} onchange="toggleJamInput()">
                <label for="buka">Buka</label>
                            
                <input type="radio" id="libur" name="status" value="libur" {{ $isLibur ? 'checked' : '' }} onchange="toggleJamInput()">
                <label for="libur">Libur</label>

                <span class="dashboard-toggle-slider"></span>
            </div>

            <div id="jamInputs" class="dashboard-form-group {{ $isLibur ? 'hidden' : '' }}">
                <h1 class="dashboard-jam-title">Pilih Jam</h1>
                <div class="dashboard-time-inputs-grid">
                    <div class="dashboard-time-item-schedule">
                        <label class="dashboard-form-label">Buka</label>
                        <input type="time" name="jam_mulai"  value="{{ old('jam_mulai', $jadwal && $jadwal->jam_mulai ? \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') : '09:00') }}"
                            class="dashboard-form-input-jam">
                    </div>
                    <div class="dashboard-time-item-schedule">
                        <label class="dashboard-form-label">Tutup</label>
                        <input type="time" name="jam_selesai"  value="{{ old('jam_selesai', $jadwal && $jadwal->jam_selesai ? \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') : '21:00') }}"
                            class="dashboard-form-input-jam">
                    </div>
                </div>

                <button type="submit" class="dashboard-btn-submit-schedule">
                    Simpan
                </button>
            </div>

            <div id="catatanInput" class="dashboard-form-group {{ $isLibur ? '' : 'hidden' }}">
                <label class="dashboard-form-label">Catatan</label>
                <textarea name="catatan" rows="5" cols="40" 
                          class="dashboard-form-input dashboard-form-textarea"></textarea>
                <button type="submit" class="dashboard-btn-submit-schedule">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleJamInput() {
    const status = document.querySelector('input[name="status"]:checked').value;
    const jamInputs = document.getElementById('jamInputs');
    const catatanInput = document.getElementById('catatanInput');
    
    if (status === 'libur') {
        jamInputs.classList.add('hidden');
        catatanInput.classList.remove('hidden');
    } else {
        jamInputs.classList.remove('hidden');
        catatanInput.classList.add('hidden');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('input[name="status"]')) {
        toggleJamInput();
    }
});

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

    document.addEventListener('DOMContentLoaded', generateCalendar);
</script>
@endsection