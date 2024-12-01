<?php
session_start();
$ime = $_SESSION['ime_uporabnika'];
$admin = $_SESSION['admin'];
$id = $_SESSION['id'];



require("baza.php");
$povezava = new mysqli($ime_strežnika, $uporabnisko_ime_db, $geslo_podatkovne_baze, $ime_podatkovne_baze);

if (!$povezava) {
    echo '   <div class="glavni">
        <h1>Nekaj je šlo narobe! : (</h1>
    </div>';
    die();
}

if (isset($_GET["izhod"])) {
    header(header: "location:urnik_cokolad.php");
    die();
}

if (isset($_GET["izbrisi"])) {
    $izbisani = $_GET["izbrisi"];
    foreach ($izbisani as $k => $izbris) {
        $ukaz = "DELETE FROM se_udelezi WHERE UID=" . $k . ";";
        $povezava->query($ukaz);
        $ukaz = "DELETE FROM uporabnik WHERE UID=" . $k . ";";
        $povezava->query($ukaz);
    }
    unset($_GET["izbrisi"]);
}
if (isset($_GET["spremeni_admin"])) {
    $izbisani = $_GET["spremeni_admin"];
    foreach ($izbisani as $k => $izbris) {
        $status = ($izbris == "odstrani") ? 0 : 1;
        $ukaz = "UPDATE uporabnik SET Admin = " . $status . " WHERE UID=" . $k . ";";
        $povezava->query($ukaz);
    }
    unset($_GET["spremeni_admin"]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

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
</head>

<body>
    <div class="glavni">
        <h1>Vroča čokolada</h1>

        <h2>Urejanje uporabnikov</h2>
        <?php
        izpisi_uporabnike($povezava);
        ?>
    </div>
</body>

</html>
<?php
function izpisi_uporabnike($baza)
{
    $ukaz = "SELECT  * FROM Uporabnik";
    $uporabniki =  $baza->query($ukaz);
    echo '<form>';
    echo '<table>';
    echo '<tr><th>ID</th><th>Ime</th><th>Admin</th><th>Izbris</th></tr>';
    while ($vrstica = $uporabniki->fetch_assoc()) {
        $niz_za_admin = ($vrstica["Admin"] == 1) ? 'odstrani' : 'dodaj';
        $dej_niz =   '<input type="submit" name="izbrisi[' . $vrstica["UID"] . ']" value="Izbriši">';
        echo '<tr><td>' . $vrstica["UID"] . '</td><td>' . $vrstica["Ime"] . '</td><th> <input type="submit" name="spremeni_admin[' . $vrstica["UID"] . ']" value="' . $niz_za_admin . '"></th><th><input type="submit" name="izbrisi[' . $vrstica["UID"] . ']" value="Izbriši"></th></tr>';
    }
    echo '</table>';
    echo '<p></p>';
    echo '<input type="submit" name="izhod" value="Izhod">';
    echo '</form>';
}
?>