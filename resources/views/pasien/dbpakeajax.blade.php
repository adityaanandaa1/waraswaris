<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pasien</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #CEDEFF;
            overflow: hidden;
            height: 100vh;
        }

        .container {
            display: flex;
            height: 100vh;
            max-width: 100vw;
        }

        /* Sidebar Navigation */
        .sidebar {
            position: fixed;
            right: 558.5px;
            top: 65%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 0;
            z-index: 100;
        }

        .nav-item {
            width: 42px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0 15px 15px 0;
            cursor: pointer;
            transition: all 0.3s;
        }

        .nav-item.dashboard {
            background: #FFFFFF;
            color: #464646;
            box-shadow: 10px 0px 7px rgba(0, 0, 0, 0.1);
            z-index: 200;
        }

        .nav-item.riwayat {
            background: #587EF4;
            color: #FFFFFF;
            box-shadow: inset 10px 0 20px rgba(0, 0, 0, 0.3);
        }

        .nav-item.reservasi {   
            background: #3B41AE;
            color: #FFFFFF;
            box-shadow: inset 10px 0 20px rgba(0, 0, 0, 0.3);
        }

        .nav-item span {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            font-weight: 700;
            font-size: 12px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            background: #FFFFFF;
            border-radius: 0 20px 20px 0;
            padding: 15px 50px;
            box-shadow: 10px 0px 10px rgba(0, 0, 0, 0.25);
            margin-right: 42px;
            max-width: calc(100vw - 600px);
            overflow: hidden;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .header-left h1 {
            font-size: 18px;
            font-weight: 700;
            color: #464646;
            margin-bottom: 2px;
        }

        .header-left .date {
            font-size: 11px;
            color: #464646;
        }


        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, #5A81FA 0%, #587EF4 100%);
            border-radius: 15px;
            padding: 15px 20px;
            margin-top: 50px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            box-shadow: 0px 0px 20px 5px rgba(0, 0, 0, 0.15);
            height: 120px;
            position: relative;
            overflow: visible;
        }

        .welcome-image {
            width:300px;
            height: auto;
            position: absolute;
            left: -20px;
            top: -75px;
            z-index: 1;
        }

        .welcome-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .welcome-text {
            flex: 1;
            color: #FFFFFF;
            text-align: left;
            padding-left: 280px;
        }

        .welcome-text h2 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .welcome-text p {
            font-size: 14px;
            font-weight: 300;
            line-height: 20px;
        }

        /* Calendar */
        .calendar-container {
            background: rgba(206, 222, 255, 0.5);
            border-radius: 20px;
            padding: 20px 40px;
            box-shadow: 0px 4px 15px 5px rgba(0, 0, 0, 0.25);
            height: calc(100vh - 278px);
            overflow: hidden;
            margin-top: 20px;
        }

        .calendar-header {
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            color: #5A81FA;
            margin-bottom: 20px;
        }

        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            text-align: center;
            margin-bottom: 10px;
        }

        .day-name {
            font-size: 16px;
            color: #464646;
            font-weight: 550;
        }

        .day-name.current {
            color: #5A81FA;
        }

        .calendar-dates {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 6px;
            text-align: center;
        }

        .date-cell {
            font-size: 16px;
            padding: 10px;
            cursor: pointer;
            transition: all 0.3s;
            border-radius: 30px;
        }

        .date-cell.prev-month,
        .date-cell.next-month {
            color: rgba(0, 0, 0, 0.5);
            font-weight: 100;
        }

        .date-cell.current {
            background: #5A81FA;
            color: #FFFFFF;
            font-weight: 600;
        }

        .date-cell:hover:not(.prev-month):not(.next-month):not(.date-cell.current) {
            background: rgba(90, 129, 250, 0.2);
        }

        /* Right Panel */
        .right-panel {
            width: 450px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            height: 100vh;
            margin: 0px 0px 20px 50px;
        }

        /* Profile Card */
        .profile-card {
            background: #FFFFFF;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0px 0px 20px 5px rgba(0, 0, 0, 0.15);
            padding-bottom: 15px;
        }

        .profile-header {
            background: #5A81FA;
            color: #FFFFFF;
            padding: 10px 40px;
            border-radius: 20px;
            margin: -20px -20px 12px -40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 450px;
            height: 50px;
        }

        .profile-header h3 {
            font-size: 16px;
            font-weight: 600;
        }

        .profile-header span {
            cursor: pointer;
            font-size: 14px;
        }

        .profile-content {
            display: flex;
            gap: 0px;
        }

        .profile-image {
            width: 60px;
            height: 60px;
            margin-left:20px;
            margin-top:0px;
            border-radius: 50%;
            background: #D9D9D9;
            flex-shrink: 0;
        }

        .profile-info h4 {
            margin-top: 13px;
            margin-left: 20px;
            font-size: 16px;
            font-weight: 600;
            color: #1F4DD9;
            margin-bottom: 3px;
        }

        .profile-info .phone {
            margin-left: 20px;
            font-size: 10px;
            color: #464646;
            margin-bottom: 10px;
        }

        .profile-details {
            display: flex;
            align-items: center;       
            justify-content: center;   
            margin-top: 25px;
            margin-left: -50px;
            gap: 40px; 
        }

        .detail-item {
            display: flex;
            flex-direction: column;    
            align-items: center;       
            justify-content: center;   
            text-align: center;
            flex: none;               
            width: 100px;             
        }

        .detail-item label {
            font-size: 10px;
            color: #464646;
            margin-bottom: 3px;
        }

        .detail-item value {
            font-size: 10px;
            font-weight: 600;
            color: #464646;
        }

        .divider {
            width: 1px;
            height: 25px;
            background: #464646;
            align-self: center;
            margin: 0 20px;            
        }

        /* Reminders */
        .reminders-card {
            background: #FFFFFF;
            border-radius: 15px;
            padding: 12px;
            box-shadow: 0px 0px 25px 3px rgba(0, 0, 0, 0.25);
            flex: 1;
            overflow-y: auto;
        }

        .reminders-card h3 {
            font-size: 18px;
            font-weight: 600;
            color: #587EF4;
            text-align: center;
            margin-bottom: 12px;
        }

        .reminder-item {
            padding: 10px;
            background: rgba(90, 129, 250, 0.1);
            border-radius: 8px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 11px;
        }

        .reminder-item:hover {
            background: rgba(90, 129, 250, 0.2);
        }

        .reminder-item strong {
            display: block;
            margin-bottom: 3px;
        }

        .reminder-item small {
            font-size: 9px;
            color: #666;
        }

        /* Logout Button */
        .logout-btn {
            background: rgba(255, 0, 4, 0.5);
            border-radius: 35px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: inset 0px 4px 15px rgba(0, 0, 0, 0.25);
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: rgba(255, 0, 4, 0.7);
        }

        .logout-btn .icon {
            font-size: 18px;
        }

        .logout-btn .text {
            font-size: 16px;
            font-weight: 700;
            color: #FFFFFF;
        }

        .loading {
            text-align: center;
            padding: 15px;
            color: #5A81FA;
            font-size: 11px;
        }

        /* Scrollbar styling */
        .reminders-card::-webkit-scrollbar {
            width: 6px;
        }

        .reminders-card::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .reminders-card::-webkit-scrollbar-thumb {
            background: #5A81FA;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <div class="nav-item dashboard">
                <span>Dashboard</span>
            </div>
            <div class="nav-item riwayat" onclick="loadHistory()">
                <span>Riwayat</span>
            </div>
            <div class="nav-item reservasi" onclick="loadReservation()">
                <span>Reservasi</span>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <div class="header-left">
                    <h1>Dashboard</h1>
                    <div class="date" id="currentDate"></div>
                </div>
            </div>

            <div class="welcome-banner">
                <div class="welcome-image">
                    <img src="{{ asset('images/dbpasien.png') }}" alt="Animasi Dokter">
                </div>
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

        <!-- Right Panel -->
        <div class="right-panel">
            <div class="profile-card">
                <div class="profile-header">
                    <h3>Profil Saya</h3>
                    <span onclick="editProfile()">
                        <svg width="23" height="12" viewBox="0 0 23 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.5 12L23 1.61539L21.0833 9.53674e-07L11.5 8.53846L1.91667 9.53674e-07L0 1.61539L11.5 12Z" fill="white"/>
                        </svg>
                    </span>
                </div>
                <div class="profile-content">
                    <div class="profile-image"></div>
                    <div class="profile-info">
                        <h4 id="patientName">Loading...</h4>
                        <div class="phone" id="patientPhone">Loading...</div>
                        <div class="profile-details">
                            <div class="detail-item">
                                <label>Golongan Darah</label>
                                <value id="bloodType">-</value>
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

            <div class="logout-btn" onclick="logout()">
                <span class="text">Keluar Akun</span>
            </div>
        </div>
    </div>

    <script>
        // Simulasi data dari API
        const API_BASE = 'https://api.example.com'; // Ganti dengan API Anda

        // Fungsi untuk format tanggal
        function formatDate(date) {
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }

        // Set tanggal saat ini
        document.getElementById('currentDate').textContent = formatDate(new Date());

        // Load Patient Profile dengan AJAX
        function loadPatientProfile() {
            // Simulasi AJAX call
            // Dalam implementasi nyata, gunakan fetch atau XMLHttpRequest
            setTimeout(() => {
                const data = {
                    name: 'A. Waris',
                    phone: '+62 8123 4578 9012 11',
                    bloodType: 'A+',
                    age: '25 Tahun'
                };

                document.getElementById('patientName').textContent = data.name;
                document.getElementById('patientPhone').textContent = data.phone;
                document.getElementById('bloodType').textContent = data.bloodType;
                document.getElementById('age').textContent = data.age;
            }, 500);
        }

        // Load Reminders dengan AJAX
        function loadReminders() {
            setTimeout(() => {
                const reminders = [
                    { id: 1, title: 'Kontrol Rutin', date: '12 Oktober 2024', time: '10:00' },
                    { id: 2, title: 'Cek Lab', date: '15 Oktober 2024', time: '14:00' },
                    { id: 3, title: 'Konsultasi', date: '20 Oktober 2024', time: '09:00' },
                    { id: 4, title: 'Vaksinasi', date: '22 Oktober 2024', time: '11:00' },
                    { id: 5, title: 'Terapi Fisik', date: '25 Oktober 2024', time: '15:00' }
                ];

                const remindersList = document.getElementById('remindersList');
                remindersList.innerHTML = reminders.map(r => `
                    <div class="reminder-item" onclick="viewReminder(${r.id})">
                        <strong>${r.title}</strong><br>
                        <small>${r.date} - ${r.time}</small>
                    </div>
                `).join('');
            }, 500);
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

        // ✅ HAPUS deklarasi ulang 'const today'
        const currentDay = today.getDay(); // 0 = Minggu, 1 = Senin, dst
        const dayNames = document.querySelectorAll('.day-name');

        // Karena kamu urutannya mulai dari Senin, kita ubah indeksnya sedikit
        let adjustedIndex = currentDay - 1;
        if (adjustedIndex < 0) adjustedIndex = 6; // kalau Minggu, jadi terakhir

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

        // Previous month days
        const startDay = firstDay === 0 ? 6 : firstDay - 1;
        for (let i = startDay; i > 0; i--) {
            html += `<div class="date-cell prev-month">${daysInPrevMonth - i + 1}</div>`;
        }

        // Current month days
        for (let i = 1; i <= daysInMonth; i++) {
            const isToday = i === currentDate ? 'current' : '';
            html += `<div class="date-cell ${isToday}" onclick="selectDate(${i})">${i}</div>`;
        }

        // Next month days
        const remainingCells = 42 - (startDay + daysInMonth);
        for (let i = 1; i <= remainingCells; i++) {
            html += `<div class="date-cell next-month">${i}</div>`;
        }

        calendarDates.innerHTML = html;
    }


        // Event Handlers
        function selectDate(date) {
            console.log('Selected date:', date);
            // AJAX call untuk load appointments di tanggal tersebut
            alert(`Tanggal ${date} dipilih. Di sini Anda bisa load data appointment dengan AJAX.`);
        }

        function loadHistory() {
            console.log('Loading history...');
            alert('Memuat halaman Riwayat...');
            // AJAX call untuk load riwayat
        }

        function loadReservation() {
            console.log('Loading reservation...');
            alert('Memuat halaman Reservasi...');
            // AJAX call untuk load reservasi
        }


        function viewReminder(id) {
            console.log('View reminder:', id);
            alert(`Melihat detail reminder ID: ${id}`);
            // AJAX call untuk detail reminder
        }

        function editProfile() {
            console.log('Edit profile...');
            alert('Membuka form edit profil...');
            // AJAX call untuk edit profil
        }

        function logout() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                console.log('Logging out...');
                alert('Logout berhasil!');
                // AJAX call untuk logout
                // window.location.href = '/login';
            }
        }

        // Initialize
        window.onload = function() {
            loadPatientProfile();
            loadReminders();
            generateCalendar();
        };
    </script>
</body>
</html>