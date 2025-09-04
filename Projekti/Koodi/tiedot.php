<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <div class="header">
        <a href="index.php" class="logo">Logo</a>
        <div class="items">
            <a href="tilat.php">Tilat</a>
            <a href="kurssit.php">Kurssit</a>
            <a href="opiskelijat.php">Opiskelijat</a>
            <a href="opettajat.php">Opettajat</a>
        </div>
    </div>

    <div class="box" style="margin: 50px;">
        <div class="oppilaat">
            <h3>Oppilaat</h3>
            <br>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Etunimi</th>
                        <th>Sukunimi</th>
                        <th>Syntymäpäivä</th>
                        <th>Vuosikurssi</th>
                        <th>Toiminnot</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php
                    include("yhteys.php");

                    $sql = "SELECT * FROM opiskelijat";
                    $result = $yhteys->query($sql);  

                    if (!$result) {
                        die("Invalid query: " . $yhteys->errorInfo()[2]);  
                    }

                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo"<tr>
                            <td>" . $row["Opiskelijanumero"] . "</td>
                            <td>" . $row["Etunimi"] . "</td>
                            <td>" . $row["Sukunimi"] . "</td>
                            <td>" . $row["Syntymapaiva"] . "</td>
                            <td>" . $row["Vuosikurssi"] . "</td>
                            <td>
                                <a class='btn btn-success btn-sm' href='add.php?table=opiskelijat'>Add</a>
                                <a class='btn btn-primary btn-sm' href='update.php?table=opiskelijat&id=" . $row["Opiskelijanumero"] . "'>Update</a>
                                <a class='btn btn-danger btn-sm' href='delete.php?table=opiskelijat&id=" . $row["Opiskelijanumero"] . "'>Delete</a>                          
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="opettajat">
            <h3>Opettajat</h3>
            <br>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Etunimi</th>
                        <th>Sukunimi</th>
                        <th>Aine</th>
                        <th>Toiminnot</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php
                    $sql = "SELECT * FROM opettajat";
                    $result = $yhteys->query($sql);  

                    if (!$result) {
                        die("Invalid query: " . $yhteys->errorInfo()[2]);  
                    }

                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo"<tr>
                            <td>" . $row["Tunnusnumero"] . "</td>
                            <td>" . $row["Etunimi"] . "</td>
                            <td>" . $row["Sukunimi"] . "</td>
                            <td>" . $row["Aine"] . "</td>
                            <td>
                                <a class='btn btn-success btn-sm' href='add.php?table=opettajat'>Add</a>
                                <a class='btn btn-primary btn-sm' href='update.php?table=opettajat&id=" . $row["Tunnusnumero"] . "'>Update</a>
                                <a class='btn btn-danger btn-sm' href='delete.php?table=opettajat&id=" . $row["Tunnusnumero"] . "'>Delete</a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="kurssit">
            <h3>Kurssit</h3>
            <br>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nimi</th>
                        <th>Kuvaus</th>
                        <th>Alkupäivä</th>
                        <th>Loppupäivä</th>
                        <th>Opettaja</th>
                        <th>Tila</th>
                        <th>Toiminnot</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php
                    $sql = "SELECT * FROM kurssit";
                    $result = $yhteys->query($sql);  

                    if (!$result) {
                        die("Invalid query: " . $yhteys->errorInfo()[2]);  
                    }

                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo"<tr>
                            <td>" . $row["Tunnus"] . "</td>
                            <td>" . $row["Nimi"] . "</td>
                            <td>" . $row["Kuvaus"] . "</td>
                            <td>" . $row["Alkupaiva"] . "</td>
                            <td>" . $row["Loppupaiva"] . "</td>
                            <td>" . $row["Opettaja"] . "</td>
                            <td>" . $row["Tila"] . "</td>
                            <td>
                                <a class='btn btn-success btn-sm' href='add.php?table=kurssit'>Add</a>
                                <a class='btn btn-primary btn-sm' href='update.php?table=kurssit&id=" . $row["Tunnus"] . "'>Update</a>
                                <a class='btn btn-danger btn-sm' href='delete.php?table=kurssit&id=" . $row["Tunnus"] . "'>Delete</a>                      
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="kirjautumiset">
            <h3>Kirjautumiset</h3>
            <br>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Opiskelija</th>
                        <th>Kurssi</th>
                        <th>Kirjautumispäivä</th>
                        <th>Toiminnot</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php
                    $sql = "SELECT * FROM kurssikirjautuminen";
                    $result = $yhteys->query($sql);  

                    if (!$result) {
                        die("Invalid query: " . $yhteys->errorInfo()[2]);  
                    }

                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo"<tr>
                            <td>" . $row["Tunnus"] . "</td>
                            <td>" . $row["Opiskelija"] . "</td>
                            <td>" . $row["Kurssi"] . "</td>
                            <td>" . $row["Kirjautumispaiva"] . "</td>
                            <td>
                                <a class='btn btn-success btn-sm' href='add.php?table=kurssikirjautuminen'>Add</a>
                                <a class='btn btn-primary btn-sm' href='update.php?table=kurssikirjautuminen&id=" . $row["Tunnus"] . "'>Update</a>
                                <a class='btn btn-danger btn-sm' href='delete.php?table=kurssikirjautuminen&id=" . $row["Tunnus"] . "'>Delete</a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>