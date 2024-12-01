<?php

require("funkcije.php");

if (isset($_POST["registracija"])) {
    header("location:registracija.php");
    die();
}
if (isset($_POST["domov"])) {
    header("location:https://kokosek.si");
    die();
}

require("baza.php");
$povezava = new mysqli($ime_strežnika, $uporabnisko_ime_db, $geslo_podatkovne_baze, $ime_podatkovne_baze);

if (!$povezava) {
    echo '   <div class="glavni">
        <h1>Nekaj je šlo narobe! : (</h1>
    </div>';
    die();
}
if (!$_POST["prijava"] || !$_POST["up_ime"] || !$_POST["up_ime"]) {
    header("location:index.php");
    die();
}

$up_ime = $_POST["up_ime"];
$pattern = '/(<|>|\"|\'|;|--|\/|\*|\?|=|DROP|SELECT|INSERT|UPDATE|DELETE|UNION|OR|eval|system|exec|shell_exec)/i';
if (preg_match($pattern, $up_ime)) {
    header("location:index.php");
    unset($_POST["up_ime"]);
    unset($_POST["up_geslo"]);
    die();
}
$up_geslo = $_POST["up_geslo"];

$ukaz = $povezava->prepare("SELECT * FROM Uporabnik WHERE Ime = ?");
$ukaz->bind_param("s", $up_ime);

$ukaz->execute();
$rezulatat = $ukaz->get_result();
$upporabnik_vse =  $rezulatat->fetch_assoc();

if (!$rezulatat->num_rows > 0 || password_verify($up_geslo, $upporabnik_vse["Geslo"]) != 1) {
    header("location:index.php");
    die();
}
session_start();
$_SESSION['ime_uporabnika'] = $upporabnik_vse['Ime'];
$_SESSION['admin'] = $upporabnik_vse['Admin'];
$_SESSION['id'] = $upporabnik_vse['UID'];

header(header: "location:urnik_cokolad.php");
