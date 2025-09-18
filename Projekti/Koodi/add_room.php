<?php
include("yhteys.php");

$success_message = '';
$error_message = '';
$warning_message = '';

if ($_POST) {
    try {
        $yhteys->beginTransaction();
        
        $tilaNimi = trim($_POST['tila_nimi']);
        $kapasiteetti = (int)$_POST['kapasiteetti'];
        
        // Tarkista että tilan nimi ei ole jo olemassa
        $checkSql = "SELECT COUNT(*) FROM tilat WHERE Nimi = ?";
        $checkStmt = $yhteys->prepare($checkSql);
        $checkStmt->execute([$tilaNimi]);
        
        if ($checkStmt->fetchColumn() > 0) {
            $error_message = "Tila nimellä '{$tilaNimi}' on jo olemassa. Valitse toinen nimi.";
        } else {
            // Lisää tila
            $sql = "INSERT INTO tilat (Nimi, Kapasiteetti) VALUES (?, ?)";
            $stmt = $yhteys->prepare($sql);
            $stmt->execute([$tilaNimi, $kapasiteetti]);
            
            $tilaId = $yhteys->lastInsertId();
            
            // Käsittele mahdolliset kurssi-määritykset
            $assignedCourses = [];
            if (!empty($_POST['existing_courses'])) {
                foreach ($_POST['existing_courses'] as $courseId) {
                    $updateSql = "UPDATE kurssit SET Tila = ? WHERE Tunnus = ?";
                    $updateStmt = $yhteys->prepare($updateSql);
                    $updateStmt->execute([$tilaId, $courseId]);
                    $assignedCourses[] = $courseId;
                }
            }
            
            $yhteys->commit();
            
            if (empty($assignedCourses)) {
                $success_message = "Tila '{$tilaNimi}' lisätty onnistuneesti! Kapasiteetti: {$kapasiteetti} henkilöä.";
            } else {
                $success_message = "Tila '{$tilaNimi}' ja " . count($assignedCourses) . " kurssia lisätty onnistuneesti!";
            }
        }
        
    } catch (Exception $e) {
        $yhteys->rollBack();
        $error_message = "Virhe: " . $e->getMessage();
    }
}

// Hae kurssit joilla ei ole tilaa määritettynä
$availableCourses = $yhteys->query("
    SELECT k.Tunnus, k.Nimi, o.Etunimi, o.Sukunimi 
    FROM kurssit k
    LEFT JOIN opettajat o ON k.Opettaja = o.Tunnusnumero
    WHERE k.Tila IS NULL OR k.Tila = 0
    ORDER BY k.Nimi
")->fetchAll(PDO::FETCH_ASSOC);

// Hae kaikki tilat kapasiteettien vertailua varten
$existingRooms = $yhteys->query("SELECT Nimi, Kapasiteetti FROM tilat ORDER BY Kapasiteetti DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Lisää tila</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Styles/toiminnot.css">
    <style>
        .course-section {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .capacity-guide {
            background-color: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }
        .existing-rooms {
            background-color: #fff3e0;
            border: 1px solid #ffcc80;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }
        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php" class="logo">Oppi</a>
    </div>
    
    <div class="container">
        <h2>Lisää uusi tila</h2>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?= $success_message ?></div>
            <a href="tiedot.php" class="btn btn-primary">Takaisin listaan</a>
            <a href="tilat.php" class="btn btn-info">Katso tilat</a>
        <?php endif; ?>
        
        <?php if ($warning_message): ?>
            <div class="alert alert-warning"><?= $warning_message ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>
        
        <?php if (!$success_message): ?>
        
        <!-- Olemassa olevien tilojen näyttö -->
        <?php if (!empty($existingRooms)): ?>
        <div class="existing-rooms">
            <h5>Olemassa olevat tilat vertailua varten</h5>
            <div class="row">
                <?php foreach ($existingRooms as $room): ?>
                <div class="col-md-4 mb-2">
                    <strong><?= htmlspecialchars($room['Nimi']) ?></strong>: <?= $room['Kapasiteetti'] ?> henkilöä
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <form method="POST">
            <!-- Perustilatiedot -->
            <div class="row">
                <div class="col-md-8">
                    <label class="form-label">Tilan nimi *</label>
                    <input type="text" name="tila_nimi" class="form-control" 
                           placeholder="esim. Luokka A101, Liikuntasali, Laboratorio B" 
                           required>
                    <div class="form-text">Anna tilalle kuvaava nimi. Tilan nimi ei saa olla jo käytössä.</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kapasiteetti (henkilöä) *</label>
                    <input type="number" name="kapasiteetti" class="form-control" 
                           min="1" max="500" value="30" required>
                    <div class="form-text">Montako henkilöä tilaan mahtuu turvallisesti</div>
                </div>
            </div>
            
            <!-- Kapasiteettiohje -->
            <div class="capacity-guide">
                <h6>Kapasiteettiohje</h6>
                <div class="row">
                    <div class="col-md-3">
                        <strong>Pieni luokka:</strong><br>15-25 henkilöä
                    </div>
                    <div class="col-md-3">
                        <strong>Normaali luokka:</strong><br>25-35 henkilöä
                    </div>
                    <div class="col-md-3">
                        <strong>Iso luokka:</strong><br>35-50 henkilöä
                    </div>
                    <div class="col-md-3">
                        <strong>Liikuntasali:</strong><br>50-100 henkilöä
                    </div>
                </div>
            </div>
            
            <!-- Kurssien määrittäminen -->
            <?php if (!empty($availableCourses)): ?>
            <div class="course-section">
                <h4>Määritä kursseja tälle tilalle</h4>
                <p>Voit valita kursseja, joilla ei vielä ole tilaa määritettynä:</p>
                
                <div class="alert alert-info">
                    <strong>Vinkki:</strong> Voit myös jättää tämän tyhjäksi ja määrittää kursseja myöhemmin.
                </div>
                
                <div class="row">
                    <?php foreach ($availableCourses as $course): ?>
                    <div class="col-md-6 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="existing_courses[]" 
                                   value="<?= $course['Tunnus'] ?>"
                                   id="course_<?= $course['Tunnus'] ?>">
                            <label class="form-check-label" for="course_<?= $course['Tunnus'] ?>">
                                <strong><?= htmlspecialchars($course['Nimi']) ?></strong>
                                <?php if ($course['Etunimi'] && $course['Sukunimi']): ?>
                                    <br><small class="text-muted">Opettaja: <?= htmlspecialchars($course['Etunimi'] . ' ' . $course['Sukunimi']) ?></small>
                                <?php endif; ?>
                            </label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="alert alert-info">
                <strong>Huomio:</strong> Kaikilla kursseilla on jo tila määritettynä. Voit muuttaa kurssien tiloja myöhemmin muokkauslomakkeella.
            </div>
            <?php endif; ?>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-success">Lisää tila</button>
                <a href="tiedot.php" class="btn btn-secondary">Peruuta</a>
                <a href="tilat.php" class="btn btn-info">Katso olemassa olevat tilat</a>
            </div>
        </form>
        <?php endif; ?>
    </div>

    <script>
        // Kapasiteetin validointi
        document.querySelector('input[name="kapasiteetti"]').addEventListener('input', function() {
            const value = parseInt(this.value);
            const feedback = document.createElement('div');
            feedback.className = 'form-text';
            
            // Poista aiempi palaute
            const existingFeedback = this.parentNode.querySelector('.capacity-feedback');
            if (existingFeedback) {
                existingFeedback.remove();
            }
            
            if (value < 10) {
                feedback.textContent = '⚠️ Hyvin pieni tila - varmista että kapasiteetti on oikein';
                feedback.style.color = 'orange';
            } else if (value > 100) {
                feedback.textContent = '⚠️ Hyvin iso tila - varmista että kapasiteetti on oikein';
                feedback.style.color = 'orange';
            } else if (value >= 25 && value <= 35) {
                feedback.textContent = '✓ Normaali luokka koko';
                feedback.style.color = 'green';
            }
            
            feedback.className += ' capacity-feedback';
            this.parentNode.appendChild(feedback);
        });
    </script>
</body>
</html>