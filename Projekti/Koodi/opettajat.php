<?php
include 'yhteys.php';

$sql = "SELECT Tunnusnumero, Etunimi, Sukunimi, Aine FROM opettajat ORDER BY Sukunimi, Etunimi";
$stmt = $yhteys->query($sql);
$opettajat = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <link rel="stylesheet" href="Styles/styles.css">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Opettajat</title>
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
        <h1>Opettajat ja heidän kurssinsa</h1>

        <?php foreach ($opettajat as $opettaja): ?>
            <div class="teacher-box" style="border:8px outset #ccc; padding:7px; margin-bottom:20px;">
                <h2><?= htmlspecialchars($opettaja['Etunimi'] . ' ' . $opettaja['Sukunimi']) ?></h2>
                <p><strong>Aine:</strong> <?= htmlspecialchars($opettaja['Aine']) ?></p>

                <?php
                $sql2 = "SELECT k.Nimi, k.Alkupaiva, k.Loppupaiva, t.Nimi AS tila_nimi
                         FROM kurssit k
                         LEFT JOIN tilat t ON k.Tila = t.Tunnus
                         WHERE k.Opettaja = ?
                         ORDER BY k.Alkupaiva";
                $stmt2 = $yhteys->prepare($sql2);
                $stmt2->execute([$opettaja['Tunnusnumero']]);
                $kurssit = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <?php if (empty($kurssit)): ?>
                    <p>Tällä opettajalla ei ole vielä kursseja.</p>
                <?php else: ?>
                    <table border="1" cellpadding="5" style="width:100%; max-width:800px; margin-top:10px;">
                        <tr>
                            <th>Kurssi</th>
                            <th>Alkupäivä</th>
                            <th>Loppupäivä</th>
                            <th>Tila</th>
                        </tr>
                        <?php foreach ($kurssit as $kurssi): ?>
                        <tr>
                            <td><?= htmlspecialchars($kurssi['Nimi']) ?></td>
                            <td><?= htmlspecialchars($kurssi['Alkupaiva']) ?></td>
                            <td><?= htmlspecialchars($kurssi['Loppupaiva']) ?></td>
                            <td><?= htmlspecialchars($kurssi['tila_nimi']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>