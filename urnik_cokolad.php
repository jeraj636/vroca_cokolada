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

$izbrani_datumi = [];
if (isset($_GET["kdaj_lahko"])) {
    $izbrani_datumi = $_GET["kdaj_lahko"];
}

if (isset($_GET["uporabniki"])) {
    header("location:urejanje_uporabnikov.php");
    die();
}

if (isset($_GET["potrdi_izbiro"]) || isset($_GET["odjava"])) {
    $ukaz = "DELETE FROM se_udelezi WHERE UID = " . $id . ";";
    $povezava->query($ukaz);
    if (count($izbrani_datumi) > 0) {

        $ukaz = "INSERT INTO se_udelezi VALUES";
        foreach ($izbrani_datumi as $cas_udelezitve) {
            $ura = "";
            preg_match('/\d\d\s/', $cas_udelezitve, $ura);
            $datum = [];
            preg_match('/\d{2}-\d{2}-\d{2}/', $cas_udelezitve, $datum);
            $ukaz .= "(" . $id . ",'" . $datum[0] . "'," . $ura[0] . "),";
        }
        $ukaz[strlen($ukaz) - 1] = ";";
        $povezava->query($ukaz);
    }
}
if (isset($_GET["odjava"])) {
    session_unset();
    session_destroy();
    header("location:index.php");
    die();
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

        <h2>Pozdravljen <?= $ime ?>!</h2>
        <b>Spodaj izberi, kdaj si lahko privoščiš skodelico vroče čokolade!</b>
        <h1></h1><!-- samo seperator -->
        <?php
        echo '<form method="get"><div class = "glavni">';
        izrisi_tabelo($povezava, $ime);
        ?>
        <?php
        if ($admin == 1) {
            echo '<p></p>';
            echo '<form>';
            echo '<input type="submit" name="uporabniki" value="Uporabniki" />';
            echo '</form>';
        }
        echo '<h1></h1>';
        echo '<input type="submit" name="potrdi_izbiro" value="Potrdi izbiro" />';
        echo '<p></p>';
        echo '<input type="submit" name="odjava" value="Odjava" />';
        echo '</div></form>';
        ?>
    </div>
</body>

</html>
<?php
function kdo_se_udelezi($datum, $ura, mysqli_result $udelezba)
{
    $udelezba->data_seek(0);
    $udelezenci = [];
    while ($vrsta = $udelezba->fetch_assoc()) {
        if ($vrsta['Ura'] == $ura && $vrsta['Datum'] == $datum) {
            $udelezenci[] = $vrsta['Ime'];
        }
    }
    //print_r($udelezenci);
    return $udelezenci;
}
function izrisi_tabelo($baza, $moje_ime)
{
    $udelezba = $baza->query("SELECT * FROM udelezba");

    $danes = new DateTime(date("y-m-d"));
    $dan_tedna = date("w");
    $dan_tedna = ($dan_tedna == 0) ? 7 : $dan_tedna;
    $danes->modify("-" . $dan_tedna - 1  . " day");

    $teden = [];
    for ($i = 1; $i <= 7; $i++) {
        $teden[$i] = new DateTime($danes->format("y-m-d"));
        $danes->modify("+1 day");
    }

    echo '<table>';
    echo '<tr><td></td><th>Ponedeljek</th><th>Torek</th><th>Sreda</th><th>Četrtek</th><th>Petek</th><th>Sobota</th><th>Nedelja</th>';
    echo '<tr><td></td>';
    for ($i = 1; $i <= 7; $i++) {
        echo '<th>' . $teden[$i]->format("d.m.Y")  . '</th>';
    }

    for ($i = 12; $i <= 24; $i++) {
        echo '<tr>';
        echo '<th>' . $i . '</th>';
        for ($j = 1; $j <= 7; $j++) {
            echo '<th >';

            $udelezenci = kdo_se_udelezi($teden[$j]->format("Y-m-d"), $i, $udelezba);
            $ali_se_udelezim = false;
            foreach ($udelezenci as $udelezenec) {
                if ($udelezenec != $moje_ime)
                    echo $udelezenec . "<br>";
                else
                    $ali_se_udelezim = true;
            }
            if (!$ali_se_udelezim)
                echo '<input type="checkbox" name="kdaj_lahko[]" value = "' . $i . '  ' . $teden[$j]->format("y-m-d") . '"/></th>';
            else
                echo '<input type="checkbox" name="kdaj_lahko[]" checked="" value = "' . $i . '  ' . $teden[$j]->format("y-m-d") . '"/></th>';
        }
        echo '</tr>';
    }
    echo '</tr>';
    echo '</table>';
}
?>