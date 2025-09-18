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
        'display_fields' => [
            'Opiskelijanumero' => 'Opiskelijanumero',
            'Etunimi' => 'Etunimi',
            'Sukunimi' => 'Sukunimi',
            'Syntymapaiva' => 'Syntymäpäivä',
            'Vuosikurssi' => 'Vuosikurssi'
        ]
    ],
    'opettajat' => [
        'name' => 'opettaja',
        'name_plural' => 'opettajat',
        'id_field' => 'Tunnusnumero',
        'display_fields' => [
            'Tunnusnumero' => 'Tunnusnumero',
            'Etunimi' => 'Etunimi',
            'Sukunimi' => 'Sukunimi',
            'Aine' => 'Aine'
        ]
    ],
    'kurssit' => [
        'name' => 'kurssi',
        'name_plural' => 'kurssit',
        'id_field' => 'Tunnus',
        'display_fields' => [
            'Tunnus' => 'Tunnus',
            'Nimi' => 'Nimi',
            'Kuvaus' => 'Kuvaus',
            'Alkupaiva' => 'Alkupäivä',
            'Loppupaiva' => 'Loppupäivä'
        ]
    ],
    'kurssikirjautuminen' => [
        'name' => 'kirjautuminen',
        'name_plural' => 'kirjautumiset',
        'id_field' => 'Tunnus',
        'display_fields' => [
            'Tunnus' => 'Tunnus',
            'Opiskelija' => 'Opiskelija',
            'Kurssi' => 'Kurssi',
            'Kirjautumispaiva' => 'Kirjautumispäivä'
        ]
    ]
];

if (!isset($tableConfig[$table])) {
    die("Tuntematon taulukko!");
}

$config = $tableConfig[$table];

if (isset($_POST['confirm_delete'])) {
    $sql = "DELETE FROM {$table} WHERE {$config['id_field']} = ?";
    $stmt = $yhteys->prepare($sql);
    
    if ($stmt->execute([$id])) {
        echo "<div class='alert alert-success'>" . ucfirst($config['name']) . " poistettu onnistuneesti!</div>";
        echo "<a href='tiedot.php' class='btn btn-primary'>Takaisin listaan</a>";
    } else {
        echo "<div class='alert alert-danger'>Virhe poistettaessa!</div>";
    }
    exit;
}

$sql = "SELECT * FROM {$table} WHERE {$config['id_field']} = ?";
$stmt = $yhteys->prepare($sql);
$stmt->execute([$id]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$record) {
    die(ucfirst($config['name']) . " ei löytynyt!");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Poista <?= $config['name'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Styles/toiminnot.css">
</head>
<body>
    <div class="header">
        <a href="index.php" class="logo">Oppi</a>
    </div>

    <div class="container">
        <h2>Poista <?= $config['name'] ?></h2>
        <div class="alert alert-warning">
            <h4>Oletko varma että haluat poistaa tämän <?= $config['name'] ?>n?</h4>
            <p>Tämä toiminto ei ole peruutettavissa!</p>
        </div>
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?= ucfirst($config['name']) ?>n tiedot:</h5>
                <?php foreach ($config['display_fields'] as $field => $label): ?>
                    <p><strong><?= $label ?>:</strong> <?= htmlspecialchars($record[$field]) ?></p>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="mt-3">
            <form method="POST" style="display: inline;">
                <button type="submit" name="confirm_delete" class="btn btn-danger"
                        onclick="return confirm('Oletko aivan varma?')">
                    Kyllä, poista <?= $config['name'] ?>
                </button>
            </form>
            <a href="tiedot.php" class="btn btn-secondary">Peruuta</a>
        </div>
    </div>
</body>
</html>