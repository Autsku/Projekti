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
    <link rel="stylesheet" href="Styles/styles.css">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tilat</title>
    <style>
        .warning { color: red; font-weight: bold; }
        table {
            border-collapse: collapse;
            width: 100%;
            max-width: 900px;
            margin-bottom: 40px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }
        .tila-header {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 1.2em;
        }
        /* Lisätty wrapperille padding */
        .wrapper {
            padding: 20px;
        }
    </style>
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

    <div class="wrapper">
        <div class="content">
            <h1>Tilat</h1>

            <?php foreach ($tilat as $tila): ?>
                <table>
                    <tr class="tila-header">
                        <td colspan="5">
                            <?= htmlspecialchars($tila['Nimi']) ?> — Kapasiteetti: <?= htmlspecialchars($tila['Kapasiteetti']) ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Kurssi</th>
                        <th>Opettaja</th>
                        <th>Alkupäivä</th>
                        <th>Loppupäivä</th>
                        <th>Osallistujia / Kapasiteetti</th>
                    </tr>

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

                    if (empty($kurssit)): ?>
                        <tr>
                            <td colspan="5">Ei tällä hetkellä kursseja tässä tilassa.</td>
                        </tr>
                    <?php else: 
                        foreach ($kurssit as $kurssi):
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
                                <?= $osallistujat ?> / <?= htmlspecialchars($tila['Kapasiteetti']) ?>
                                <?php if ($varoitus): ?>
                                    <span class="warning" title="Osallistujamäärä ylittää tilan kapasiteetin">&#9888;</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php 
                        endforeach; 
                    endif; ?>
                </table>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>