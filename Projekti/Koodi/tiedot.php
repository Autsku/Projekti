<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logo</title>
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

    <div class="content">
        <h2 onclick="a()">Oppilaat</h2>
        <div class="oppilaat" id="oppilaat">
            <a class='btn btn-success btn-sm' href='add.php?table=opiskelijat'>Add</a>
            <br>
            <table border="1" cellpadding="10">
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
                                <a class='btn btn-primary btn-sm' href='update.php?table=opiskelijat&id=" . $row["Opiskelijanumero"] . "'>Update</a>
                                <a class='btn btn-danger btn-sm' href='delete.php?table=opiskelijat&id=" . $row["Opiskelijanumero"] . "'>Delete</a>                          
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <h2 onclick="b()">Opettajat</h2>
        <div class="opettajat" id="opettajat">
            <a class='btn btn-success btn-sm' href='add.php?table=opettajat'>Add</a>

            <br>
            <table border="1" cellpadding="10">
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
                                <a class='btn btn-primary btn-sm' href='update.php?table=opettajat&id=" . $row["Tunnusnumero"] . "'>Update</a>
                                <a class='btn btn-danger btn-sm' href='delete.php?table=opettajat&id=" . $row["Tunnusnumero"] . "'>Delete</a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <h2 onclick="c()">Kurssit</h2>
        <div class="kurssit" id="kurssit">
            <a class='btn btn-success btn-sm' href='add.php?table=kurssit'>Add</a>
            <br>
            <table border="1" cellpadding="10">
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
                                <a class='btn btn-primary btn-sm' href='update.php?table=kurssit&id=" . $row["Tunnus"] . "'>Update</a>
                                <a class='btn btn-danger btn-sm' href='delete.php?table=kurssit&id=" . $row["Tunnus"] . "'>Delete</a>                      
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <h2 onclick="d()">Kirjautumiset</h2>
        <div class="kirjautumiset" id="kirjautumiset">
            <a class='btn btn-success btn-sm' href='add.php?table=kurssikirjautuminen'>Add</a>
            <br>
            <table border="1" cellpadding="10">
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

<script>
    function a() {
    var x = document.getElementById("oppilaat");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
    }

    function b() {
    var x = document.getElementById("opettajat");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
    }

    function c() {
    var x = document.getElementById("kurssit");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
    }

    function d() {
    var x = document.getElementById("kirjautumiset");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
    }
</script>

</body>
</html>