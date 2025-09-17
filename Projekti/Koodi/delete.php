<?php
include("yhteys.php");

$id = $_GET['id'] ?? '';

if (empty($id)) {
    die("Opiskelijanumero puuttuu!");
}

if (isset($_POST['confirm_delete'])) {
    $sql = "DELETE FROM opiskelijat WHERE Opiskelijanumero = ?";
    $stmt = $yhteys->prepare($sql);
    
    if ($stmt->execute([$id])) {
        echo "<div class='alert alert-success'>Opiskelija poistettu onnistuneesti!</div>";
        echo "<a href='tiedot.php' class='btn btn-primary'>Takaisin listaan</a>";
    } else {
        echo "<div class='alert alert-danger'>Virhe poistettaessa!</div>";
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
    <title>Poista opiskelija</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Styles/toiminnot.css">
</head>
<body>

    <div class="header">
        <a href="index.php" class="logo">Oppi</a>
    </div>

    <div class="container">
        <h2>Poista opiskelija</h2>
        <div class="alert alert-warning">
            <h4>Oletko varma että haluat poistaa tämän opiskelijan?</h4>
            <p>Tämä toiminto ei ole peruutettavissa!</p>
        </div>
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Opiskelijan tiedot:</h5>
                <p><strong>Opiskelijanumero:</strong> <?= htmlspecialchars($student['Opiskelijanumero']) ?></p>
                <p><strong>Nimi:</strong> <?= htmlspecialchars($student['Etunimi']) ?> <?= htmlspecialchars($student['Sukunimi']) ?></p>
                <p><strong>Syntymäpäivä:</strong> <?= htmlspecialchars($student['Syntymapaiva']) ?></p>
                <p><strong>Vuosikurssi:</strong> <?= htmlspecialchars($student['Vuosikurssi']) ?></p>
            </div>
        </div>
        
        <div class="mt-3">
            <form method="POST" style="display: inline;">
                <button type="submit" name="confirm_delete" class="btn btn-danger" 
                        onclick="return confirm('Oletko aivan varma?')">
                    Kyllä, poista opiskelija
                </button>
            </form>
            <a href="tiedot.php" class="btn btn-secondary">Peruuta</a>
        </div>
    </div>
</body>
</html>