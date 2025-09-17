<?php include 'yhteys.php'; ?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <link rel="stylesheet" href="Styles/styles.css">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Opiskelijat</title>
</head>
<body>
    <div class="header">
        <a href="index.php" class="logo">Oppi</a>
        <div class="items">
            <a href="tiedot.php">Tiedot</a>
            <a href="tilat.php">Tilat</a>
            <a href="kurssit.php">Kurssit</a>
            <a href="opiskelijat.php" style="text-decoration: underline;">Opiskelijat</a>
            <a href="opettajat.php">Opettajat</a>
        </div>
    </div>

    <div class="content">
        <h1>Opiskelijat</h1>
        <table border="1" cellpadding="10">
            <tr>
                <th>OpiskelijaID</th>
                <th>Nimi</th>
                <th>Syntymäpäivä</th>
                <th>Vuosikurssi</th>
                <th>Kurssit</th>
                <th>Aloituspäivä</th>
            </tr>

            <?php
            $sql = "
                SELECT 
                    o.Opiskelijanumero,
                    CONCAT(o.Etunimi, ' ', o.Sukunimi) AS Nimi,
                    o.Syntymapaiva,
                    o.Vuosikurssi,
                    GROUP_CONCAT(k.Nimi SEPARATOR ', ') AS Kurssit,
                    MIN(kk.Kirjautumispaiva) AS Aloituspaiva
                FROM opiskelijat o
                LEFT JOIN kurssikirjautuminen kk ON o.Opiskelijanumero = kk.Opiskelija
                LEFT JOIN kurssit k ON kk.Kurssi = k.Tunnus
                GROUP BY o.Opiskelijanumero
                ORDER BY o.Opiskelijanumero
            ";
            $stmt = $yhteys->query($sql);
            $opiskelijat = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($opiskelijat as $op) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($op['Opiskelijanumero']) . "</td>";
                echo "<td>" . htmlspecialchars($op['Nimi']) . "</td>";
                echo "<td>" . htmlspecialchars($op['Syntymapaiva']) . "</td>";
                echo "<td>" . htmlspecialchars($op['Vuosikurssi']) . "</td>";
                echo "<td>" . htmlspecialchars($op['Kurssit'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($op['Aloituspaiva'] ?? '') . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
