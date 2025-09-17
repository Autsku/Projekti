<?php
include 'yhteys.php';

// Haetaan hakusana
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Hakulogiikka: etunimi + sukunimi yhdistelmä
if ($search !== '') {
    $searchParts = preg_split('/\s+/', $search);
    if (count($searchParts) >= 2) {
        $firstName = $searchParts[0];
        $lastName = $searchParts[1];

        $sql = "
            SELECT * FROM opiskelijat
            WHERE Etunimi LIKE :firstname AND Sukunimi LIKE :lastname
               OR Opiskelijanumero LIKE :search
            ORDER BY Opiskelijanumero
        ";
        $stmt = $yhteys->prepare($sql);
        $stmt->execute([
            ':firstname' => '%' . $firstName . '%',
            ':lastname'  => '%' . $lastName . '%',
            ':search'    => $search
        ]);
    } else {
        $sql = "
            SELECT * FROM opiskelijat
            WHERE Etunimi LIKE :like_search
               OR Sukunimi LIKE :like_search
               OR Opiskelijanumero LIKE :search
            ORDER BY Opiskelijanumero
        ";
        $stmt = $yhteys->prepare($sql);
        $stmt->execute([
            ':like_search' => '%' . $search . '%',
            ':search' => $search
        ]);
    }
} else {
    $sql = "SELECT * FROM opiskelijat ORDER BY Opiskelijanumero";
    $stmt = $yhteys->query($sql);
}

$opiskelijat = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Opiskelijat</title>
    <link rel="stylesheet" href="Styles/styles.css">
    <style>
        .opiskelijat-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .opiskelijat-list li {
            background-color: white;
            color: rgb(5,54,73);
            margin-bottom: 10px;
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid rgb(5,54,73);
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        .opiskelijat-list li:hover {
            background-color: rgb(240,240,240);
        }
        .opiskelija-header {
            font-weight: bold;
        }
        .opiskelija-id {
            font-style: italic;
            margin-right: 8px;
        }
        .student-details {
            display: none;
            margin-top: 8px;
            padding: 10px;
            background-color: #f8f8f8;
            border-radius: 6px;
        }
        .student-details h3 {
            margin: 0 0 8px 0;
        }
        .student-details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        .student-details th, .student-details td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }
        .student-details th {
            background-color: rgb(5,54,73);
            color: white;
        }
        .show-more {
            margin-top: 5px;
            display: inline-block;
            font-size: 0.9em;
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }
        .course-count {
            display: inline-block;
            background-color: rgb(5, 54, 73);
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.9em;
            margin-top: 5px;
        }

    </style>
</head>
<body>
<div class="header">
    <a href="index.php" class="logo">Oppi</a>
    <div>
        <a href="tiedot.php">Tiedot</a>
        <a href="tilat.php">Tilat</a>
        <a href="kurssit.php">Kurssit</a>
        <a href="opiskelijat.php" style="text-decoration: underline;">Opiskelijat</a>
        <a href="opettajat.php">Opettajat</a>
    </div>
</div>

<div class="content">

    <div class="search-container">
        <form method="GET">
            <input type="text" name="search" placeholder="Hae opiskelijaa nimellä tai ID:llä" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Hae</button>
        </form>
    </div>

    <ul class="opiskelijat-list">
        <?php foreach ($opiskelijat as $op): ?>
            <?php
            $opId = $op['Opiskelijanumero'];
            $studentId = 'student_' . $opId;

            $stmt2 = $yhteys->prepare("
                SELECT k.Nimi, kk.Kirjautumispaiva
                FROM kurssikirjautuminen kk
                JOIN kurssit k ON kk.Kurssi = k.Tunnus
                WHERE kk.Opiskelija = ?
                ORDER BY kk.Kirjautumispaiva
            ");
            $stmt2->execute([$opId]);
            $kurssit = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            ?>
            
            <li onclick="toggleStudent('<?= $studentId ?>', event)">
                <div class="opiskelija-header">
                    <span class="opiskelija-id">ID: <?= $opId ?></span>
                    <?= htmlspecialchars($op['Etunimi'] . ' ' . $op['Sukunimi']) ?>
                </div>

                <div class="course-count">
                    <?= count($kurssit) ?> kurssi<?= count($kurssit) !== 1 ? 'a' : '' ?>
                </div>

                <div class="student-details" id="<?= $studentId ?>">
                    <h3><?= htmlspecialchars($op['Etunimi'] . ' ' . $op['Sukunimi']) ?></h3>
                    <p><strong>Syntymäpäivä:</strong> <?= htmlspecialchars($op['Syntymapaiva']) ?></p>
                    <p><strong>Vuosikurssi:</strong> <?= htmlspecialchars($op['Vuosikurssi']) ?></p>
                    <?php if (empty($kurssit)): ?>
                        <p><em>Ei kursseja.</em></p>
                    <?php else: ?>
                        <table>
                            <tr>
                                <th>Kurssi</th>
                                <th>Alkupäivä</th>
                            </tr>
                            <?php foreach ($kurssit as $kurssi): ?>
                                <tr>
                                    <td><?= htmlspecialchars($kurssi['Nimi']) ?></td>
                                    <td><?= htmlspecialchars($kurssi['Kirjautumispaiva']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                </div>
            </li>

        <?php endforeach; ?>
    </ul>

</div>

<script>
function toggleStudent(studentId, event) {
    event.stopPropagation();
    document.querySelectorAll('.student-details').forEach(d => {
        if (d.id !== studentId) d.style.display = 'none';
    });
    const target = document.getElementById(studentId);
    target.style.display = (target.style.display === 'block') ? 'none' : 'block';
}

document.addEventListener('click', e => {
    if (!e.target.closest('.opiskelijat-list li')) {
        document.querySelectorAll('.student-details').forEach(d => d.style.display = 'none');
    }
});

</script>

</body>
</html>
