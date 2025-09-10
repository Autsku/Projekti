<?php
include("yhteys.php");

$table = $_GET['table'] ?? 'opiskelijat';
$success_message = '';
$error_message = '';

$allowed_tables = ['opiskelijat', 'opettajat', 'kurssit', 'kurssikirjautuminen'];
if (!in_array($table, $allowed_tables)) {
    $table = 'opiskelijat';
}

if ($_POST) {
    try {
        switch($table) {
            case 'opiskelijat':
                $etunimi = $_POST['etunimi'];
                $sukunimi = $_POST['sukunimi'];
                $syntymapaiva = $_POST['syntymapaiva'];
                $vuosikurssi = $_POST['vuosikurssi'];
                
                $sql = "INSERT INTO opiskelijat (Etunimi, Sukunimi, Syntymapaiva, Vuosikurssi) VALUES (?, ?, ?, ?)";
                $stmt = $yhteys->prepare($sql);
                $result = $stmt->execute([$etunimi, $sukunimi, $syntymapaiva, $vuosikurssi]);
                break;
                
            case 'opettajat':
                $etunimi = $_POST['etunimi'];
                $sukunimi = $_POST['sukunimi'];
                $aine = $_POST['aine'];
                
                $sql = "INSERT INTO opettajat (Etunimi, Sukunimi, Aine) VALUES (?, ?, ?)";
                $stmt = $yhteys->prepare($sql);
                $result = $stmt->execute([$etunimi, $sukunimi, $aine]);
                break;
                
            case 'kurssit':
                $nimi = $_POST['nimi'];
                $kuvaus = $_POST['kuvaus'];
                $alkupaiva = $_POST['alkupaiva'];
                $loppupaiva = $_POST['loppupaiva'];
                $opettaja = $_POST['opettaja'];
                $tila = $_POST['tila'];
                
                $sql = "INSERT INTO kurssit (Nimi, Kuvaus, Alkupaiva, Loppupaiva, Opettaja, Tila) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $yhteys->prepare($sql);
                $result = $stmt->execute([$nimi, $kuvaus, $alkupaiva, $loppupaiva, $opettaja, $tila]);
                break;
                
            case 'kurssikirjautuminen':
                $opiskelija = $_POST['opiskelija'];
                $kurssi = $_POST['kurssi'];
                $kirjautumispaiva = $_POST['kirjautumispaiva'];
                
                $sql = "INSERT INTO kurssikirjautuminen (Opiskelija, Kurssi, Kirjautumispaiva) VALUES (?, ?, ?)";
                $stmt = $yhteys->prepare($sql);
                $result = $stmt->execute([$opiskelija, $kurssi, $kirjautumispaiva]);
                break;
        }
        
        if ($result) {
            $success_message = ucfirst(str_replace(['opiskelijat', 'opettajat', 'kurssit', 'kurssikirjautuminen'], 
                                                  ['opiskelija', 'opettaja', 'kurssi', 'kirjautuminen'], $table)) . " lisätty onnistuneesti!";
        } else {
            $error_message = "Virhe lisättäessä tietoja!";
        }
    } catch (Exception $e) {
        $error_message = "Virhe: " . $e->getMessage();
    }
}

$opettajat = [];
$kurssit = [];
$opiskelijat = [];

try {
    $opettajat_result = $yhteys->query("SELECT Tunnusnumero, CONCAT(Etunimi, ' ', Sukunimi) as nimi FROM opettajat");
    $opettajat = $opettajat_result->fetchAll(PDO::FETCH_ASSOC);
    
    $kurssit_result = $yhteys->query("SELECT Tunnus, Nimi FROM kurssit");
    $kurssit = $kurssit_result->fetchAll(PDO::FETCH_ASSOC);
    
    $opiskelijat_result = $yhteys->query("SELECT Opiskelijanumero, CONCAT(Etunimi, ' ', Sukunimi) as nimi FROM opiskelijat");
    $opiskelijat = $opiskelijat_result->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    
}

$titles = [
    'opiskelijat' => 'Lisää uusi opiskelija',
    'opettajat' => 'Lisää uusi opettaja', 
    'kurssit' => 'Lisää uusi kurssi',
    'kurssikirjautuminen' => 'Lisää uusi kirjautuminen'
];
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titles[$table] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles1.css">
</head>
<body>
    <div class="header">
        <a href="index.php" class="logo">Logo</a>
    </div>

    <div class="container">
        <h2><?= $titles[$table] ?></h2>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <?= $success_message ?>
                <br><br>
                <a href="index.php" class="btn btn-primary">Takaisin pääsivulle</a>
                <a href="add.php?table=<?= $table ?>" class="btn btn-success">Lisää uusi</a>
            </div>
        <?php elseif ($error_message): ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>

        <?php if (!$success_message): ?>
<form method="POST">
    <?php if ($table == 'opiskelijat'): ?>
        <div>
            <label class="form-label">Etunimi:</label>
            <input type="text" name="etunimi" class="form-control" value="<?= $_POST['etunimi'] ?? '' ?>" required>
        </div>
        <div>
            <label class="form-label">Sukunimi:</label>
            <input type="text" name="sukunimi" class="form-control" value="<?= $_POST['sukunimi'] ?? '' ?>" required>
        </div>
        <div>
            <label class="form-label">Syntymäpäivä:</label>
            <input type="date" name="syntymapaiva" class="form-control" value="<?= $_POST['syntymapaiva'] ?? '' ?>" required>
        </div>
        <div>
            <label class="form-label">Vuosikurssi:</label>
            <input type="number" name="vuosikurssi" class="form-control" value="<?= $_POST['vuosikurssi'] ?? '' ?>" min="1" max="5" required>
            <div>Syötä vuosikurssi (1-3)</div>
        </div>

    <?php elseif ($table == 'opettajat'): ?>
        <div>
            <label class="form-label">Etunimi:</label>
            <input type="text" name="etunimi" class="form-control" value="<?= $_POST['etunimi'] ?? '' ?>" required>
        </div>
        <div>
            <label class="form-label">Sukunimi:</label>
            <input type="text" name="sukunimi" class="form-control" value="<?= $_POST['sukunimi'] ?? '' ?>" required>
        </div>
        <div>
            <label class="form-label">Aine:</label>
            <input type="text" name="aine" class="form-control" value="<?= $_POST['aine'] ?? '' ?>" required>
            <div class="form-text">Esim. Matematiikka, Historia, Englanti</div>
        </div>

    <?php elseif ($table == 'kurssit'): ?>
        <div>
            <label class="form-label">Kurssin nimi:</label>
            <input type="text" name="nimi" class="form-control" value="<?= $_POST['nimi'] ?? '' ?>" required>
        </div>
        <div>
            <label class="form-label">Kuvaus:</label>
            <textarea name="kuvaus" class="form-control" rows="3"><?= $_POST['kuvaus'] ?? '' ?></textarea>
        </div>
        <div>
            <label class="form-label">Alkupäivä:</label>
            <input type="date" name="alkupaiva" class="form-control" value="<?= $_POST['alkupaiva'] ?? '' ?>" required>
        </div>
        <div>
            <label class="form-label">Loppupäivä:</label>
            <input type="date" name="loppupaiva" class="form-control" value="<?= $_POST['loppupaiva'] ?? '' ?>" required>
        </div>
        <div>
            <label class="form-label">Opettaja:</label>
            <select name="opettaja" class="form-control" required>
                <option value="">Valitse opettaja</option>
                <?php foreach ($opettajat as $opettaja): ?>
                    <option value="<?= $opettaja['Tunnusnumero'] ?>" 
                            <?= ($_POST['opettaja'] ?? '') == $opettaja['Tunnusnumero'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($opettaja['nimi']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="form-label">Tila:</label>
            <input type="text" name="tila" class="form-control" value="<?= $_POST['tila'] ?? '' ?>" required>
            <div class="form-text">Esim. Luokka A101, Laboratorio B</div>
        </div>

    <?php elseif ($table == 'kurssikirjautuminen'): ?>
        <div>
            <label class="form-label">Opiskelija:</label>
            <select name="opiskelija" class="form-control" required>
                <option value="">Valitse opiskelija</option>
                <?php foreach ($opiskelijat as $opiskelija): ?>
                    <option value="<?= $opiskelija['Opiskelijanumero'] ?>" 
                            <?= ($_POST['opiskelija'] ?? '') == $opiskelija['Opiskelijanumero'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($opiskelija['nimi']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="form-label">Kurssi:</label>
            <select name="kurssi" class="form-control" required>
                <option value="">Valitse kurssi</option>
                <?php foreach ($kurssit as $kurssi): ?>
                    <option value="<?= $kurssi['Tunnus'] ?>" 
                            <?= ($_POST['kurssi'] ?? '') == $kurssi['Tunnus'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($kurssi['Nimi']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="form-label">Kirjautumispäivä:</label>
            <input type="date" name="kirjautumispaiva" class="form-control" 
                   value="<?= $_POST['kirjautumispaiva'] ?? date('Y-m-d') ?>" required>
        </div>
    <?php endif; ?>

    <button type="submit" class="btn btn-success">
        Lisää <?= str_replace(['opiskelijat', 'opettajat', 'kurssit', 'kurssikirjautuminen'], 
                             ['opiskelija', 'opettaja', 'kurssi', 'kirjautuminen'], $table) ?>
    </button>
    <a href="tiedot.php" class="btn btn-secondary">Peruuta</a>
</form>
<?php endif; ?>
</body>
</html>