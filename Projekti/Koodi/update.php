<?php
include("yhteys.php");

$id = $_GET['id'] ?? '';

if (empty($id)) {
    die("Opiskelijanumero puuttuu!");
}

if ($_POST) {
    $etunimi = $_POST['etunimi'];
    $sukunimi = $_POST['sukunimi'];
    $syntymapaiva = $_POST['syntymapaiva'];
    $vuosikurssi = $_POST['vuosikurssi'];
    
    $sql = "UPDATE opiskelijat SET Etunimi = ?, Sukunimi = ?, Syntymapaiva = ?, Vuosikurssi = ? WHERE Opiskelijanumero = ?";
    $stmt = $yhteys->prepare($sql);
    
    if ($stmt->execute([$etunimi, $sukunimi, $syntymapaiva, $vuosikurssi, $id])) {
        echo "<div class='alert alert-success'>Opiskelija päivitetty onnistuneesti!</div>";
        echo "<a href='tiedot.php' class='btn btn-primary'>Takaisin listaan</a>";
    } else {
        echo "<div class='alert alert-danger'>Virhe päivittäessä!</div>";
    }
    exit;
}

$sql = "SELECT * FROM opiskelijat WHERE Opiskelijanumero = ?";
$stmt = $yhteys->prepare($sql);
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Opiskelijaa ei löytynyt!");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Päivitä opiskelija</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Styles/toiminnot.css">
</head>
<body>
    <div class="header">
        <a href="index.php" class="logo">Oppi</a>
    </div>
    <div class="container">
        <h2>Päivitä opiskelija</h2>
        
        <form method="POST">
            <div>
                <label class="form-label">Opiskelijanumero:</label>
                <input type="text" class="form-control" value="<?= $student['Opiskelijanumero'] ?>" readonly>
            </div>
            
            <div>
                <label class="form-label">Etunimi:</label>
                <input type="text" name="etunimi" class="form-control" value="<?= htmlspecialchars($student['Etunimi']) ?>" required>
            </div>
            
            <div>
                <label class="form-label">Sukunimi:</label>
                <input type="text" name="sukunimi" class="form-control" value="<?= htmlspecialchars($student['Sukunimi']) ?>" required>
            </div>
            
            <div>
                <label class="form-label">Syntymäpäivä:</label>
                <input type="date" name="syntymapaiva" class="form-control" value="<?= $student['Syntymapaiva'] ?>" required>
            </div>
            
            <div>
                <label class="form-label">Vuosikurssi:</label>
                <input type="number" name="vuosikurssi" class="form-control" value="<?= $student['Vuosikurssi'] ?>" required>
            </div>
            
            <button type="submit" class="btn btn-success">Päivitä</button>
            <a href="tiedot.php" class="btn btn-secondary">Peruuta</a>
        </form>
    </div>
</body>
</html>