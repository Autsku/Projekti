<?php
include 'yhteys.php';

// Haetaan kaikki tilat
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
        <div class="tilat-container">
            <?php foreach ($tilat as $tila): ?>
                <?php
                // Haetaan kurssit tähän tilaan
                $sql2 = "SELECT k.Tunnus, k.Nimi, k.Alkupaiva, k.Loppupaiva,
                                o.Etunimi, o.Sukunimi
                         FROM kurssit k
                         LEFT JOIN opettajat o ON k.Opettaja = o.Tunnusnumero
                         WHERE k.Tila = ?
                         ORDER BY k.Nimi";
                $stmt2 = $yhteys->prepare($sql2);
                $stmt2->execute([$tila['Tunnus']]);
                $kurssit = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                // Lasketaan kurssien määrä
                $kurssien_maara = count($kurssit);
                ?>

                <div class="tila-box" onclick="toggleTila('tila_<?= $tila['Tunnus'] ?>')">
                    <div class="tila-nimi"><?= htmlspecialchars($tila['Nimi']) ?></div>
                    <p><strong>Kapasiteetti:</strong> <?= htmlspecialchars($tila['Kapasiteetti']) ?></p>
                    <p><strong>Kurssien määrä:</strong> <?= $kurssien_maara ?></p>

                    <div class="tila-tiedot" id="tila_<?= $tila['Tunnus'] ?>">
                        <?php if (empty($kurssit)): ?>
                            <p><em>Ei kursseja tässä tilassa.</em></p>
                        <?php else: ?>
                            <table>
                                <tr>
                                    <th>Kurssi</th>
                                    <th>Opettaja</th>
                                    <th>Alkupäivä</th>
                                    <th>Loppupäivä</th>
                                    <th>Osallistujat</th>
                                </tr>
                                <?php foreach ($kurssit as $kurssi): ?>
                                    <?php
                                    $sql3 = "SELECT COUNT(*) AS osallistujia FROM kurssikirjautuminen WHERE Kurssi = ?";
                                    $stmt3 = $yhteys->prepare($sql3);
                                    $stmt3->execute([$kurssi['Tunnus']]);
                                    $osallistujat = $stmt3->fetch(PDO::FETCH_ASSOC)['osallistujia'];
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
                                                <span class="warning" title="Osallistujamäärä ylittää kapasiteetin">&#9888;</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function toggleTila(tilaId) {
            let all = document.querySelectorAll('.tila-tiedot');
            all.forEach(function(t) {
                if (t.id !== tilaId) {
                    t.style.display = 'none';
                }
            });
            let target = document.getElementById(tilaId);
            if (target) {
                target.style.display = (target.style.display === 'block') ? 'none' : 'block';
            }
        }

        // Piilota jos klikataan muualle
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.tila-box')) {
                document.querySelectorAll('.tila-tiedot').forEach(function(t) {
                    t.style.display = 'none';
                });
            }
        });
    </script>
</body>
</html>