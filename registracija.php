<?php
if (isset($_POST["nazaj"])) {
    header("location:index.php");
    die();
}
if (isset($_POST["up_ime"]) && isset($_POST["up_geslo"]) && isset($_POST["registracija"])) {
    require("funkcije.php");



    require("baza.php");
    $povezava = new mysqli($ime_strežnika, $uporabnisko_ime_db, $geslo_podatkovne_baze, $ime_podatkovne_baze);

    if (!$povezava) {
        echo '   <div class="glavni">
        <h1>Nekaj je šlo narobe! : (</h1>
    </div>';
        die();
    }

    $up_ime = $_POST["up_ime"];
    $pattern = '/(<|>|\"|\'|;|--|\/|\*|\?|=|DROP|SELECT|INSERT|UPDATE|DELETE|UNION|OR|eval|system|exec|shell_exec)/i';
    if (preg_match($pattern, $up_ime)) {
        header("location:registracija.php");
        unset($_POST["up_ime"]);
        unset($_POST["up_geslo"]);
        unset($_POST["registracija"]);
        die();
    }

    $up_geslo = $_POST["up_geslo"];
    $has_geslo =  password_hash($up_geslo, PASSWORD_DEFAULT);
    echo $has_geslo;
    $ukaz = "INSERT INTO Uporabnik(Ime,Geslo,Admin) VALUES('" . $up_ime . "','" . $has_geslo . "',0);";
    echo $ukaz;
    // $ukaz->bind_param("ss", $up_ime, $has_geslo);

    $povezava->query($ukaz);


    //if (!$rezulatat->num_rows > 0)
    header("location:index.php");

    /*
    $upporabnik_vse =  $rezulatat->fetch_assoc();
    session_start();
    $_SESSION['ime_uporabnika'] = $upporabnik_vse['Ime'];
    $_SESSION['admin'] = $upporabnik_vse['Admin'];
    $_SESSION['id'] = $upporabnik_vse['UID'];

    header("location:urnik_cokolad.php?up=" . zaK($uporabnik));
    */
}




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Jakob Jeraj">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../kokosek/izgled.css">


    <link rel="apple-touch-icon" sizes="180x180" href="Favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="Favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="Favicon/favicon-16x16.png">
    <link rel="manifest" href="Favicon/site.webmanifest">
    <link rel="mask-icon" href="Favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="theme-color" content="#ffffff">

    <title>Vroča čokolada</title>
    <link rel="stylesheet" href="izgled.css" />
</head>

<body>
    <div class="glavni">
        <h1>Vroča čokolada</h1>
        <h2>Registracija</h2>
        <form method="post">
            <label for="up_ime">Ime:</label>
            <input type="text" name="up_ime" id="up_ime" /><br><br>
            <label for="up_geslo">Geslo:</label>
            <input type="password" name="up_geslo" id="up_geslo" /><br><br>
            <div class="glavni">
                <input type="submit" name="registracija" value="Registracija" />
                <p></p>
                <input type="submit" name="nazaj" value="Nazaj" />
            </div>
        </form>
    </div>
</body>

</html>