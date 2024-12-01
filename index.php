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
		<h2>Prijava</h2>
		<form action="prijava.php" method="post">
			<label for="up_ime">Ime:</label>
			<input type="text" name="up_ime" id="up_ime" /><br><br>
			<label for="up_geslo">Geslo:</label>
			<input type="password" name="up_geslo" id="up_geslo" /><br><br>
			<div class="glavni">
				<input type="submit" name="prijava" value="Prijava" />
				<p></p>
				<input type="submit" name="registracija" value="Registracija" />
				<p></p>
				<input type="submit" name="domov" value="Domov" />
			</div>
		</form>
	</div>
</body>

</html>