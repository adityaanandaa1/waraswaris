document.querySelectorAll('.datepicker-wrapper').forEach(wrapper => {

    const dateInput = wrapper.querySelector('.datepicker-input');
    const calendarIcon = wrapper.querySelector('.datepicker-icon');
    const calendarPopup = wrapper.querySelector('.datepicker');
    const selectedDateInput = wrapper.querySelector('.datepicker-hidden');

    const monthYearElement = wrapper.querySelector('.datepicker-monthYear');
    const dateElement = wrapper.querySelector('.datepicker-dates');
    const prevBtn = wrapper.querySelector('.datepicker-prevBtn');
    const nextBtn = wrapper.querySelector('.datepicker-nextBtn');
    const daysRow = wrapper.querySelector('.datepicker-days');
    const monthGrid = wrapper.querySelector('.datepicker-month');
    const yearGrid = wrapper.querySelector('.datepicker-year');

    let currentDate = selectedDateInput && selectedDateInput.value
        ? new Date(selectedDateInput.value + 'T00:00:00')
        : new Date();
    let mode = 'date';

    const toggleCalendar = () => {
        calendarPopup.classList.toggle('hidden');
        if (!calendarPopup.classList.contains('hidden')) updateCalendar();
    };

    //DatePicker
    const updateCalendar = () => {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        monthGrid.classList.add("hidden");
        yearGrid.classList.add("hidden");
        dateElement.classList.remove("hidden");
        daysRow.classList.remove("hidden");
        prevBtn.style.visibility = "visible";
        nextBtn.style.visibility = "visible";

        const firstDayIndex = new Date(year, month, 1).getDay();
        const totalDays = new Date(year, month + 1, 0).getDate();
        const prevMonthDays = new Date(year, month, 0).getDate();

        const monthYearString = currentDate.toLocaleString('id-ID', { month: 'long', year: 'numeric' });
        monthYearElement.textContent = monthYearString;

        let datesHTML = '';
        const start = (firstDayIndex === 0 ? 6 : firstDayIndex - 1);

        for (let i = start; i > 0; i--) {
            datesHTML += `<div class="datepicker-date inactive">${prevMonthDays - i + 1}</div>`;
        }

        for (let i = 1; i <= totalDays; i++) {
            const formatted = `${year}-${String(month+1).padStart(2,'0')}-${String(i).padStart(2,'0')}`;

            const isSelected = selectedDateInput.value === formatted;
            const selectedClass = isSelected ? 'active' : '';

            const today = new Date();
            const isToday = today.getFullYear() === year && today.getMonth() === month && today.getDate() === i;
            const todayClass = isToday ? 'today' : '';

            datesHTML += `<div class="datepicker-date cursor-pointer ${selectedClass} ${todayClass}" data-date="${formatted}">${i}</div>`;
        }

        const lastDayIndex = new Date(year, month + 1, 0).getDay();
        const end = 7 - (lastDayIndex === 0 ? 7 : lastDayIndex);

        for (let i = 1; i <= end; i++) {
            datesHTML += `<div class="datepicker-date inactive">${i}</div>`;
        }

        dateElement.innerHTML = datesHTML;

        dateElement.querySelectorAll('[data-date]').forEach(div => {
            div.addEventListener('click', () => {
                const selected = div.dataset.date;

                dateInput.value = new Date(selected).toLocaleDateString('id-ID', {
                    day:'2-digit', month:'long', year:'numeric'
                });

                selectedDateInput.value = selected;
                calendarPopup.classList.add('hidden');
                
                const form = wrapper.closest("form");
                if (form) {
                    const url = new URL(form.action);
                    url.searchParams.set(form.querySelector(".datepicker-hidden").name, selected);

                    fetch(url, { method: "GET" })
                        .then(res => res.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, "text/html");
                        
                            const newSchedule = doc.querySelector(".dashboard-schedule-time") 
                                             || doc.querySelector(".time-item");
                            const oldSchedule = document.querySelector(".dashboard-schedule-time") 
                                             || document.querySelector(".time-item");
                            if (newSchedule && oldSchedule) {
                                oldSchedule.replaceWith(newSchedule);
                            }
                        });
                }
                dateElement.querySelectorAll('[data-date].active').forEach(el => el.classList.remove('active'));
                div.classList.add('active');
            });
        });
    };

    //MonthPicker
    function showMonthPicker() {
        mode = "month";

        dateElement.classList.add("hidden");
        daysRow.classList.add("hidden");
        yearGrid.classList.add("hidden");
        monthGrid.classList.remove("hidden");
        prevBtn.style.visibility = "visible";
        nextBtn.style.visibility = "visible";

        const months = [
            "Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"
        ];

        monthGrid.innerHTML = "";

        months.forEach((m, i) => {
            const div = document.createElement("div");
            div.textContent = m;
            div.classList.add("p-1","cursor-pointer","rounded");

            if (i === currentDate.getMonth()) div.classList.add("datepicker-month-active");
                div.onclick = () => {
                    monthGrid.querySelectorAll('.datepicker-month-active').forEach(el => el.classList.remove('datepicker-month-active'));
                    currentDate.setMonth(i);

                    div.classList.add('datepicker-month-active');
                    mode = "date";
                    updateCalendar();
                };
            
                monthGrid.appendChild(div);
        });

        monthYearElement.textContent = currentDate.getFullYear();
    }

    //YearPicker
    function showYearPicker() {
        mode = "year";

        monthGrid.classList.add("hidden");
        dateElement.classList.add("hidden");
        daysRow.classList.add("hidden");
        yearGrid.classList.remove("hidden");
        prevBtn.style.visibility = "hidden";
        nextBtn.style.visibility = "hidden";

        yearGrid.innerHTML = "";

        for (let y = 2000; y <= 2050; y++) {
            const div = document.createElement("div");
            div.textContent = y;
            div.classList.add("p-1","cursor-pointer","rounded");

            if (y === currentDate.getFullYear()) div.classList.add("datepicker-year-active");

            div.onclick = () => {
                yearGrid.querySelectorAll('.datepicker-year-active')
                .forEach(el => el.classList.remove('datepicker-year-active'));
                
                div.classList.add('datepicker-year-active');
                currentDate.setFullYear(y);
                yearGrid.classList.add("hidden");

                showMonthPicker();
            };
            yearGrid.appendChild(div);
        }
        monthYearElement.textContent = currentDate.getFullYear();
        const active = yearGrid.querySelector('.datepicker-year-active');
        if (active) active.scrollIntoView({ block: 'center' });
    }

    //Event On-Click Button
    monthYearElement.onclick = () => {
        if (mode === "date") showMonthPicker();
        else if (mode === "month") showYearPicker();
    };

    prevBtn.onclick = () => {
        if (mode === "date") {
            currentDate.setMonth(currentDate.getMonth() - 1);
            updateCalendar();
        } else if (mode === "month") {
            currentDate.setFullYear(currentDate.getFullYear() - 1);
            showMonthPicker();
        } else if (mode === "year") {
            currentDate.setFullYear(currentDate.getFullYear() - 12);
            showYearPicker();
        }
    };

    nextBtn.onclick = () => {
        if (mode === "date") {
            currentDate.setMonth(currentDate.getMonth() + 1);
            updateCalendar();
        } else if (mode === "month") {
            currentDate.setFullYear(currentDate.getFullYear() + 1);
            showMonthPicker();
        } else if (mode === "year") {
            currentDate.setFullYear(currentDate.getFullYear() + 12);
            showYearPicker();
        }
    };

    calendarIcon.onclick = toggleCalendar;
    dateInput.onclick = toggleCalendar;

    document.addEventListener('click', e => {
        if (!wrapper.contains(e.target)) calendarPopup.classList.add('hidden');
    });

    updateCalendar();
});