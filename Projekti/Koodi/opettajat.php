<?php
include 'yhteys.php';

// Haetaan hakusana
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Valmistellaan SQL-haku
if ($search !== '') {
    $sql = "SELECT Tunnusnumero, Etunimi, Sukunimi, Aine
            FROM opettajat
            WHERE Etunimi LIKE ? OR Sukunimi LIKE ?
            ORDER BY Sukunimi, Etunimi";
    $stmt = $yhteys->prepare($sql);
    $stmt->execute(['%' . $search . '%', '%' . $search . '%']);
} else {
    $sql = "SELECT Tunnusnumero, Etunimi, Sukunimi, Aine
            FROM opettajat
            ORDER BY Sukunimi, Etunimi";
    $stmt = $yhteys->query($sql);
}

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
<<<<<<< HEAD
    <div class="header">
        <a href="index.php" class="logo">Oppi</a>
        <div class="items">
            <a href="tiedot.php">Tiedot</a>
            <a href="tilat.php">Tilat</a>
            <a href="kurssit.php">Kurssit</a>
            <a href="opiskelijat.php">Opiskelijat</a>
            <a href="opettajat.php" style="text-decoration: underline;">Opettajat</a>
        </div>
=======
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

    <!-- Keskitetty hakupalkki ylös -->
    <div class="search-container">
        <form method="GET">
            <input type="text" name="search" placeholder="Hae opettajaa nimellä" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Hae</button>
        </form>
>>>>>>> 7cedff472cb79622dad4abdc65944a86ef98c05a
    </div>

    <div class="teachers-container">
        <?php foreach ($opettajat as $opettaja): ?>
            <?php
            $teacherId = 'teacher_' . $opettaja['Tunnusnumero'];

            // Haetaan kurssit
            $sql2 = "SELECT k.Nimi, k.Alkupaiva, k.Loppupaiva, t.Nimi AS tila_nimi
                     FROM kurssit k
                     LEFT JOIN tilat t ON k.Tila = t.Tunnus
                     WHERE k.Opettaja = ?
                     ORDER BY k.Alkupaiva";
            $stmt2 = $yhteys->prepare($sql2);
            $stmt2->execute([$opettaja['Tunnusnumero']]);
            $kurssit = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            ?>
            
            <div class="teacher-box" onclick="toggleTeacher('<?= $teacherId ?>')">
                <h2><?= htmlspecialchars($opettaja['Etunimi'] . ' ' . $opettaja['Sukunimi']) ?></h2>
                <p><strong>Aine:</strong> <?= htmlspecialchars($opettaja['Aine']) ?></p>
                <p><strong>Kurssien määrä:</strong> <?= count($kurssit) ?></p>

                <div class="teacher-details" id="<?= $teacherId ?>">
                    <?php if (empty($kurssit)): ?>
                        <p><em>Tällä opettajalla ei ole vielä kursseja.</em></p>
                    <?php else: ?>
                        <table>
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
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function toggleTeacher(teacherId) {
    document.querySelectorAll('.teacher-details').forEach(d => {
        if (d.id !== teacherId) d.style.display = 'none';
    });
    const target = document.getElementById(teacherId);
    if (target) target.style.display = (target.style.display === 'block') ? 'none' : 'block';
}

document.addEventListener('click', e => {
    if (!e.target.closest('.teacher-box')) {
        document.querySelectorAll('.teacher-details').forEach(d => d.style.display = 'none');
    }
});
</script>
</body>
</html>
