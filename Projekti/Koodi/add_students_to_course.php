<?php
include("yhteys.php");

$success_message = '';
$error_message = '';
$warning_message = '';

if ($_POST) {
    try {
        $yhteys->beginTransaction();

        $kurssi = $_POST['kurssi'];
        $kirjautumispaiva = $_POST['kirjautumispaiva'] ?? date('Y-m-d');
        $assignedStudents = [];

        if (!empty($_POST['opiskelijat'])) {
            foreach ($_POST['opiskelijat'] as $opiskelijaId) {
                // Tarkista, onko jo kirjautunut
                $checkSql = "SELECT COUNT(*) FROM kurssikirjautuminen WHERE Opiskelija = ? AND Kurssi = ?";
                $checkStmt = $yhteys->prepare($checkSql);
                $checkStmt->execute([$opiskelijaId, $kurssi]);

                if ($checkStmt->fetchColumn() == 0) {
                    $sql = "INSERT INTO kurssikirjautuminen (Opiskelija, Kurssi, Kirjautumispaiva) VALUES (?, ?, ?)";
                    $stmt = $yhteys->prepare($sql);
                    $stmt->execute([$opiskelijaId, $kurssi, $kirjautumispaiva]);
                    $assignedStudents[] = $opiskelijaId;
                } else {
                    $warning_message .= "⚠️ Opiskelija ID {$opiskelijaId} on jo kirjautunut tälle kurssille.<br>";
                }
            }
        }

        $yhteys->commit();

        if (empty($assignedStudents)) {
            $warning_message .= "⚠️ Yhtään uutta opiskelijaa ei lisätty.";
        } else {
            $success_message = "✅ " . count($assignedStudents) . " opiskelijaa lisättiin kurssille onnistuneesti!";
        }

    } catch (Exception $e) {
        $yhteys->rollBack();
        $error_message = "❌ Virhe: " . $e->getMessage();
    }
}

// Haetaan kurssit ja opiskelijat
$kurssit = $yhteys->query("SELECT Tunnus, Nimi FROM kurssit ORDER BY Nimi")->fetchAll(PDO::FETCH_ASSOC);
$opiskelijat = $yhteys->query("SELECT Opiskelijanumero, Etunimi, Sukunimi, Vuosikurssi FROM opiskelijat ORDER BY Sukunimi")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Lisää oppilaita kurssille</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Styles/toiminnot.css">
</head>
<body>
<div class="header">
    <a href="index.php" class="logo">Oppi</a>
</div>

<div class="container">
    <h2>Lisää useampia oppilaita kurssille</h2>

    <?php if ($success_message): ?>
        <div class="alert alert-success"><?= $success_message ?></div>
        <a href="tiedot.php" class="btn btn-primary">Takaisin listaan</a>
    <?php endif; ?>

    <?php if ($warning_message): ?>
        <div class="alert alert-warning"><?= $warning_message ?></div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
    <?php endif; ?>

    <?php if (!$success_message): ?>
    <form method="POST">
        <!-- Kurssi -->
        <div class="mb-3">
            <label class="form-label">Kurssi *</label>
            <select name="kurssi" class="form-control" required>
                <option value="">Valitse kurssi...</option>
                <?php foreach ($kurssit as $kurssi): ?>
                    <option value="<?= $kurssi['Tunnus'] ?>"><?= htmlspecialchars($kurssi['Nimi']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

       <!-- Opiskelijat -->
        <div class="mb-3">
            <label class="form-label">Valitse opiskelijat *</label>
            
            <!-- Hakukenttä -->
            <input type="text" id="studentSearch" class="form-control mb-2" placeholder="Hae opiskelijaa nimellä tai ID:llä...">

            <!-- Lista opiskelijoista -->
            <div id="studentList" class="border rounded p-2" style="max-height: 300px; overflow-y: auto;">
                <?php foreach ($opiskelijat as $opiskelija): ?>
                    <div class="form-check student-item">
                        <input class="form-check-input student-checkbox" 
                            type="checkbox" 
                            name="opiskelijat[]" 
                            value="<?= $opiskelija['Opiskelijanumero'] ?>" 
                            id="student<?= $opiskelija['Opiskelijanumero'] ?>">
                        <label class="form-check-label" for="student<?= $opiskelija['Opiskelijanumero'] ?>">
                            <?= htmlspecialchars($opiskelija['Sukunimi'] . " " . $opiskelija['Etunimi']) ?> 
                            (<?= $opiskelija['Vuosikurssi'] ?>) - ID: <?= $opiskelija['Opiskelijanumero'] ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="form-text">Voit valita useita opiskelijoita ruksittamalla listasta.</div>
        </div>

        <script>
            const searchInput = document.getElementById('studentSearch');
            const studentItems = document.querySelectorAll('.student-item');

            searchInput.addEventListener('input', function() {
                const term = this.value.toLowerCase();
                studentItems.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    if (text.includes(term)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        </script>


        <!-- Päivä -->
        <div class="mb-3">
            <label class="form-label">Kirjautumispäivä *</label>
            <input type="date" name="kirjautumispaiva" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>

        <button type="submit" class="btn btn-success">Lisää oppilaat kurssille</button>
        <a href="tiedot.php" class="btn btn-secondary">Peruuta</a>
    </form>
    <?php endif; ?>
</div>
</body>
</html>
