<?php include 'yhteys.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opiskelijat</title>
</head>
<body>
    <div class="header">
        <a href="index.php" class="logo">Logo</a>
        <div class="items">
            <a href="tiedot.php">Tiedot</a>
            <a href="tilat.php">Tilat</a>
            <a href="kurssit.php">Kurssit</a>
            <a href="opettajat.php">Opettajat</a>
        </div>
    </div>

    <div class="content">
        <h2>Opiskelijat</h2>

        <table border="1" cellpadding="10">
            <tr>
                <th>Opiskelijanumero</th>
                <th>Nimi</th>
                <th>Syntymäpäivä</th>
                <th>Vuosikurssi</th>
            </tr>

            <?php
            $sql = "SELECT * FROM Opiskelijat";
            $stmt = $yhteys->query($sql);
            $opiskelijat = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($opiskelijat as $op) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($op['Opiskelijanumero']) . "</td>";
                echo "<td>" . htmlspecialchars($op['Etunimi']) . " " . htmlspecialchars($op['Sukunimi']) . "</td>";
                echo "<td>" . htmlspecialchars($op['Syntymapaiva']) . "</td>";
                echo "<td>" . htmlspecialchars($op['Vuosikurssi']) . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
