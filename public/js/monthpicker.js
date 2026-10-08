const monthYearElement = document.getElementById('monthYear');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const monthsContainer = document.querySelector('.monthpicker-months');
const yearGrid = document.getElementById('year');

const monthpickerInput = document.getElementById("monthpickerInput");
const monthpickerPanel = document.getElementById("monthpickerPanel");
const input = document.getElementById("monthpickerInput");
const panel = document.getElementById("monthpickerPanel");

// Toggle Trigger
monthpickerInput.addEventListener("click", () => {
    if (monthpickerPanel.style.display === "block") {
        monthpickerPanel.style.display = "none";
    } else {
        monthpickerPanel.style.display = "block";
    }
});

// tutup panel jika klik area luar
document.addEventListener("click", (e) => {
    if (!monthpickerPanel.contains(e.target) && !monthpickerInput.contains(e.target)) {
        monthpickerPanel.style.display = "none";
    }
});

let currentDate = new Date();
let mode = 'month';

function showMonthPicker() {
    mode = "month";

    monthYearElement.classList.remove("no-pointer");
    prevBtn.classList.remove("hidden-icon");
    nextBtn.classList.remove("hidden-icon");

    monthYearElement.textContent = currentDate.getFullYear();

    monthsContainer.classList.remove("hidden");
    yearGrid.classList.add("hidden");

    [...monthsContainer.children].forEach((div, index) => {
        div.classList.remove("month-active");
        if (index === currentDate.getMonth()) {
            div.classList.add("month-active");
        }

        div.onclick = () => {
    currentDate.setMonth(index);
    highlightActiveMonth();
    renderCalendar();

    monthpickerInput.value = currentDate.toLocaleDateString("id-ID", {
        month: "long",
        year: "numeric"
    });

    monthpickerPanel.style.display = "none";
};
    });
}

function highlightActiveMonth() {
    [...monthsContainer.children].forEach((div, index) => {
        div.classList.toggle("month-active", index === currentDate.getMonth());
    });
}

function showYearPicker() {
    mode = "year";

    monthYearElement.classList.add("no-pointer");
    prevBtn.classList.add("hidden-icon");
    nextBtn.classList.add("hidden-icon");

    const currentYear = currentDate.getFullYear();
    monthYearElement.textContent = currentYear;

    monthsContainer.classList.add("hidden");
    yearGrid.classList.remove("hidden");
    yearGrid.innerHTML = "";

    for (let y = 2000; y <= 2050; y++) {
        const div = document.createElement("div");
        div.textContent = y;

        if (y === currentYear) div.classList.add("year-active");

        div.onclick = () => {
    currentDate.setFullYear(y);
    showMonthPicker();
    renderCalendar();
};

        yearGrid.appendChild(div);
    }

    const activeYear = yearGrid.querySelector(".year-active");
    if (activeYear) {
        activeYear.scrollIntoView({ block: "center" });
    }
}

monthYearElement.onclick = () => {
    if (mode === "month") showYearPicker();
    else if (mode === "year") showMonthPicker();
};

prevBtn.onclick = () => {
    if (mode === "year") return;
    currentDate.setFullYear(currentDate.getFullYear() - 1);
    showMonthPicker();
    renderCalendar();
};

nextBtn.onclick = () => {
    if (mode === "year") return;
    currentDate.setFullYear(currentDate.getFullYear() + 1);
    showMonthPicker();
    renderCalendar();
};

function renderCalendar() {
    const today = new Date();
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    document.getElementById("calendarMonth").textContent =
        currentDate.toLocaleDateString("id-ID", { month: "long", year: "numeric" });

    const calendarDates = document.getElementById("calendarDates");
    calendarDates.innerHTML = "";

    const firstDay = new Date(year, month, 1).getDay(); 
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const daysInPrevMonth = new Date(year, month, 0).getDate();
    const startIndex = (firstDay === 0) ? 6 : firstDay - 1; 

    for (let i = 0; i < startIndex; i++) {
        const prevDay = daysInPrevMonth - (startIndex - 1 - i);
        const dayDiv = document.createElement("div");
        
        dayDiv.classList.add("date-cell", "prev-month");
        dayDiv.textContent = prevDay;
        calendarDates.appendChild(dayDiv);
    }

    for (let d = 1; d <= daysInMonth; d++) {
        const dayDiv = document.createElement("div");
        dayDiv.classList.add("date-cell");

        const isToday = (d === today.getDate() && month === today.getMonth() && year === today.getFullYear());

        if (isToday) {
            dayDiv.classList.add("current");
        }
        
        dayDiv.textContent = d;
        calendarDates.appendChild(dayDiv);
    }
    
    const totalCells = startIndex + daysInMonth;
    const remainingCells = 42 - totalCells;

    for (let d = 1; d <= remainingCells; d++) {
        const dayDiv = document.createElement("div");
        dayDiv.classList.add("date-cell", "next-month");
        dayDiv.textContent = d;
        calendarDates.appendChild(dayDiv);
    }
}

showMonthPicker();
renderCalendar();