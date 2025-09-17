<?php
include 'yhteys.php';

// Haetaan hakusana
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Valmistellaan SQL-haku
if ($search !== '') {
    $sql = "SELECT k.Tunnus, k.Nimi, k.Kuvaus, k.Alkupaiva, k.Loppupaiva,
                   o.Etunimi, o.Sukunimi,
                   t.Nimi AS tila_nimi
            FROM kurssit k
            LEFT JOIN opettajat o ON k.Opettaja = o.Tunnusnumero
            LEFT JOIN tilat t ON k.Tila = t.Tunnus
            WHERE k.Nimi LIKE ?
            ORDER BY k.Nimi";
    $stmt = $yhteys->prepare($sql);
    $stmt->execute(['%' . $search . '%']);
} else {
    $sql = "SELECT k.Tunnus, k.Nimi, k.Kuvaus, k.Alkupaiva, k.Loppupaiva,
                   o.Etunimi, o.Sukunimi,
                   t.Nimi AS tila_nimi
            FROM kurssit k
            LEFT JOIN opettajat o ON k.Opettaja = o.Tunnusnumero
            LEFT JOIN tilat t ON k.Tila = t.Tunnus
            ORDER BY k.Nimi";
    $stmt = $yhteys->query($sql);
}

$kurssit = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="Styles/styles.css">
    <title>Kurssit</title>
</head>
<body>
<div class="header">
    <a href="index.php" class="logo">Oppi</a>
    <div class="items">
        <a href="tiedot.php">Tiedot</a>
        <a href="tilat.php">Tilat</a>
        <a href="kurssit.php" style="text-decoration: underline;">Kurssit</a>
        <a href="opiskelijat.php">Opiskelijat</a>
        <a href="opettajat.php">Opettajat</a>
    </div>
</div>

<div class="content">

    <!-- Keskitetty hakupalkki ylös -->
    <div class="search-container">
        <form method="GET">
            <input type="text" name="search" placeholder="Hae kurssia nimellä" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Hae</button>
        </form>
    </div>

    <div class="kurssit-container">
        <?php foreach ($kurssit as $kurssi): ?>
            <?php
            $kurssiId = 'kurssi_' . $kurssi['Tunnus'];
            $stmt2 = $yhteys->prepare("SELECT COUNT(*) AS maara FROM kurssikirjautuminen WHERE Kurssi = ?");
            $stmt2->execute([$kurssi['Tunnus']]);
            $maara = $stmt2->fetch(PDO::FETCH_ASSOC)['maara'];
            ?>

            <div class="kurssi-box" onclick="toggleKurssi('<?= $kurssiId ?>')">
                <div class="kurssi-nimi"><?= htmlspecialchars($kurssi['Nimi']) ?></div>
                <p><strong>Opettaja:</strong> <?= htmlspecialchars($kurssi['Etunimi'] . ' ' . $kurssi['Sukunimi']) ?></p>
                <p><strong>Tila:</strong> <?= htmlspecialchars($kurssi['tila_nimi']) ?></p>
                <div class="ilmoittautuneet-count"><?= $maara ?> ilmoittautunutta</div>

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
    document.querySelectorAll('.kurssi-tiedot').forEach(tiedot => {
        if (tiedot.id !== kurssiId) tiedot.style.display = 'none';
    });
    const target = document.getElementById(kurssiId);
    if (target) target.style.display = (target.style.display === 'block') ? 'none' : 'block';
}

document.addEventListener('click', e => {
    if (!e.target.closest('.kurssi-box')) {
        document.querySelectorAll('.kurssi-tiedot').forEach(tiedot => tiedot.style.display = 'none');
    }
});
</script>
</body>
</html>