<?php
include 'yhteys.php';
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Tiedot</title>
    <link rel="stylesheet" href="Styles/styles.css">
</head>
<body>

    <div class="header">
        <a href="index.php" class="logo">Oppi</a>
        <div class="items">
            <a href="tiedot.php" style="text-decoration: underline;">Tiedot</a>
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
                <a href="add_teacher_enhanced.php" class="button" onclick="event.stopPropagation()">Lisää opettaja</a>
            </div>

            <div class="card" onclick="toggleSection('hallinta-section')">
                <h1>Tiedon hallinta</h1>
                <p>Kurssit ja kirjautumiset – lisää, muokkaa, poista.</p>
                <a href="add.php?table=kurssit" class="button" onclick="event.stopPropagation()">Lisää kurssi</a>
                 <a href="add_students_to_course.php" class="button" onclick="event.stopPropagation()">Lisää kirjautuminen</a>
            </div>

        </div>

        <!-- Oppilaat listaosio, sisältää hakupalkin -->
        <div id="oppilaat-section" class="section-content" style="display:none;">
            <h2>Oppilaat</h2>

            <!-- Hakupalkki -->
            <form method="GET" onsubmit="event.stopPropagation();" style="margin-bottom: 20px;">
                <input type="hidden" name="section" value="oppilaat-section">
                <input type="text" name="search" placeholder="Hae oppilasta nimellä tai ID:llä"
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="padding: 8px; width: 300px;">
                <button type="submit" style="padding: 8px;">Hae</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Etunimi</th><th>Sukunimi</th><th>Syntymäpäivä</th><th>Vuosikurssi</th><th>Toiminnot</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Hakuparametri
                    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

                    if ($search !== '') {
                        $searchParts = preg_split('/\s+/', $search);

                        if (count($searchParts) === 1) {
                            // Yksi sana - haetaan ID:llä tai etu- tai sukunimellä
                            $sql = "SELECT * FROM opiskelijat WHERE Opiskelijanumero = :search OR Etunimi LIKE :like_search OR Sukunimi LIKE :like_search";
                            $stmt = $yhteys->prepare($sql);
                            $stmt->execute([
                                ':search' => $search,
                                ':like_search' => '%' . $search . '%'
                            ]);
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        } else {
                            // Useampi sana - haetaan etu- ja sukunimellä
                            $firstName = $searchParts[0];
                            $lastName = $searchParts[1];
                            $sql = "SELECT * FROM opiskelijat WHERE Etunimi LIKE :firstname AND Sukunimi LIKE :lastname";
                            $stmt = $yhteys->prepare($sql);
                            $stmt->execute([
                                ':firstname' => '%' . $firstName . '%',
                                ':lastname' => '%' . $lastName . '%'
                            ]);
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                    } else {
                        // Näytetään kaikki, jos haku ei ole päällä
                        $sql = "SELECT * FROM opiskelijat ORDER BY Opiskelijanumero";
                        $result = $yhteys->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                    }

                    foreach($result as $row) {
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
        <div id="opettajat-section" class="section-content" style="display:none;">
            <h2>Opettajat</h2>

            <!-- Hakupalkki -->
            <form method="GET" onsubmit="event.stopPropagation();" style="margin-bottom: 20px;">
                <input type="hidden" name="section" value="opettajat-section">
                <input type="text" name="search_opettaja" placeholder="Hae opettajaa nimellä tai ID:llä"
                    value="<?php echo isset($_GET['search_opettaja']) ? htmlspecialchars($_GET['search_opettaja']) : ''; ?>" style="padding: 8px; width: 300px;">
                <button type="submit" style="padding: 8px;">Hae</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Etunimi</th>
                        <th>Sukunimi</th>
                        <th>Aine</th>
                        <th>Kurssit</th> <!-- uusi sarake -->
                        <th>Toiminnot</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $search = isset($_GET['search_opettaja']) ? trim($_GET['search_opettaja']) : '';
                    
                    if ($search !== '') {
                        $searchParts = preg_split('/\s+/', $search);

                        if (count($searchParts) === 1) {
                            $sql = "SELECT * FROM opettajat 
                                    WHERE Tunnusnumero = :search 
                                    OR Etunimi LIKE :like_search 
                                    OR Sukunimi LIKE :like_search";
                            $stmt = $yhteys->prepare($sql);
                            $stmt->execute([
                                ':search' => $search,
                                ':like_search' => '%' . $search . '%'
                            ]);
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        } else {
                            $firstName = $searchParts[0];
                            $lastName = $searchParts[1];
                            $sql = "SELECT * FROM opettajat 
                                    WHERE Etunimi LIKE :firstname 
                                    AND Sukunimi LIKE :lastname";
                            $stmt = $yhteys->prepare($sql);
                            $stmt->execute([
                                ':firstname' => '%' . $firstName . '%',
                                ':lastname' => '%' . $lastName . '%'
                            ]);
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                    } else {
                        $sql = "SELECT * FROM opettajat ORDER BY Tunnusnumero";
                        $result = $yhteys->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                    }

                    foreach($result as $row) {
                        // hae kaikki kurssit mitä tämä opettaja opettaa
                        $kurssiStmt = $yhteys->prepare("SELECT Nimi FROM kurssit WHERE Opettaja = :id");
                        $kurssiStmt->execute([':id' => $row['Tunnusnumero']]);
                        $kurssit = $kurssiStmt->fetchAll(PDO::FETCH_COLUMN);

                        // jos kursseja ei ole, näytetään "Ei kursseja"
                        $kurssiLista = $kurssit ? implode(", ", array_map('htmlspecialchars', $kurssit)) : "Ei kursseja";

                        echo "<tr>
                            <td>{$row['Tunnusnumero']}</td>
                            <td>".htmlspecialchars($row['Etunimi'])."</td>
                            <td>".htmlspecialchars($row['Sukunimi'])."</td>
                            <td>".htmlspecialchars($row['Aine'])."</td>
                            <td>{$kurssiLista}</td>
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

        <!-- Hallinta: Kurssit osio -->
        <div id="hallinta-section" class="section-content" style="display:none;">
            <h2>Kurssit</h2>

            <!-- Hakupalkki -->
            <form method="GET" onsubmit="event.stopPropagation();" style="margin-bottom: 20px;">
                <input type="hidden" name="section" value="hallinta-section">
                <input type="text" name="search_kurssi" placeholder="Hae kurssia nimellä tai ID:llä"
                    value="<?php echo isset($_GET['search_kurssi']) ? htmlspecialchars($_GET['search_kurssi']) : ''; ?>" style="padding: 8px; width: 300px;">
                <button type="submit" style="padding: 8px;">Hae</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Nimi</th><th>Kuvaus</th><th>Alkupäivä</th><th>Loppupäivä</th><th>Opettaja</th><th>Tila</th><th>Toiminnot</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $searchKurssi = isset($_GET['search_kurssi']) ? trim($_GET['search_kurssi']) : '';
                    $sql = "SELECT k.Tunnus, k.Nimi, k.Kuvaus, k.Alkupaiva, k.Loppupaiva,
                                o.Etunimi, o.Sukunimi,
                                t.Nimi AS tila_nimi
                            FROM kurssit k
                            LEFT JOIN opettajat o ON k.Opettaja = o.Tunnusnumero
                            LEFT JOIN tilat t ON k.Tila = t.Tunnus";
                    
                    if ($searchKurssi !== '') {
                        $searchParts = preg_split('/\s+/', $searchKurssi);
                        
                        if (count($searchParts) === 1) {
                            // Yksi sana - haetaan ID:llä, kurssinnimellä tai opettajan nimellä
                            $sql .= " WHERE k.Tunnus = :search OR k.Nimi LIKE :like_search 
                                     OR o.Etunimi LIKE :like_search OR o.Sukunimi LIKE :like_search";
                            $stmt = $yhteys->prepare($sql);
                            $stmt->execute([
                                ':search' => $searchKurssi,
                                ':like_search' => '%' . $searchKurssi . '%'
                            ]);
                            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        } else {
                            // Useampi sana - haetaan opettajan etu- ja sukunimellä
                            $firstName = $searchParts[0];
                            $lastName = $searchParts[1];
                            $sql .= " WHERE (o.Etunimi LIKE :firstname AND o.Sukunimi LIKE :lastname) 
                                     OR k.Nimi LIKE :fullname";
                            $stmt = $yhteys->prepare($sql);
                            $stmt->execute([
                                ':firstname' => '%' . $firstName . '%',
                                ':lastname' => '%' . $lastName . '%',
                                ':fullname' => '%' . $searchKurssi . '%'
                            ]);
                            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                    } else {
                        $sql .= " ORDER BY k.Tunnus";
                        $res = $yhteys->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                    }
                    foreach($res as $r) {
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

            <!-- Hakupalkki -->
            <form method="GET" onsubmit="event.stopPropagation();" style="margin-bottom: 20px;">
                <input type="hidden" name="section" value="hallinta-section">
                <input type="text" name="search_kirjautuminen" placeholder="Hae opiskelijaa tai kurssia"
                    value="<?php echo isset($_GET['search_kirjautuminen']) ? htmlspecialchars($_GET['search_kirjautuminen']) : ''; ?>" style="padding: 8px; width: 300px;">
                <button type="submit" style="padding: 8px;">Hae</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Opiskelija</th><th>Kurssi</th><th>Päivämäärä</th><th>Toiminnot</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $searchKirj = isset($_GET['search_kirjautuminen']) ? trim($_GET['search_kirjautuminen']) : '';
                    $sql = "SELECT kk.Tunnus, o.Etunimi, o.Sukunimi, k.Nimi AS kurssi_nimi, kk.Kirjautumispaiva
                            FROM kurssikirjautuminen kk
                            LEFT JOIN opiskelijat o ON kk.Opiskelija = o.Opiskelijanumero
                            LEFT JOIN kurssit k ON kk.Kurssi = k.Tunnus";
                    
                    if ($searchKirj !== '') {
                        $searchParts = preg_split('/\s+/', $searchKirj);
                        
                        if (count($searchParts) === 1) {
                            // Yksi sana - haetaan ID:llä, etunimellä, sukunimellä tai kurssin nimellä
                            $sql .= " WHERE kk.Tunnus = :search 
                                    OR o.Etunimi LIKE :like_search 
                                    OR o.Sukunimi LIKE :like_search 
                                    OR k.Nimi LIKE :like_search";
                            $stmt = $yhteys->prepare($sql);
                            $stmt->execute([
                                ':search' => $searchKirj,
                                ':like_search' => '%' . $searchKirj . '%'
                            ]);
                            $res2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        } else {
                            // Useampi sana - haetaan opiskelijan etu- ja sukunimellä
                            $firstName = $searchParts[0];
                            $lastName = $searchParts[1];
                            $sql .= " WHERE (o.Etunimi LIKE :firstname AND o.Sukunimi LIKE :lastname) 
                                     OR k.Nimi LIKE :fullname";
                            $stmt = $yhteys->prepare($sql);
                            $stmt->execute([
                                ':firstname' => '%' . $firstName . '%',
                                ':lastname' => '%' . $lastName . '%',
                                ':fullname' => '%' . $searchKirj . '%'
                            ]);
                            $res2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                    } else {
                        $sql .= " ORDER BY kk.Tunnus";
                        $res2 = $yhteys->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                    }
                    foreach($res2 as $r2) {
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
        function toggleSection(sectionId) {
            // Hide all sections first
            var sections = document.querySelectorAll('.section-content');
            sections.forEach(function(section) {
                section.style.display = 'none';
            });
            
            // Show the clicked section
            var targetSection = document.getElementById(sectionId);
            if (targetSection) {
                targetSection.style.display = 'block';
            }
        }

        // Check if we need to show a specific section based on URL parameters
        window.addEventListener('DOMContentLoaded', function() {
            var urlParams = new URLSearchParams(window.location.search);
            var section = urlParams.get('section');
            
            if (section) {
                toggleSection(section);
            }
        });
    </script>

</body>
</html>