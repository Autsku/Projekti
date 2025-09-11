<?php
include 'yhteys.php';
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Tiedot</title>
    <link rel="stylesheet" href="styles.css">
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

    <div class="content">

        <div class="cards-container">
            <div class="card" onclick="toggleSection('oppilaat-section')">
                <h1>Oppilaat</h1>
                <p>Katso opiskelijalistaus, muokkaa tai poista opiskelijoita.</p>
                <a href="add.php?table=opiskelijat" class="button" onclick="event.stopPropagation()">Lisää oppilas</a>
            </div>

            <div class="card" onclick="toggleSection('opettajat-section')">
                <h1>Opettajat</h1>
                <p>Katso opettajalistaus, muokkaa tai poista opettajia.</p>
                <a href="add.php?table=opettajat" class="button" onclick="event.stopPropagation()">Lisää opettaja</a>
            </div>

            <div class="card" onclick="toggleSection('hallinta-section')">
                <h1>Tiedon hallinta</h1>
                <p>Kurssit ja kirjautumiset – lisää, muokkaa, poista.</p>
                <a href="add.php?table=kurssit" class="button" onclick="event.stopPropagation()">Lisää kurssi</a>
                <a href="add.php?table=kurssikirjautuminen" class="button" onclick="event.stopPropagation()">Lisää kirjautuminen</a>
            </div>

        </div>

        <!-- Oppilaat listaosio -->
        <div id="oppilaat-section" class="section-content">
            <h2>Oppilaat</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Etunimi</th><th>Sukunimi</th><th>Syntymäpäivä</th><th>Vuosikurssi</th><th>Toiminnot</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM opiskelijat";
                    $result = $yhteys->query($sql);
                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                            <td>{$row['Opiskelijanumero']}</td>
                            <td>".htmlspecialchars($row['Etunimi'])."</td>
                            <td>".htmlspecialchars($row['Sukunimi'])."</td>
                            <td>".htmlspecialchars($row['Syntymapaiva'])."</td>
                            <td>".htmlspecialchars($row['Vuosikurssi'])."</td>
                            <td>
                                <a class='update-button' href='update.php?table=opiskelijat&id={$row['Opiskelijanumero']}'>Update</a>
                                <a class='delete-button' href='delete.php?table=opiskelijat&id={$row['Opiskelijanumero']}'>Delete</a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Opettajat listaosio -->
        <div id="opettajat-section" class="section-content">
            <h2>Opettajat</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Etunimi</th><th>Sukunimi</th><th>Aine</th><th>Toiminnot</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM opettajat";
                    $result = $yhteys->query($sql);
                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                            <td>{$row['Tunnusnumero']}</td>
                            <td>".htmlspecialchars($row['Etunimi'])."</td>
                            <td>".htmlspecialchars($row['Sukunimi'])."</td>
                            <td>".htmlspecialchars($row['Aine'])."</td>
                            <td>
                                <a class='update-button' href='update.php?table=opettajat&id={$row['Tunnusnumero']}'>Update</a>
                                <a class='delete-button' href='delete.php?table=opettajat&id={$row['Tunnusnumero']}'>Delete</a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Hallinta: Kurssit + Kirjautumiset osio -->
        <div id="hallinta-section" class="section-content">
            <h2>Kurssit</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Nimi</th><th>Kuvaus</th><th>Alkupäivä</th><th>Loppupäivä</th><th>Opettaja</th><th>Tila</th><th>Toiminnot</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT k.Tunnus, k.Nimi, k.Kuvaus, k.Alkupaiva, k.Loppupaiva,
                                   o.Etunimi, o.Sukunimi,
                                   t.Nimi AS tila_nimi
                            FROM kurssit k
                            LEFT JOIN opettajat o ON k.Opettaja = o.Tunnusnumero
                            LEFT JOIN tilat t ON k.Tila = t.Tunnus
                            ORDER BY k.Nimi";
                    $res = $yhteys->query($sql);
                    while($r = $res->fetch(PDO::FETCH_ASSOC)) {
                        $opettaja = htmlspecialchars($r['Etunimi'] . ' ' . $r['Sukunimi']);
                        echo "<tr>
                            <td>{$r['Tunnus']}</td>
                            <td>".htmlspecialchars($r['Nimi'])."</td>
                            <td>".htmlspecialchars($r['Kuvaus'])."</td>
                            <td>".htmlspecialchars($r['Alkupaiva'])."</td>
                            <td>".htmlspecialchars($r['Loppupaiva'])."</td>
                            <td>{$opettaja}</td>
                            <td>".htmlspecialchars($r['tila_nimi'])."</td>
                            <td>
                                <a class='update-button' href='update.php?table=kurssit&id={$r['Tunnus']}'>Update</a>
                                <a class='delete-button' href='delete.php?table=kurssit&id={$r['Tunnus']}'>Delete</a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>

            <h2 style="margin-top:70px;">Kirjautumiset</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Opiskelija</th><th>Kurssi</th><th>Päivämäärä</th><th>Toiminnot</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT kk.Tunnus, o.Etunimi, o.Sukunimi, k.Nimi AS kurssi_nimi, kk.Kirjautumispaiva
                            FROM kurssikirjautuminen kk
                            LEFT JOIN opiskelijat o ON kk.Opiskelija = o.Opiskelijanumero
                            LEFT JOIN kurssit k ON kk.Kurssi = k.Tunnus
                            ORDER BY kk.Tunnus";
                    $res2 = $yhteys->query($sql);
                    while($r2 = $res2->fetch(PDO::FETCH_ASSOC)) {
                        $opiskelijaNimi = htmlspecialchars($r2['Etunimi'] . ' ' . $r2['Sukunimi']);
                        echo "<tr>
                            <td>{$r2['Tunnus']}</td>
                            <td>{$opiskelijaNimi}</td>
                            <td>".htmlspecialchars($r2['kurssi_nimi'])."</td>
                            <td>".htmlspecialchars($r2['Kirjautumispaiva'])."</td>
                            <td>
                                <a class='update-button' href='update.php?table=kurssikirjautuminen&id={$r2['Tunnus']}'>Update</a>
                                <a class='delete-button' href='delete.php?table=kurssikirjautuminen&id={$r2['Tunnus']}'>Delete</a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

    <script>
        function toggleSection(id) {
            const sec = document.getElementById(id);
            if (sec.style.display === "block") {
                sec.style.display = "none";
            } else {
                sec.style.display = "block";
                // Voit sulkea muut avoinna olevat osiot, jos haluat
                const others = ['oppilaat-section','opettajat-section','hallinta-section'];
                others.forEach(function(other) {
                    if (other !== id) {
                        const o = document.getElementById(other);
                        if (o.style.display === "block") {
                            o.style.display = "none";
                        }
                    }
                });
            }
        }
    </script>

</body>
</html>