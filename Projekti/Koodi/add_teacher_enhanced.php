<?php
include("yhteys.php");

$success_message = '';
$error_message = '';
$warning_message = '';

if ($_POST) {
    try {
        // Start transaction
        $yhteys->beginTransaction();
        
        // Add teacher first
        $etunimi = $_POST['etunimi'];
        $sukunimi = $_POST['sukunimi'];
        $aine = $_POST['aine'];
        
        $sql = "INSERT INTO opettajat (Etunimi, Sukunimi, Aine) VALUES (?, ?, ?)";
        $stmt = $yhteys->prepare($sql);
        $stmt->execute([$etunimi, $sukunimi, $aine]);
        
        $teacherId = $yhteys->lastInsertId();
        
        // Handle course assignments
        $assignedCourses = [];
        if (!empty($_POST['existing_courses'])) {
            foreach ($_POST['existing_courses'] as $courseId) {
                $sql = "UPDATE kurssit SET Opettaja = ? WHERE Tunnus = ?";
                $stmt = $yhteys->prepare($sql);
                $stmt->execute([$teacherId, $courseId]);
                $assignedCourses[] = $courseId;
            }
        }
        
        // Create new courses if any
        if (!empty($_POST['new_course_name'])) {
            for ($i = 0; $i < count($_POST['new_course_name']); $i++) {
                if (!empty($_POST['new_course_name'][$i])) {
                    $courseName = $_POST['new_course_name'][$i];
                    $courseDesc = $_POST['new_course_desc'][$i] ?? '';
                    $startDate = $_POST['new_course_start'][$i] ?? null;
                    $endDate = $_POST['new_course_end'][$i] ?? null;
                    $room = $_POST['new_course_room'][$i] ?? null;
                    
                    $sql = "INSERT INTO kurssit (Nimi, Kuvaus, Alkupaiva, Loppupaiva, Opettaja, Tila) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $yhteys->prepare($sql);
                    $stmt->execute([$courseName, $courseDesc, $startDate, $endDate, $teacherId, $room]);
                    $assignedCourses[] = $yhteys->lastInsertId();
                }
            }
        }
        
        $yhteys->commit();
        
        if (empty($assignedCourses)) {
            $warning_message = "⚠️ Huomio: Opettaja lisätty onnistuneesti, mutta hänelle ei ole määritetty yhtään kurssia. Voit lisätä kursseja myöhemmin.";
        } else {
            $success_message = "✅ Opettaja ja " . count($assignedCourses) . " kurssia lisätty onnistuneesti!";
        }
        
    } catch (Exception $e) {
        $yhteys->rollBack();
        $error_message = "❌ Virhe: " . $e->getMessage();
    }
}

// Get existing courses without teacher
$availableCourses = $yhteys->query("SELECT Tunnus, Nimi FROM kurssit WHERE Opettaja IS NULL OR Opettaja = 0")->fetchAll(PDO::FETCH_ASSOC);

// Get rooms for new courses
$rooms = $yhteys->query("SELECT Tunnus, Nimi FROM tilat")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Lisää opettaja</title>
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
        .new-course-form {
            background-color: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
        }
        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffecb5;
            color: #664d03;
        }
        .btn-add-course {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-remove-course {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php" class="logo">Oppi</a>
    </div>
    
    <div class="container">
        <h2>Lisää uusi opettaja</h2>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?= $success_message ?></div>
            <a href="tiedot.php" class="btn btn-primary">Takaisin listaan</a>
            <a href="add.php?table=opettajat" class="btn btn-secondary">Lisää toinen opettaja</a>
        <?php elseif ($warning_message): ?>
            <div class="alert alert-warning"><?= $warning_message ?></div>
            <a href="tiedot.php" class="btn btn-primary">Takaisin listaan</a>
            <a href="add.php?table=kurssit" class="btn btn-success">Lisää kursseja tälle opettajalle</a>
        <?php elseif ($error_message): ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>
        
        <?php if (!$success_message && !$warning_message): ?>
        <form method="POST" id="teacherForm">
            <!-- Basic teacher information -->
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Etunimi *</label>
                    <input type="text" name="etunimi" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sukunimi *</label>
                    <input type="text" name="sukunimi" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Aine *</label>
                    <input type="text" name="aine" class="form-control" required>
                </div>
            </div>
            
            <!-- Existing courses section -->
            <?php if (!empty($availableCourses)): ?>
            <div class="course-section">
                <h4>📚 Valitse olemassa olevia kursseja</h4>
                <p>Voit valita kursseja, joilla ei vielä ole opettajaa:</p>
                
                <div class="row">
                    <?php foreach ($availableCourses as $course): ?>
                    <div class="col-md-4 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="existing_courses[]" 
                                   value="<?= $course['Tunnus'] ?>"
                                   id="course_<?= $course['Tunnus'] ?>">
                            <label class="form-check-label" for="course_<?= $course['Tunnus'] ?>">
                                <?= htmlspecialchars($course['Nimi']) ?>
                            </label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- New courses section -->
            <div class="course-section">
                <h4>Luo uusia kursseja</h4>
                <p>Voit luoda samalla uusia kursseja tälle opettajalle:</p>
                
                <button type="button" class="btn-add-course" onclick="addNewCourseForm()">
                    + Lisää uusi kurssi
                </button>
                
                <div id="newCoursesContainer">
                    <!-- Dynamic course forms will be added here -->
                </div>
            </div>
            
            <!-- Warning about no courses -->
            <div class="alert alert-warning">
                <strong>⚠️ Huomio:</strong> Jos et valitse tai luo yhtään kurssia, opettaja lisätään ilman kursseja. 
                Voit lisätä kursseja myöhemmin muokkaamalla opettajan tietoja.
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-success">Lisää opettaja</button>
                <a href="tiedot.php" class="btn btn-secondary">Peruuta</a>
            </div>
        </form>
        <?php endif; ?>
    </div>

    <script>
        let courseCounter = 0;
        
        function addNewCourseForm() {
            courseCounter++;
            
            const container = document.getElementById('newCoursesContainer');
            const courseForm = document.createElement('div');
            courseForm.className = 'new-course-form';
            courseForm.id = 'course_' + courseCounter;
            
            courseForm.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6>Uusi kurssi #${courseCounter}</h6>
                    <button type="button" class="btn-remove-course" onclick="removeCourseForm(${courseCounter})">
                        Poista
                    </button>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Kurssin nimi *</label>
                        <input type="text" name="new_course_name[]" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tila</label>
                        <select name="new_course_room[]" class="form-control">
                            <option value="">Valitse tila...</option>
                            <?php foreach ($rooms as $room): ?>
                            <option value="<?= $room['Tunnus'] ?>"><?= htmlspecialchars($room['Nimi']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Alkupäivä</label>
                        <input type="date" name="new_course_start[]" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Loppupäivä</label>
                        <input type="date" name="new_course_end[]" class="form-control">
                    </div>
                </div>
                
                <div class="mt-2">
                    <label class="form-label">Kuvaus</label>
                    <textarea name="new_course_desc[]" class="form-control" rows="2"></textarea>
                </div>
            `;
            
            container.appendChild(courseForm);
        }
        
        function removeCourseForm(id) {
            const courseForm = document.getElementById('course_' + id);
            if (courseForm) {
                courseForm.remove();
            }
        }
        
        // Add one course form by default
        addNewCourseForm();
    </script>
</body>
</html>