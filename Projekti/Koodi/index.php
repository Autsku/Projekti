<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Oppi – Kurssienhallinta</title>
    <link rel="stylesheet" href="Styles/index.css">
</head>
<body>
    <div class="header">
        <a href="index.php" class="logo">Oppi</a>
        <div class="items">
            <a href="tiedot.php">Tiedot</a>
            <a href="tilat.php">Tilat</a>
            <a href="kurssit.php">Kurssit</a>
            <a href="opiskelijat.php">Opiskelijat</a>
            <a href="opettajat.php">Opettajat</a>
        </div>
    </div>

    <section class="curse-box">
        <h1>Tervetuloa Oppi-järjestelmään</h1>
        <p>Oppi on oppilaitoksen kurssienhallintajärjestelmä, jossa voit hallita opiskelijoita, opettajia, kursseja, tiloja ja ilmoittautumisia.</p>
    </section>

    <!-- 📌 NEW LUKKARI SECTION -->
    <section class="lukkari">
        <div class="lukkari-header">
            <h2>Viikon lukkari</h2>

            <div class="calendar-chooser">
                <select id="calendar-type">
                    <option value="teacher">👨‍🏫 Opettajat</option>
                    <option value="student">👨‍🎓 Opiskelijat</option>
                    <option value="room">🏫 Tilat</option>
                </select>

                <select id="calendar-person"></select>
            </div>
        </div>

        <table class="lukkaritaulukko" id="lukkaritaulukko"></table>
    </section>

    <div class="content">
        <div class="left-column">
            <section class="card">
                <h2>Toiminnot</h2>
                <ul>
                    <li>📚 <strong>Kurssit:</strong> Tarkastele ja hallitse kursseja ja näe ilmoittautuneet opiskelijat.</li>
                    <li>👨‍🏫 <strong>Opettajat:</strong> Katso opettajien tiedot ja heidän kurssinsa.</li>
                    <li>👨‍🎓 <strong>Opiskelijat:</strong> Näe opiskelijoiden profiilit ja heidän kurssinsa.</li>
                    <li>🏫 <strong>Tilat:</strong> Tarkista tilojen kapasiteetti ja niissä pidettävät kurssit.</li>
                </ul>
            </section>

            <section class="card">
                <h2>Ajankohtaista</h2>
                <ul>
                    <li>📢 Kurssi-ilmoittautuminen päättyy 15.9.2025</li>
                    <li>🛠️ Huoltokatko järjestelmässä 20.9.2025 klo 22–00</li>
                </ul>
            </section>
        </div>

        <section class="right-column">
            <div class="calendar-header">
                <button id="prev-month">◀</button>
                <span id="month-year">Syyskuu 2025</span>
                <button id="next-month">▶</button>
            </div>
            <div class="calendar-dates" id="calendar-dates"></div>
        </section>
    </div>

    <div class="contentf">
        <h1>Yhteystiedot</h1>
        <div class="contacts-grid">
            <section class="contact">
                <h3>Opinto-ohjaaja Mari Meikäläinen</h3>
                <p>📞 040 123 4567</p>
                <p>✉️ mari.meikalainen@koulu.fi</p>
            </section>

            <section class="contact">
                <h3>Koulu-kuraattori Tomi Mänty</h3>
                <p>📞 040 123 4567</p>
                <p>✉️ tomi.manty@koulu.fi</p>
            </section>

            <section class="contact">
                <h3>IT-tuki Korhi Havunen</h3>
                <p>📞 040 123 4567</p>
                <p>✉️ kori.havunen@koulu.fi</p>
            </section>

            <section class="contact">
                <h3>Rehtori Juha Tapiola</h3>
                <p>📞 040 123 4567</p>
                <p>✉️ juha.tapiola@koulu.fi</p>
            </section>

            <section class="contact">
                <h3>Info</h3>
                <p>📞 040 123 4567</p>
                <p>✉️ info@koulu.fi</p>
            </section>
        </div>
    </div>

    <script>
    /* ========= EXISTING CALENDAR JS ========= */
    const monthYearEl = document.getElementById('month-year');
    const calendarDatesEl = document.getElementById('calendar-dates');
    const prevBtn = document.getElementById('prev-month');
    const nextBtn = document.getElementById('next-month');

    const months = ['Tammikuu', 'Helmikuu', 'Maaliskuu', 'Huhtikuu', 'Toukokuu', 'Kesäkuu', 'Heinäkuu', 'Elokuu', 'Syyskuu', 'Lokakuu', 'Marraskuu', 'Joulukuu'];
    const dayNames = ['Su', 'Ma', 'Ti', 'Ke', 'To', 'Pe', 'La'];

    let currentDate = new Date();

    function renderCalendar(date) {
        calendarDatesEl.innerHTML = '';
        const year = date.getFullYear();
        const month = date.getMonth();
        monthYearEl.textContent = `${months[month]} ${year}`;
        const dayNamesDiv = document.createElement('div');
        dayNamesDiv.className = 'day-names';
        dayNames.forEach(dayName => {
            const dayEl = document.createElement('div');
            dayEl.className = 'day-name';
            dayEl.textContent = dayName;
            dayNamesDiv.appendChild(dayEl);
        });
        calendarDatesEl.appendChild(dayNamesDiv);
        const datesGrid = document.createElement('div');
        datesGrid.className = 'dates-grid';
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDay = firstDay.getDay();
        const prevMonth = new Date(year, month, 0);
        const prevMonthDays = prevMonth.getDate();
        for (let i = startingDay - 1; i >= 0; i--) {
            const dateDiv = document.createElement('div');
            dateDiv.className = 'date-cell other-month';
            dateDiv.textContent = prevMonthDays - i;
            datesGrid.appendChild(dateDiv);
        }
        const today = new Date();
        for (let day = 1; day <= daysInMonth; day++) {
            const dateDiv = document.createElement('div');
            dateDiv.className = 'date-cell';
            dateDiv.textContent = day;
            if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                dateDiv.classList.add('today');
            }
            datesGrid.appendChild(dateDiv);
        }
        const totalCells = datesGrid.children.length;
        const remainingCells = 42 - totalCells;
        for (let day = 1; day <= remainingCells && remainingCells < 7; day++) {
            const dateDiv = document.createElement('div');
            dateDiv.className = 'date-cell other-month';
            dateDiv.textContent = day;
            datesGrid.appendChild(dateDiv);
        }
        calendarDatesEl.appendChild(datesGrid);
    }

    prevBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
    });

    nextBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
    });

    renderCalendar(currentDate);

    /* ========= NEW LUKKARI JS ========= */
    const schedules = {
        teacher: {
            "topi": {
                title: "Opettaja Topi Topilainen – Viikko 38",
                table: `
                    <thead>
                        <tr>
                            <th>Aika</th>
                            <th data-day="1">Ma</th>
                            <th data-day="2">Ti</th>
                            <th data-day="3">Ke</th>
                            <th data-day="4">To</th>
                            <th data-day="5">Pe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>9.00</td>
                            <td class="kurssi" rowspan="2">Rakenteinen ohjelmointi 1</td>
                            <td></td>
                            <td class="kurssi" rowspan="2">Rakenteinen ohjelmointi 1</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                `
            }
        },
        student: {
            "mari": {
                title: "Opiskelija Mari Meikäläinen – Viikko 38",
                table: `
                    <thead>
                        <tr>
                            <th>Aika</th>
                            <th data-day="1">Ma</th>
                            <th data-day="2">Ti</th>
                            <th data-day="3">Ke</th>
                            <th data-day="4">To</th>
                            <th data-day="5">Pe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>9.00</td>
                            <td></td>
                            <td class="kurssi">Englanti</td>
                            <td></td>
                            <td></td>
                            <td class="kurssi">Matematiikka</td>
                        </tr>
                    </tbody>
                `
            }
        },
        room: {
            "tilaA": {
                title: "Luokka A101 – Viikko 38",
                table: `
                    <thead>
                        <tr>
                            <th>Aika</th>
                            <th data-day="1">Ma</th>
                            <th data-day="2">Ti</th>
                            <th data-day="3">Ke</th>
                            <th data-day="4">To</th>
                            <th data-day="5">Pe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>9.00</td>
                            <td class="kurssi">Käyttöliittymä</td>
                            <td></td>
                            <td class="kurssi">Web-ohjelmointi</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                `
            }
        }
    };

    const typeEl = document.getElementById("calendar-type");
    const personEl = document.getElementById("calendar-person");
    const tableEl = document.getElementById("lukkaritaulukko");

    function fillPersons(type) {
        personEl.innerHTML = "";
        Object.keys(schedules[type]).forEach(id => {
            const option = document.createElement("option");
            option.value = id;
            option.textContent = schedules[type][id].title.split(" – ")[0];
            personEl.appendChild(option);
        });
    }

    function loadCalendar(type, id) {
        const data = schedules[type][id];
        document.querySelector(".lukkari h2").textContent = data.title;
        tableEl.innerHTML = data.table;
        highlightToday();
    }

    function highlightToday() {
        const today = new Date().getDay();
        if (today >= 1 && today <= 5) {
            const headers = tableEl.querySelectorAll(`th[data-day="${today}"]`);
            if (headers.length > 0) {
                const colIndex = Array.from(headers[0].parentNode.children).indexOf(headers[0]);
                tableEl.querySelectorAll("tr").forEach(row => {
                    const cell = row.children[colIndex];
                    if (cell) cell.classList.add("highlight");
                });
            }
        }
    }

    fillPersons("teacher");
    loadCalendar("teacher", personEl.value);

    typeEl.addEventListener("change", e => {
        const type = e.target.value;
        fillPersons(type);
        loadCalendar(type, personEl.value);
    });

    personEl.addEventListener("change", e => {
        loadCalendar(typeEl.value, e.target.value);
    });
    </script>
</body>
</html>
