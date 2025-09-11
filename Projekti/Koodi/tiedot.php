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
        <a href="index.php" class="logo">Oppi</a>
        <div class="items">
            <a href="tiedot.php">Tiedot</a>
            <a href="tilat.php">Tilat</a>
            <a href="kurssit.php">Kurssit</a>
            <a href="opiskelijat.php">Opiskelijat</a>
            <a href="opettajat.php">Opettajat</a>
        </div>
    </div>

    <div class="content">
        <div class="top">
            <h1 onclick="a()">Oppilaat</h1>
            <a class='button' href='add.php?table=opiskelijat'>Add</a>
        </div>
        <div class="oppilaat" id="oppilaat">
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
                                <a class='button' href='update.php?table=opiskelijat&id=" . $row["Opiskelijanumero"] . "'>Update</a>
                                <a class='button' href='delete.php?table=opiskelijat&id=" . $row["Opiskelijanumero"] . "'>Delete</a>                          
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="top">
            <h1 onclick="b()">Opettajat</h1>
            <a class='button' href='add.php?table=opettajat'>Add</a>
        </div>
        <div class="opettajat" id="opettajat">

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
                                <a class='button' href='update.php?table=opettajat&id=" . $row["Tunnusnumero"] . "'>Update</a>
                                <a class='button' href='delete.php?table=opettajat&id=" . $row["Tunnusnumero"] . "'>Delete</a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="top">
            <h1 onclick="c()">Kurssit</h1>
            <a class='button' href='add.php?table=kurssit'>Add</a>
        </div>
        <div class="kurssit" id="kurssit">
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
                    // Tehdään JOIN-operaatiot opettajien ja tilojen taulujen kanssa
                    $sql = "SELECT k.Tunnus, k.Nimi, k.Kuvaus, k.Alkupaiva, k.Loppupaiva,
                                o.Etunimi, o.Sukunimi,
                                t.Nimi AS tila_nimi
                            FROM kurssit k
                            LEFT JOIN opettajat o ON k.Opettaja = o.Tunnusnumero
                            LEFT JOIN tilat t ON k.Tila = t.Tunnus
                            ORDER BY k.Nimi";
                    $result = $yhteys->query($sql);  

                    if (!$result) {
                        die("Invalid query: " . $yhteys->errorInfo()[2]);  
                    }

                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $opettaja = $row["Etunimi"] . " " . $row["Sukunimi"];
                        $tila = $row["tila_nimi"];
                        echo "<tr>
                                <td>" . $row["Tunnus"] . "</td>
                                <td>" . $row["Nimi"] . "</td>
                                <td>" . $row["Kuvaus"] . "</td>
                                <td>" . $row["Alkupaiva"] . "</td>
                                <td>" . $row["Loppupaiva"] . "</td>
                                <td>" . htmlspecialchars($opettaja) . "</td>
                                <td>" . htmlspecialchars($tila) . "</td>
                                <td>
                                    <a class='button' href='update.php?table=kurssit&id=" . $row["Tunnus"] . "'>Update</a>
                                    <a class='button' href='delete.php?table=kurssit&id=" . $row["Tunnus"] . "'>Delete</a>                      
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="top">
            <h1 onclick="d()">Kirjautumiset</h1>
            <a class='button' href='add.php?table=kurssikirjautuminen'>Add</a>
        </div>
        <div class="kirjautumiset" id="kirjautumiset">
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
                        // Haetaan kirjautumiset yhdistäen opiskelijat ja kurssit tauluihin
                        $sql = "SELECT kk.Tunnus,
                                    o.Etunimi, o.Sukunimi,
                                    k.Nimi AS kurssi_nimi,
                                    kk.Kirjautumispaiva
                                FROM kurssikirjautuminen kk
                                LEFT JOIN opiskelijat o ON kk.Opiskelija = o.Opiskelijanumero
                                LEFT JOIN kurssit k ON kk.Kurssi = k.Tunnus
                                ORDER BY kk.Tunnus";
                        $result = $yhteys->query($sql);  

                        if (!$result) {
                            die("Invalid query: " . $yhteys->errorInfo()[2]);  
                        }

                        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $opiskelija = $row["Etunimi"] . " " . $row["Sukunimi"];
                            $kurssi = $row["kurssi_nimi"];
                            echo "<tr>
                                    <td>" . $row["Tunnus"] . "</td>
                                    <td>" . htmlspecialchars($opiskelija) . "</td>
                                    <td>" . htmlspecialchars($kurssi) . "</td>
                                    <td>" . $row["Kirjautumispaiva"] . "</td>
                                    <td>
                                        <a class='button' href='update.php?table=kurssikirjautuminen&id=" . $row["Tunnus"] . "'>Update</a>
                                        <a class='button' href='delete.php?table=kurssikirjautuminen&id=" . $row["Tunnus"] . "'>Delete</a>
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