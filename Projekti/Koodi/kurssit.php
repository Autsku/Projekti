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
    <link rel="stylesheet" href="Styles/styles.css">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kurssit</title>
    <style>
        .kurssit-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .kurssi-box {
            background-color: white;
            color: rgb(5, 54, 73);
            padding: 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border: 2px solid rgb(5, 54, 73);
            box-sizing: border-box;
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
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background-color: white;
            border: 2px solid rgb(5, 54, 73);
            border-top: none;
            border-radius: 0 0 8px 8px;
            padding: 20px;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .kurssi-box {
            background-color: white;
            color: rgb(5, 54, 73);
            padding: 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border: 2px solid rgb(5, 54, 73);
            box-sizing: border-box;
            position: relative;
        }

        .ilmoittautuneet-count {
            background-color: rgb(5, 54, 73);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9rem;
            display: inline-block;
        }

        .kurssi-tiedot table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
        }
        
        .kurssi-tiedot th, 
        .kurssi-tiedot td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }
        
        .kurssi-tiedot th {
            background-color: rgb(5, 54, 73);
            color: white;
        }

        .ei-opiskelijoita {
            font-style: italic;
            color: rgb(120, 120, 120);
            text-align: center;
            padding: 20px;
        }

        /* Responsiivinen design - yksi sarake pienillä näytöillä */
        @media (max-width: 768px) {
            .kurssit-container {
                grid-template-columns: 1fr;
                gap: 15px;
            }
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

        <div class="kurssit-container">
            <?php foreach ($kurssit as $kurssi): ?>
                <?php
                $kurssiId = 'kurssi_' . $kurssi['Tunnus'];

                // Haetaan ilmoittautuneiden määrä
                $stmt2 = $yhteys->prepare("SELECT COUNT(*) AS maara FROM kurssikirjautuminen WHERE Kurssi = ?");
                $stmt2->execute([$kurssi['Tunnus']]);
                $maara = $stmt2->fetch(PDO::FETCH_ASSOC)['maara'];
                ?>

                <div class="kurssi-box" onclick="toggleKurssi('<?= $kurssiId ?>')">
                    <div class="kurssi-nimi"><?= htmlspecialchars($kurssi['Nimi']) ?></div>
                    <p><strong>Opettaja:</strong> <?= htmlspecialchars($kurssi['Etunimi'] . ' ' . $kurssi['Sukunimi']) ?></p>
                    <p><strong>Tila:</strong> <?= htmlspecialchars($kurssi['tila_nimi']) ?></p>
                    <div class="ilmoittautuneet-count">
                        <?= $maara ?> ilmoittautunutta
                    </div>

                    <div class="kurssi-tiedot" id="<?= $kurssiId ?>">
                        <h3>Kurssin tiedot</h3>
                        <p><strong>Kuvaus:</strong> <?= nl2br(htmlspecialchars($kurssi['Kuvaus'])) ?></p>
                        <p><strong>Alkupäivä:</strong> <?= htmlspecialchars($kurssi['Alkupaiva']) ?></p>
                        <p><strong>Loppupäivä:</strong> <?= htmlspecialchars($kurssi['Loppupaiva']) ?></p>
                        <p><strong>Kurssi-ID:</strong> <?= htmlspecialchars($kurssi['Tunnus']) ?></p>

                        <h3>Ilmoittautuneet opiskelijat (<?= $maara ?>)</h3>

                        <?php
                        $sql3 = "SELECT o.Opiskelijanumero, o.Etunimi, o.Sukunimi, o.Syntymapaiva, o.Vuosikurssi, kk.Kirjautumispaiva
                                 FROM kurssikirjautuminen kk
                                 JOIN opiskelijat o ON kk.Opiskelija = o.Opiskelijanumero
                                 WHERE kk.Kurssi = ?
                                 ORDER BY o.Sukunimi, o.Etunimi";
                        $stmt3 = $yhteys->prepare($sql3);
                        $stmt3->execute([$kurssi['Tunnus']]);
                        $opiskelijat = $stmt3->fetchAll(PDO::FETCH_ASSOC);
                        ?>

                        <?php if (empty($opiskelijat)): ?>
                            <div class="ei-opiskelijoita">Ei vielä ilmoittautuneita opiskelijoita.</div>
                        <?php else: ?>
                            <table>
                                <tr>
                                    <th>Opiskelijanumero</th>
                                    <th>Nimi</th>
                                    <th>Syntymäpäivä</th>
                                    <th>Vuosikurssi</th>
                                    <th>Ilmoittautumispäivä</th>
                                </tr>
                                <?php foreach ($opiskelijat as $op): ?>
                                <tr>
                                    <td><?= htmlspecialchars($op['Opiskelijanumero']) ?></td>
                                    <td><?= htmlspecialchars($op['Etunimi'] . ' ' . $op['Sukunimi']) ?></td>
                                    <td><?= htmlspecialchars($op['Syntymapaiva']) ?></td>
                                    <td><?= htmlspecialchars($op['Vuosikurssi']) ?></td>
                                    <td><?= htmlspecialchars($op['Kirjautumispaiva']) ?></td>
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
        function toggleKurssi(kurssiId) {
            // Piilota kaikki muut kurssit ensin
            var allTiedot = document.querySelectorAll('.kurssi-tiedot');
            allTiedot.forEach(function(tiedot) {
                if (tiedot.id !== kurssiId) {
                    tiedot.style.display = 'none';
                }
            });
            
            // Toggle klikkattua kurssia
            var targetKurssi = document.getElementById(kurssiId);
            if (targetKurssi) {
                targetKurssi.style.display = (targetKurssi.style.display === 'block') ? 'none' : 'block';
            }
        }

        // Piilota tiedot kun klikataan muualle
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.kurssi-box')) {
                var allTiedot = document.querySelectorAll('.kurssi-tiedot');
                allTiedot.forEach(function(tiedot) {
                    tiedot.style.display = 'none';
                });
            }
        });
    </script>
</body>
</html>