<?php
include("yhteys.php");

$table = $_GET['table'] ?? '';
$id = $_GET['id'] ?? '';

if (empty($table) || empty($id)) {
    die("Taulukko tai ID puuttuu!");
}

// Define table configurations
$tableConfig = [
    'opiskelijat' => [
        'name' => 'opiskelija',
        'name_plural' => 'opiskelijat',
        'id_field' => 'Opiskelijanumero',
        'fields' => [
            'Etunimi' => ['type' => 'text', 'label' => 'Etunimi', 'required' => true],
            'Sukunimi' => ['type' => 'text', 'label' => 'Sukunimi', 'required' => true],
            'Syntymapaiva' => ['type' => 'date', 'label' => 'Syntymäpäivä', 'required' => true],
            'Vuosikurssi' => ['type' => 'number', 'label' => 'Vuosikurssi', 'required' => true]
        ]
    ],
    'opettajat' => [
        'name' => 'opettaja',
        'name_plural' => 'opettajat',
        'id_field' => 'Tunnusnumero',
        'fields' => [
            'Etunimi' => ['type' => 'text', 'label' => 'Etunimi', 'required' => true],
            'Sukunimi' => ['type' => 'text', 'label' => 'Sukunimi', 'required' => true],
            'Aine' => ['type' => 'text', 'label' => 'Aine', 'required' => true]
        ]
    ],
    'kurssit' => [
        'name' => 'kurssi',
        'name_plural' => 'kurssit',
        'id_field' => 'Tunnus',
        'fields' => [
            'Nimi' => ['type' => 'text', 'label' => 'Nimi', 'required' => true],
            'Kuvaus' => ['type' => 'textarea', 'label' => 'Kuvaus', 'required' => false],
            'Alkupaiva' => ['type' => 'date', 'label' => 'Alkupäivä', 'required' => true],
            'Loppupaiva' => ['type' => 'date', 'label' => 'Loppupäivä', 'required' => true],
            'Opettaja' => ['type' => 'select', 'label' => 'Opettaja', 'required' => true, 'options_query' => 'SELECT Tunnusnumero, CONCAT(Etunimi, " ", Sukunimi) as name FROM opettajat'],
            'Tila' => ['type' => 'select', 'label' => 'Tila', 'required' => true, 'options_query' => 'SELECT Tunnus, Nimi as name FROM tilat']
        ]
    ],
    'kurssikirjautuminen' => [
        'name' => 'kirjautuminen',
        'name_plural' => 'kirjautumiset',
        'id_field' => 'Tunnus',
        'fields' => [
            'Opiskelija' => ['type' => 'select', 'label' => 'Opiskelija', 'required' => true, 'options_query' => 'SELECT Opiskelijanumero, CONCAT(Etunimi, " ", Sukunimi) as name FROM opiskelijat'],
            'Kurssi' => ['type' => 'select', 'label' => 'Kurssi', 'required' => true, 'options_query' => 'SELECT Tunnus, Nimi as name FROM kurssit'],
            'Kirjautumispaiva' => ['type' => 'date', 'label' => 'Kirjautumispäivä', 'required' => true]
        ]
    ]
];

if (!isset($tableConfig[$table])) {
    die("Tuntematon taulukko!");
}

$config = $tableConfig[$table];

if ($_POST) {
    $updateFields = [];
    $updateValues = [];
    
    // Check if ID is being changed (only for teachers)
    $newId = null;
    if ($table === 'opettajat' && isset($_POST[$config['id_field']]) && $_POST[$config['id_field']] != $id) {
        $newId = $_POST[$config['id_field']];
        
        // Check if new ID already exists
        $checkSql = "SELECT COUNT(*) FROM {$table} WHERE {$config['id_field']} = ? AND {$config['id_field']} != ?";
        $checkStmt = $yhteys->prepare($checkSql);
        $checkStmt->execute([$newId, $id]);
        
        if ($checkStmt->fetchColumn() > 0) {
            echo "<div class='alert alert-danger'>Virhe: Opettajan ID {$newId} on jo käytössä!</div>";
            exit;
        }
    }
    
    foreach ($config['fields'] as $field => $fieldConfig) {
        if (isset($_POST[$field])) {
            $updateFields[] = "{$field} = ?";
            $updateValues[] = $_POST[$field];
        }
    }
    
    // If ID is being changed for teachers, handle it specially
    if ($newId && $table === 'opettajat') {
        try {
            $yhteys->beginTransaction();
            
            // First, insert new teacher record with new ID
            $insertSql = "INSERT INTO opettajat (Tunnusnumero, " . implode(', ', array_keys($config['fields'])) . ") VALUES (?, " . str_repeat('?,', count($config['fields']) - 1) . "?)";
            $insertValues = array_merge([$newId], $updateValues);
            $insertStmt = $yhteys->prepare($insertSql);
            $insertStmt->execute($insertValues);
            
            // Then update courses to use new teacher ID
            $updateCoursesSql = "UPDATE kurssit SET Opettaja = ? WHERE Opettaja = ?";
            $updateCoursesStmt = $yhteys->prepare($updateCoursesSql);
            $updateCoursesStmt->execute([$newId, $id]);
            
            // Finally, delete old teacher record
            $deleteSql = "DELETE FROM opettajat WHERE Tunnusnumero = ?";
            $deleteStmt = $yhteys->prepare($deleteSql);
            $deleteStmt->execute([$id]);
            
            $yhteys->commit();
            
            echo "<div class='alert alert-success'>" . ucfirst($config['name']) . " päivitetty onnistuneesti! ID muutettu {$id} → {$newId}</div>";
            echo "<a href='tiedot.php' class='btn btn-primary'>Takaisin listaan</a>";
        } catch (Exception $e) {
            $yhteys->rollBack();
            echo "<div class='alert alert-danger'>Virhe päivittäessä: " . $e->getMessage() . "</div>";
        }
    } else {
        // Normal update without ID change
        $updateValues[] = $id; // Add ID for WHERE clause
        
        $sql = "UPDATE {$table} SET " . implode(', ', $updateFields) . " WHERE {$config['id_field']} = ?";
        $stmt = $yhteys->prepare($sql);
        
        if ($stmt->execute($updateValues)) {
            echo "<div class='alert alert-success'>" . ucfirst($config['name']) . " päivitetty onnistuneesti!</div>";
            echo "<a href='tiedot.php' class='btn btn-primary'>Takaisin listaan</a>";
        } else {
            echo "<div class='alert alert-danger'>Virhe päivittäessä!</div>";
        }
    }
    exit;
}

$sql = "SELECT * FROM {$table} WHERE {$config['id_field']} = ?";
$stmt = $yhteys->prepare($sql);
$stmt->execute([$id]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$record) {
    die(ucfirst($config['name']) . "a ei löytynyt!");
}

// Function to get options for select fields
function getSelectOptions($yhteys, $query, $selectedValue = null) {
    $options = '';
    $stmt = $yhteys->query($query);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $key = array_keys($row)[0]; // First column is the value
        $value = $row['name']; // Second column is the display name
        $selected = ($selectedValue == $row[$key]) ? 'selected' : '';
        $options .= "<option value='{$row[$key]}' {$selected}>{$value}</option>";
    }
    return $options;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Päivitä <?= $config['name'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Styles/toiminnot.css">
</head>
<body>
    <div class="header">
        <a href="index.php" class="logo">Oppi</a>
    </div>
    <div class="container">
        <h2>Päivitä <?= $config['name'] ?></h2>
        
        <form method="POST">
            <div>
                <label class="form-label"><?= $config['id_field'] ?>:</label>
                <?php if ($table === 'opettajat'): ?>
                    <input type="number" name="<?= $config['id_field'] ?>" class="form-control" value="<?= $record[$config['id_field']] ?>" required>
                    <small class="text-muted">Voit muuttaa opettajan ID:tä tarvittaessa</small>
                <?php else: ?>
                    <input type="text" class="form-control" value="<?= $record[$config['id_field']] ?>" readonly>
                <?php endif; ?>
            </div>
            
            <?php foreach ($config['fields'] as $field => $fieldConfig): ?>
                <div>
                    <label class="form-label"><?= $fieldConfig['label'] ?>:</label>
                    <?php if ($fieldConfig['type'] === 'text'): ?>
                        <input type="text" name="<?= $field ?>" class="form-control" 
                               value="<?= htmlspecialchars($record[$field]) ?>" 
                               <?= $fieldConfig['required'] ? 'required' : '' ?>>
                    <?php elseif ($fieldConfig['type'] === 'number'): ?>
                        <input type="number" name="<?= $field ?>" class="form-control" 
                               value="<?= $record[$field] ?>" 
                               <?= $fieldConfig['required'] ? 'required' : '' ?>>
                    <?php elseif ($fieldConfig['type'] === 'date'): ?>
                        <input type="date" name="<?= $field ?>" class="form-control" 
                               value="<?= $record[$field] ?>" 
                               <?= $fieldConfig['required'] ? 'required' : '' ?>>
                    <?php elseif ($fieldConfig['type'] === 'textarea'): ?>
                        <textarea name="<?= $field ?>" class="form-control" 
                                  <?= $fieldConfig['required'] ? 'required' : '' ?>><?= htmlspecialchars($record[$field]) ?></textarea>
                    <?php elseif ($fieldConfig['type'] === 'select'): ?>
                        <select name="<?= $field ?>" class="form-control" 
                                <?= $fieldConfig['required'] ? 'required' : '' ?>>
                            <option value="">Valitse...</option>
                            <?= getSelectOptions($yhteys, $fieldConfig['options_query'], $record[$field]) ?>
                        </select>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            
            <button type="submit" class="btn btn-success">Päivitä</button>
            <a href="tiedot.php" class="btn btn-secondary">Peruuta</a>
        </form>
    </div>
</body>
</html>