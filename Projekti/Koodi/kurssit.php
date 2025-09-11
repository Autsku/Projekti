<?php
include 'yhteys.php';

$sql = "SELECT k.Tunnus, k.Nimi, k.Kuvaus, k.Alkupaiva, k.Loppupaiva,
               o.Etunimi, o.Sukunimi,
               t.Nimi AS tila_nimi
        FROM kurssit k
        LEFT JOIN opettajat o ON k.Opettaja = o.Tunnusnumero
        LEFT JOIN tilat t ON k.Tila = t.Tunnus
        ORDER BY k.Nimi";
$stmt = $yhteys->query($sql);
$kurssit = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kurssit</title>
</head>
<body>
    <div class="header">
        <a href="index.php" class="logo">Logo</a>
        <div class="items">
            <a href="tiedot.php">Tiedot</a>
            <a href="tilat.php">Tilat</a>
            <a href="kurssit.php">Kurssit</a>
            <a href="opiskelijat.php">Opiskelijat</a>
            <a href="opettajat.php">Opettajat</a>
        </div>
    </div>

    <div class="content">
        <h1 class="kurs">Kurssit</h1>

        <?php foreach ($kurssit as $kurssi): ?>
            <div class="course-box" style="border:1px solid white; padding:10px; margin-bottom:20px;">
                <h2><?= htmlspecialchars($kurssi['Nimi']) ?></h2>
                <p><strong>Kuvaus:</strong> <?= nl2br(htmlspecialchars($kurssi['Kuvaus'])) ?></p>
                <p><strong>Alkupäivä:</strong> <?= htmlspecialchars($kurssi['Alkupaiva']) ?></p>
                <p><strong>Loppupäivä:</strong> <?= htmlspecialchars($kurssi['Loppupaiva']) ?></p>
                <p><strong>Opettaja:</strong> <?= htmlspecialchars($kurssi['Etunimi'] . ' ' . $kurssi['Sukunimi']) ?></p>
                <p><strong>Tila:</strong> <?= htmlspecialchars($kurssi['tila_nimi']) ?></p>

                <h3>Ilmoittautuneet opiskelijat</h3>

                <?php
                $sql2 = "SELECT o.Etunimi, o.Sukunimi, o.Vuosikurssi
                         FROM kurssikirjautuminen kk
                         JOIN opiskelijat o ON kk.Opiskelija = o.Opiskelijanumero
                         WHERE kk.Kurssi = ?";
                $stmt2 = $yhteys->prepare($sql2);
                $stmt2->execute([$kurssi['Tunnus']]);
                $opiskelijat = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <?php if (empty($opiskelijat)): ?>
                    <p>Ei vielä ilmoittautuneita opiskelijoita.</p>
                <?php else: ?>
                    <table border="1" cellpadding="5" style="width:100%; max-width:600px;">
                        <tr>
                            <th>Nimi</th>
                            <th>Vuosikurssi</th>
                        </tr>
                        <?php foreach ($opiskelijat as $op): ?>
                        <tr>
                            <td><?= htmlspecialchars($op['Etunimi'] . ' ' . $op['Sukunimi']) ?></td>
                            <td><?= htmlspecialchars($op['Vuosikurssi']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>

                    <p><strong>Opiskelijoita yhteensä:</strong> <?= count($opiskelijat) ?></p>

                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
