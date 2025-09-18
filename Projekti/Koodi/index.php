<?php
include 'yhteys.php';

// Hae kaikki tarvittavat tiedot tietokannasta
$opettajat = $yhteys->query("SELECT * FROM opettajat ORDER BY Etunimi, Sukunimi")->fetchAll(PDO::FETCH_ASSOC);
$opiskelijat = $yhteys->query("SELECT * FROM opiskelijat ORDER BY Etunimi, Sukunimi")->fetchAll(PDO::FETCH_ASSOC);
$tilat = $yhteys->query("SELECT * FROM tilat ORDER BY Nimi")->fetchAll(PDO::FETCH_ASSOC);
$kurssit = $yhteys->query("SELECT k.*, o.Etunimi, o.Sukunimi, t.Nimi as TilaNimi 
                          FROM kurssit k 
                          LEFT JOIN opettajat o ON k.Opettaja = o.Tunnusnumero 
                          LEFT JOIN tilat t ON k.Tila = t.Tunnus")->fetchAll(PDO::FETCH_ASSOC);

// Hae kurssi-ilmoittautumiset
$ilmoittautumiset = $yhteys->query("SELECT * FROM kurssikirjautuminen")->fetchAll(PDO::FETCH_ASSOC);
?>
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

    <!-- Lukkari-osio -->
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

                <!-- Viikkonavigointi -->
                <div class="week-navigation">
                    <button id="prev-week" class="week-nav-btn">◀ Edellinen</button>
                    <span id="current-week" class="week-info">Viikko 38</span>
                    <button id="next-week" class="week-nav-btn">Seuraava ▶</button>
                </div>
            </div>
        </div>

        <table class="lukkaritaulukko" id="lukkaritaulukko">
            <!-- Taulukko täytetään JavaScriptillä -->
        </table>
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
    // Tietokannan data PHP:sta JavaScriptiin
    const teachers = <?php echo json_encode($opettajat); ?>;
    const students = <?php echo json_encode($opiskelijat); ?>;
    const rooms = <?php echo json_encode($tilat); ?>;
    const courses = <?php echo json_encode($kurssit); ?>;
    const enrollments = <?php echo json_encode($ilmoittautumiset); ?>;

    /* ========= Alkuperäinen kuukausikalenteri ========= */
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

    /* ========= Lukujärjestys ========= */
    
    // Aikataulugeneraattori
    class ScheduleGenerator {
        constructor() {
            this.timeSlots = ['8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00'];
            this.currentWeek = this.getWeekNumber(new Date());
        }

        getWeekNumber(date) {
            const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
            const dayNum = d.getUTCDay() || 7;
            d.setUTCDate(d.getUTCDate() + 4 - dayNum);
            const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
            return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
        }

        isCourseActive(course, weekNumber) {
            const startDate = new Date(course.Alkupaiva);
            const endDate = new Date(course.Loppupaiva);
            
            const startWeek = this.getWeekNumber(startDate);
            const endWeek = this.getWeekNumber(endDate);
            
            return weekNumber >= startWeek && weekNumber <= endWeek;
        }

        generateRandomSchedule(courseList, weekNumber) {
            const schedule = {};
            
            // Alusta aikataulu
            for (let time of this.timeSlots) {
                schedule[time] = { 1: [], 2: [], 3: [], 4: [], 5: [] };
            }
            
            // Lisää kurssit satunnaisesti
            courseList.forEach(course => {
                if (!this.isCourseActive(course, weekNumber)) return;
                
                const sessionsPerWeek = Math.floor(Math.random() * 3) + 1;
                const usedSlots = new Set();
                
                for (let session = 0; session < sessionsPerWeek; session++) {
                    let attempts = 0;
                    let placed = false;
                    
                    while (!placed && attempts < 20) {
                        const timeSlot = this.timeSlots[Math.floor(Math.random() * this.timeSlots.length)];
                        const day = Math.floor(Math.random() * 5) + 1;
                        const slotKey = `${timeSlot}-${day}`;
                        
                        if (!usedSlots.has(slotKey)) {
                            schedule[timeSlot][day].push({
                                courseId: course.Tunnus,
                                name: course.Nimi,
                                room: course.TilaNimi || 'Ei tilaa',
                                teacher: `${course.Etunimi} ${course.Sukunimi}` || 'Ei opettajaa'
                            });
                            usedSlots.add(slotKey);
                            placed = true;
                        }
                        attempts++;
                    }
                }
            });
            
            return schedule;
        }

        getTeacherSchedule(teacherId, weekNumber) {
            const teacherCourses = courses.filter(course => 
                course.Opettaja == teacherId
            );
            return this.generateRandomSchedule(teacherCourses, weekNumber);
        }

        getStudentSchedule(studentId, weekNumber) {
            // Hae opiskelijan kurssit ilmoittautumisten perusteella
            const studentEnrollments = enrollments.filter(enrollment => 
                enrollment.Opiskelija == studentId
            );
            const studentCourses = courses.filter(course => 
                studentEnrollments.some(enrollment => enrollment.Kurssi == course.Tunnus)
            );
            return this.generateRandomSchedule(studentCourses, weekNumber);
        }

        getRoomSchedule(roomId, weekNumber) {
            const roomCourses = courses.filter(course => 
                course.Tila == roomId
            );
            return this.generateRandomSchedule(roomCourses, weekNumber);
        }
    }

    // UI-elementit
    const typeEl = document.getElementById("calendar-type");
    const personEl = document.getElementById("calendar-person");
    const tableEl = document.getElementById("lukkaritaulukko");
    const currentWeekEl = document.getElementById("current-week");
    const prevWeekBtn = document.getElementById("prev-week");
    const nextWeekBtn = document.getElementById("next-week");

    // Globaalit muuttujat
    let generator = new ScheduleGenerator();
    let currentWeek = generator.currentWeek;
    let currentType = 'teacher';
    let currentPersonId = null;

    function fillPersons(type) {
        personEl.innerHTML = "";
        let data = [];
        
        switch (type) {
            case 'teacher':
                data = teachers;
                break;
            case 'student':
                data = students;
                break;
            case 'room':
                data = rooms;
                break;
        }

        data.forEach(item => {
            const option = document.createElement("option");
            if (type === 'teacher') {
                option.value = item.Tunnusnumero;
                option.textContent = `${item.Etunimi} ${item.Sukunimi}`;
            } else if (type === 'student') {
                option.value = item.Opiskelijanumero;
                option.textContent = `${item.Etunimi} ${item.Sukunimi}`;
            } else if (type === 'room') {
                option.value = item.Tunnus;
                option.textContent = item.Nimi;
            }
            personEl.appendChild(option);
        });

        currentPersonId = personEl.value;
    }

    function loadCalendar() {
        if (!currentPersonId) return;

        let schedule = {};
        let title = '';

        switch (currentType) {
            case 'teacher':
                schedule = generator.getTeacherSchedule(currentPersonId, currentWeek);
                const teacher = teachers.find(t => t.Tunnusnumero == currentPersonId);
                title = `${teacher.Etunimi} ${teacher.Sukunimi} – Viikko ${currentWeek}`;
                break;
            case 'student':
                schedule = generator.getStudentSchedule(currentPersonId, currentWeek);
                const student = students.find(s => s.Opiskelijanumero == currentPersonId);
                title = `${student.Etunimi} ${student.Sukunimi} – Viikko ${currentWeek}`;
                break;
            case 'room':
                schedule = generator.getRoomSchedule(currentPersonId, currentWeek);
                const room = rooms.find(r => r.Tunnus == currentPersonId);
                title = `${room.Nimi} – Viikko ${currentWeek}`;
                break;
        }

        document.querySelector(".lukkari h2").textContent = title;
        renderScheduleTable(schedule);
        highlightToday();
    }

    function renderScheduleTable(schedule) {
        let tableHTML = `
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
        `;

        generator.timeSlots.forEach(timeSlot => {
            tableHTML += `<tr><td>${timeSlot}</td>`;
            
            for (let day = 1; day <= 5; day++) {
                tableHTML += `<td data-day="${day}">`;
                const sessions = schedule[timeSlot]?.[day] || [];
                
                sessions.forEach(session => {
                    tableHTML += `
                        <div class="kurssi" title="${session.name}\n${session.teacher}\n${session.room}">
                            ${session.name}
                        </div>
                    `;
                });
                
                tableHTML += `</td>`;
            }
            
            tableHTML += `</tr>`;
        });

        tableHTML += `</tbody>`;
        tableEl.innerHTML = tableHTML;
    }

    function updateWeekDisplay() {
        currentWeekEl.textContent = `Viikko ${currentWeek}`;
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

    // Event listenerit
    typeEl.addEventListener("change", e => {
        currentType = e.target.value;
        fillPersons(currentType);
        loadCalendar();
    });

    personEl.addEventListener("change", e => {
        currentPersonId = e.target.value;
        loadCalendar();
    });

    prevWeekBtn.addEventListener("click", () => {
        currentWeek--;
        updateWeekDisplay();
        loadCalendar();
    });

    nextWeekBtn.addEventListener("click", () => {
        currentWeek++;
        updateWeekDisplay();
        loadCalendar();
    });

    // Alustus
    fillPersons(currentType);
    updateWeekDisplay();
    loadCalendar();
    </script>
</body>
</html>