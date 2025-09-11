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
    <style>
        .kurssi-box {
            background-color: white;
            color: rgb(5, 54, 73);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;

            border: 2px solid rgb(5, 54, 73);  /* reuna boksille */
            max-width: 1000px;         /* rajoitetaan maksimileveyttä */
            box-sizing: border-box;   /* jotta padding sisältyy leveyteen */
        }

        .kurssi-box:hover {
            background-color: rgb(240, 240, 240);
        }

        .kurssi-nimi {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .kurssi-tiedot {
            display: none;
            margin-top: 10px;
            border-top: 1px solid rgb(5, 54, 73);
            padding-top: 10px;
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

    <div class="content">
        <h1>Kurssit</h1>

        <?php foreach ($kurssit as $kurssi): ?>
            <?php
            $kurssiId = 'kurssi_' . $kurssi['Tunnus'];

            // Haetaan ilmoittautuneiden määrä
            $stmt2 = $yhteys->prepare("SELECT COUNT(*) AS maara FROM kurssikirjautuminen WHERE Kurssi = ?");
            $stmt2->execute([$kurssi['Tunnus']]);
            $maara = $stmt2->fetch(PDO::FETCH_ASSOC)['maara'];
            ?>

            <div class="kurssi-box" onclick="toggleVisibility('<?= $kurssiId ?>')">
                <div class="kurssi-nimi"><?= htmlspecialchars($kurssi['Nimi']) ?></div>
                <p><strong>Opettaja:</strong> <?= htmlspecialchars($kurssi['Etunimi'] . ' ' . $kurssi['Sukunimi']) ?></p>
                <p><strong>Tila:</strong> <?= htmlspecialchars($kurssi['tila_nimi']) ?></p>
                <p><strong>Ilmoittautuneita:</strong> <?= $maara ?></p>

                <div class="kurssi-tiedot" id="<?= $kurssiId ?>">
                    <p><strong>Kuvaus:</strong> <?= nl2br(htmlspecialchars($kurssi['Kuvaus'])) ?></p>
                    <p><strong>Alkupäivä:</strong> <?= htmlspecialchars($kurssi['Alkupaiva']) ?></p>
                    <p><strong>Loppupäivä:</strong> <?= htmlspecialchars($kurssi['Loppupaiva']) ?></p>

                    <h3>Ilmoittautuneet opiskelijat</h3>

                    <?php
                    $sql3 = "SELECT o.Etunimi, o.Sukunimi, o.Vuosikurssi
                             FROM kurssikirjautuminen kk
                             JOIN opiskelijat o ON kk.Opiskelija = o.Opiskelijanumero
                             WHERE kk.Kurssi = ?";
                    $stmt3 = $yhteys->prepare($sql3);
                    $stmt3->execute([$kurssi['Tunnus']]);
                    $opiskelijat = $stmt3->fetchAll(PDO::FETCH_ASSOC);
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
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function toggleVisibility(id) {
            const el = document.getElementById(id);
            el.style.display = (el.style.display === "block") ? "none" : "block";
        }
    </script>
</body>
</html>
