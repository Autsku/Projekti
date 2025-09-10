<?php
include 'yhteys.php';

// Haetaan tilat tietokannasta
$sql = "SELECT Tunnus, Nimi, Kapasiteetti FROM tilat ORDER BY Nimi";
$stmt = $yhteys->query($sql);
$tilat = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tilat</title>
    <style>
        .warning { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php" class="logo">Logo</a>
        <div class="items">
            <a href="tiedot.php">Tiedot</a>
            <a href="tilat.php">Tilat</a>
            <a href="opiskelijat.php">Opiskelijat</a>
            <a href="opettajat.php">Opettajat</a>
        </div>
    </div>

    <div class="content">
        <h1>Tilat</h1>

        <?php foreach ($tilat as $tila): ?>
            <div class="course-box" style="border:1px solid #ccc; padding:10px; margin-bottom:20px;">
                <h2><?= htmlspecialchars($tila['Nimi']) ?></h2>
                <p><strong>Kapasiteetti:</strong> <?= htmlspecialchars($tila['Kapasiteetti']) ?></p>

                <h3>Kurssit tässä tilassa</h3>

                <?php
                // Haetaan kurssit ja opettajat tilassa
                $sql2 = "SELECT k.Tunnus, k.Nimi, k.Alkupaiva, k.Loppupaiva,
                                o.Etunimi, o.Sukunimi
                         FROM kurssit k
                         LEFT JOIN opettajat o ON k.Opettaja = o.Tunnusnumero
                         WHERE k.Tila = ?
                         ORDER BY k.Nimi";
                $stmt2 = $yhteys->prepare($sql2);
                $stmt2->execute([$tila['Tunnus']]);
                $kurssit = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <?php if (empty($kurssit)): ?>
                    <p>Ei tällä hetkellä kursseja tässä tilassa.</p>
                <?php else: ?>
                    <table border="1" cellpadding="5" style="width:100%; max-width:800px;">
                        <tr>
                            <th>Kurssi</th>
                            <th>Opettaja</th>
                            <th>Alkupäivä</th>
                            <th>Loppupäivä</th>
                            <th>Osallistujia</th>
                        </tr>
                        <?php foreach ($kurssit as $kurssi): ?>
                            <?php
                            // Lasketaan osallistujien määrä
                            $sql3 = "SELECT COUNT(*) AS osallistujia FROM kurssikirjautuminen WHERE Kurssi = ?";
                            $stmt3 = $yhteys->prepare($sql3);
                            $stmt3->execute([$kurssi['Tunnus']]);
                            $osallistujat = $stmt3->fetch(PDO::FETCH_ASSOC)['osallistujia'];

                            // Tarkistetaan, ylittääkö osallistujamäärä kapasiteetin
                            $varoitus = $osallistujat > $tila['Kapasiteetti'];
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($kurssi['Nimi']) ?></td>
                                <td><?= htmlspecialchars($kurssi['Etunimi'] . ' ' . $kurssi['Sukunimi']) ?></td>
                                <td><?= htmlspecialchars($kurssi['Alkupaiva']) ?></td>
                                <td><?= htmlspecialchars($kurssi['Loppupaiva']) ?></td>
                                <td>
                                    <?= $osallistujat ?>
                                    <?php if ($varoitus): ?>
                                        <span class="warning">&#9888;</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>