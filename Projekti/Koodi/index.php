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
                    <option value="teacher">Opettajat </option>
                    <option value="student">Opiskelijat </option>
                    <option value="room">Tilat </option>
                </select>

                <!-- Hakukenttä -->
                <div class="search-container">
                    <input type="text" id="person-search" placeholder="Hae nimellä tai ID:llä..." style="padding: 8px; width: 200px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

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

    // Debug-tulostus konsoliin
    console.log('Ladatut opettajat:', teachers);
    console.log('Ladatut kurssit:', courses);
    console.log('Ilmoittautumiset:', enrollments);

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
        currentCalendarDate = new Date(currentDate); // Synkronoi lukujärjestyskalenterin
        renderCalendar(currentDate);
        syncWeekWithCalendar();
        loadCalendar();
    });

    nextBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        currentCalendarDate = new Date(currentDate); // Synkronoi lukujärjestyskalenterin
        renderCalendar(currentDate);
        syncWeekWithCalendar();
        loadCalendar();
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

        getWeekFromCalendar(calendarDate) {
            // Laskee viikon numeron kalenterin päivämäärän perusteella
            return this.getWeekNumber(calendarDate);
        }

        getWeekBounds(weekNumber, year) {
            // Palauttaa viikon ensimmäisen ja viimeisen päivän
            const jan1 = new Date(Date.UTC(year, 0, 1));
            const jan1DayOfWeek = jan1.getUTCDay() || 7;
            
            // Ensimmäisen viikon maanantai
            const firstMonday = new Date(jan1);
            firstMonday.setUTCDate(jan1.getUTCDate() + (8 - jan1DayOfWeek));
            
            // Halutun viikon maanantai
            const weekStart = new Date(firstMonday);
            weekStart.setUTCDate(firstMonday.getUTCDate() + (weekNumber - 1) * 7);
            
            // Viikon perjantai
            const weekEnd = new Date(weekStart);
            weekEnd.setUTCDate(weekStart.getUTCDate() + 4);
            
            return { start: weekStart, end: weekEnd };
        }

        getValidWeekRange(currentDate) {
            const year = currentDate.getFullYear();
            const jan1 = new Date(year, 0, 1);
            const dec31 = new Date(year, 11, 31);
            
            const firstWeek = this.getWeekNumber(jan1);
            const lastWeek = this.getWeekNumber(dec31);
            
            return { min: Math.max(1, firstWeek), max: Math.min(53, lastWeek) };
        }

        isCourseActive(course, weekNumber) {
            const startDate = new Date(course.Alkupaiva);
            const endDate = new Date(course.Loppupaiva);
            
            const startWeek = this.getWeekNumber(startDate);
            const endWeek = this.getWeekNumber(endDate);
            
            // Muutettu: Jos kurssi on päättynyt tai ei ole vielä alkanut, 
            // näytetään se silti jos se on lähellä nykyistä viikkoa (±10 viikkoa)
            const isInPeriod = weekNumber >= startWeek && weekNumber <= endWeek;
            const isNearCurrent = Math.abs(weekNumber - startWeek) <= 10 || Math.abs(weekNumber - endWeek) <= 10;
            
            return isInPeriod || isNearCurrent;
        }

        generateRandomSchedule(courseList, weekNumber) {
            const schedule = {};
            
            // Alusta aikataulu
            for (let time of this.timeSlots) {
                schedule[time] = { 1: [], 2: [], 3: [], 4: [], 5: [] };
            }
            
            // Debug: tulosta kurssit konsoliin
            console.log(`Generoidaan lukujärjestys viikolle ${weekNumber}:`, courseList);
            
            // Lisää kurssit satunnaisesti
            courseList.forEach(course => {
                if (!this.isCourseActive(course, weekNumber)) {
                    console.log(`Kurssi ${course.Nimi} ei ole aktiivinen viikolla ${weekNumber}`);
                    return;
                }
                
                console.log(`Lisätään kurssi: ${course.Nimi}`);
                const sessionsPerWeek = Math.floor(Math.random() * 3) + 1;
                const usedSlots = new Set();
                
                for (let session = 0; session < sessionsPerWeek; session++) {
                    let attempts = 0;
                    let placed = false;
                    
                    while (!placed && attempts < 50) { // Nostettu yritysten määrää
                        const timeSlot = this.timeSlots[Math.floor(Math.random() * this.timeSlots.length)];
                        const day = Math.floor(Math.random() * 5) + 1;
                        const slotKey = `${timeSlot}-${day}`;
                        
                        // Sallitaan päällekkäisyydet - ei tarkisteta usedSlots
                        schedule[timeSlot][day].push({
                            courseId: course.Tunnus,
                            name: course.Nimi,
                            room: course.TilaNimi || 'Ei tilaa',
                            teacher: `${course.Etunimi || ''} ${course.Sukunimi || ''}`.trim() || 'Ei opettajaa'
                        });
                        placed = true;
                        
                        attempts++;
                    }
                    
                    if (!placed) {
                        console.log(`Ei voitu sijoittaa kurssia ${course.Nimi} sessiota ${session + 1}`);
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
    const searchEl = document.getElementById("person-search");
    const tableEl = document.getElementById("lukkaritaulukko");
    const currentWeekEl = document.getElementById("current-week");
    const prevWeekBtn = document.getElementById("prev-week");
    const nextWeekBtn = document.getElementById("next-week");

    // Globaalit muuttujat
    let generator = new ScheduleGenerator();
    let currentWeek = generator.currentWeek;
    let currentCalendarDate = new Date(); // Seurataan myös kuukausikalenterin päivää
    let currentType = 'teacher';
    let currentPersonId = null;
    let allPersons = []; // Kaikki henkilöt hakua varten

    // Synkronoi viikko kuukausikalenterin kanssa
    function syncWeekWithCalendar() {
        currentWeek = generator.getWeekFromCalendar(currentCalendarDate);
        const weekRange = generator.getValidWeekRange(currentCalendarDate);
        
        // Varmista että viikko on vuoden rajoissa
        if (currentWeek < weekRange.min) {
            currentWeek = weekRange.min;
        } else if (currentWeek > weekRange.max) {
            currentWeek = weekRange.max;
        }
        
        updateWeekDisplay();
    }

    function searchPersons(searchTerm, type) {
        if (!searchTerm.trim()) {
            return allPersons; // Palauta kaikki jos haku tyhjä
        }

        const searchParts = searchTerm.trim().split(/\s+/);
        
        return allPersons.filter(person => {
            if (searchParts.length === 1) {
                // Yksi sana - hae ID:llä tai nimellä
                const singleTerm = searchParts[0].toLowerCase();
                const id = type === 'teacher' ? person.Tunnusnumero : 
                          type === 'student' ? person.Opiskelijanumero : person.Tunnus;
                const firstName = person.Etunimi ? person.Etunimi.toLowerCase() : '';
                const lastName = person.Sukunimi ? person.Sukunimi.toLowerCase() : '';
                const roomName = person.Nimi ? person.Nimi.toLowerCase() : '';
                
                return id.toString().includes(singleTerm) || 
                       firstName.includes(singleTerm) || 
                       lastName.includes(singleTerm) ||
                       roomName.includes(singleTerm);
            } else {
                // Useampi sana - hae etu- ja sukunimellä
                const firstTerm = searchParts[0].toLowerCase();
                const lastTerm = searchParts[1].toLowerCase();
                const firstName = person.Etunimi ? person.Etunimi.toLowerCase() : '';
                const lastName = person.Sukunimi ? person.Sukunimi.toLowerCase() : '';
                
                return firstName.includes(firstTerm) && lastName.includes(lastTerm);
            }
        });
    }

    function fillPersons(type, searchTerm = '') {
        personEl.innerHTML = "";
        
        switch (type) {
            case 'teacher':
                allPersons = teachers;
                searchEl.placeholder = "Hae opettajaa nimellä tai ID:llä...";
                break;
            case 'student':
                allPersons = students;
                searchEl.placeholder = "Hae opiskelijaa nimellä tai ID:llä...";
                break;
            case 'room':
                allPersons = rooms;
                searchEl.placeholder = "Hae tilaa nimellä tai ID:llä...";
                break;
        }

        const filteredPersons = searchPersons(searchTerm, type);

        if (filteredPersons.length === 0 && searchTerm.trim()) {
            const option = document.createElement("option");
            option.value = "";
            option.textContent = "Ei tuloksia";
            option.disabled = true;
            personEl.appendChild(option);
            currentPersonId = null;
            return;
        }

        filteredPersons.forEach(item => {
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

        // Aseta ensimmäinen henkilö valituksi jos löytyy
        if (filteredPersons.length > 0) {
            currentPersonId = personEl.value;
        } else {
            currentPersonId = null;
        }
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
        const year = currentCalendarDate.getFullYear();
        currentWeekEl.textContent = `Viikko ${currentWeek}/${year}`;
    }

    function highlightToday() {
        const today = new Date().getDay();
        if (today >= 1 && today <= 5) {
            // Korostetaan vain tbody-soluissa, ei thead-otsikoissa
            const bodyCells = tableEl.querySelectorAll(`tbody td[data-day="${today}"]`);
            bodyCells.forEach(cell => cell.classList.add("highlight"));
        }
    }

    // Event listenerit
    typeEl.addEventListener("change", e => {
        currentType = e.target.value;
        searchEl.value = ''; // Tyhjennä haku kun vaihdetaan tyyppiä
        fillPersons(currentType);
        loadCalendar();
    });

    personEl.addEventListener("change", e => {
        currentPersonId = e.target.value;
        loadCalendar();
    });

    // Hakukenttä - päivitä tuloksia kun käyttäjä kirjoittaa
    searchEl.addEventListener("input", e => {
        const searchTerm = e.target.value;
        fillPersons(currentType, searchTerm);
        if (currentPersonId) {
            loadCalendar();
        }
    });

    // Enter-näppäin hakukentässä
    searchEl.addEventListener("keypress", e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            if (personEl.options.length > 0 && !personEl.options[0].disabled) {
                personEl.selectedIndex = 0;
                currentPersonId = personEl.value;
                loadCalendar();
            }
        }
    });

    prevWeekBtn.addEventListener("click", () => {
        const currentYear = currentCalendarDate.getFullYear();
        const weekRange = generator.getValidWeekRange(currentCalendarDate);
        
        if (currentWeek > weekRange.min) {
            currentWeek--;
        } else {
            // Siirrytään edelliseen vuoteen
            currentCalendarDate.setFullYear(currentYear - 1);
            currentWeek = 52; // Useimmissa vuosissa viimeinen viikko
        }
        
        updateWeekDisplay();
        loadCalendar();
    });

    nextWeekBtn.addEventListener("click", () => {
        const currentYear = currentCalendarDate.getFullYear();
        const weekRange = generator.getValidWeekRange(currentCalendarDate);
        
        if (currentWeek < weekRange.max) {
            currentWeek++;
        } else {
            // Siirrytään seuraavaan vuoteen
            currentCalendarDate.setFullYear(currentYear + 1);
            currentWeek = 1; // Ensimmäinen viikko
        }
        
        updateWeekDisplay();
        loadCalendar();
    });

    // Alustus
    fillPersons(currentType);
    syncWeekWithCalendar(); // Synkronoi aluksi
    loadCalendar();
    </script>
</body>
</html>