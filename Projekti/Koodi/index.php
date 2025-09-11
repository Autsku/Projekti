<!DOCTYPE html>
<html lang="fi">
<head>
    <link rel="stylesheet" href="index.css">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Oppi – Kurssienhallinta</title>
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
            <div class="calendar-dates" id="calendar-dates">
                <!-- Calendar will be rendered here -->
            </div>
        </section>
    </div>

    <div class="contentf">
        <h2>Yhteystiedot</h2>

        <h3>Opinto-ohjaaja Mari Meikäläinen</h3>
        <p>📞 040 123 4567</p>
        <p>✉️ mari.meikalainen@koulu.fi</p>

        <h3>IT-tuki</h3>
        <p>📞 040 987 6543</p>
        <p>✉️ it@koulu.fi</p>
    </div>

    <script>
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

            // Create day names header
            const dayNamesDiv = document.createElement('div');
            dayNamesDiv.className = 'day-names';
            
            dayNames.forEach(dayName => {
                const dayEl = document.createElement('div');
                dayEl.className = 'day-name';
                dayEl.textContent = dayName;
                dayNamesDiv.appendChild(dayEl);
            });
            
            calendarDatesEl.appendChild(dayNamesDiv);

            // Create dates grid
            const datesGrid = document.createElement('div');
            datesGrid.className = 'dates-grid';

            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDay = firstDay.getDay(); // 0 = Sunday

            // Previous month's trailing dates
            const prevMonth = new Date(year, month, 0);
            const prevMonthDays = prevMonth.getDate();
            
            for (let i = startingDay - 1; i >= 0; i--) {
                const dateDiv = document.createElement('div');
                dateDiv.className = 'date-cell other-month';
                dateDiv.textContent = prevMonthDays - i;
                datesGrid.appendChild(dateDiv);
            }

            // Current month dates
            const today = new Date();
            for (let day = 1; day <= daysInMonth; day++) {
                const dateDiv = document.createElement('div');
                dateDiv.className = 'date-cell';
                dateDiv.textContent = day;

                // Highlight today
                if (
                    day === today.getDate() &&
                    month === today.getMonth() &&
                    year === today.getFullYear()
                ) {
                    dateDiv.classList.add('today');
                }
                
                datesGrid.appendChild(dateDiv);
            }

            // Next month's leading dates
            const totalCells = datesGrid.children.length;
            const remainingCells = 42 - totalCells; // 6 rows × 7 days = 42 cells
            
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

        // Initial render
        renderCalendar(currentDate);
    </script>
</body>
</html>