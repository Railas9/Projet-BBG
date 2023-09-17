<?php include"connexion_pdo/pdo.php";?>
<?php session_start() ?>
<?php if (empty($_SESSION)) {
	header("location:index.php");
} ?>
<!DOCTYPE html>
<html>
<head>
	<title>BBG Brasserie Burger Grill</title>
	<link rel="stylesheet" type="text/css" href="css/pages.css">
	<link rel="stylesheet" type="text/css" href="css/reserver_table.css">
	<meta name="viewport" content="width=device-width" />
</head>
<body>
	<header>
		<div class="top">
			<div id="empty">
				
			</div>
			<div class="logo">
				<img src="img/logo.png">
			</div>
			<div id="connexion">
				<?php
				if(!empty($_SESSION)) {
					if ($_SESSION['status'] == "1") {
						echo "<a href='crud.php'><p>Espace admin</p></a>";
					}
						echo "<a href='Account.php'><p>Mon compte</p></a>";
				}else{
					echo "<a href='connexion.php'><p>Connexion</p></a>";
					echo "<a href='inscription.php'><p>Inscription</p></a>";
				}
				?>
			</div>
		</div>
			<nav>
				<ul id="navig">
					<a href="index.php"><li>Accueil</li></a>
					<a href="Carte_des_Menus.php"><li>Carte des menus</li></a>
					<a href="Commander_en_Ligne.php"><li>Commander en ligne</li></a>
					<a href="Reserver_table.php"><li>Reserver une table</li></a>
					<a href="Contact.php"><li>Contact</li></a>
				</ul>
			</nav>
	</header>
	<section>
		<h1 hidden>BBG Brasserie Burger Grill</h1>
		<h2 style="text-align: center;">Reserver une table</h2>
		<div style="
		display: flex;
    	justify-content: center;">
			<form action="" method="POST">
			<label for="date">Date : </label><input type="date" name="date" id="date" required>
			<label for="heure">Heure : </label><input type="time" name="heure" id="heure" required><br>
			<label for="nbrPersonne">Nombre de personne : </label><input type="number" name="nbrPersonne" id="nbrPersonne" min="2" required><br>
			<label for="telephone">Numéro de téléphone : </label><input type="tel" name="telephone" id="telephone" value="<?php echo $_SESSION["telephone"];?>" pattern="^[0-9]{10}$" maxlength="10"><br>
			<input type="submit" name="submit" style="margin-top: 1rem">
			</form>	
		</div>

		<?php
		if(isset($_POST['submit'])){
			$date = $_POST['date'];
			$heure = $_POST['heure'];
			$nbrPersonne = $_POST['nbrPersonne'];
			$telephone = htmlspecialchars($_POST['telephone']);
			$pdo = connexpdo('bbg');
			$pdo->prepare('INSERT INTO reservation (`id_visiteur`,`nbr_personne`,`date`,`heure`,`telephone`) VALUES (?,?,?,?,?)')->execute(array($_SESSION['id_visiteur'],$nbrPersonne,$date,$heure,$telephone));
			$ok = "<h2 style='color:green'>Votre reservation a bien été pris en compte !</h2>";
			setcookie('reserv',$ok, time() + 86400);
			header('location:index.php');
		}

		 ;?>
	</section>
	<footer>
		<div id="Contact">
			<h4>Contact</h4>
			<p>Téléphone : 01 85 53 25 47</p>
			<p>Adresse : 362 Rue du Général Leclerc, 95130 Franconville</p>
		</div>
		<div id="reseaux">
			<h4>Reseaux</h4>
			<figure>
				<img src="img/facebook.png">
				<img src="img/instagram.png">
				<img src="img/snapchat.png">
			</figure>
		</div>
	</footer>
</body>
</html>
<script type="text/javascript">
	var today = new Date();
	console.log(today.getMonth());
	var dd = today.getDate();
	var mm = today.getMonth()+1; 
	var yyyy = today.getFullYear();
	if(dd<10){
	    dd='0'+dd;
	    } 
	if(mm<10){
	    mm='0'+mm;
	    }
	today = yyyy+'-'+mm+'-'+dd;
	document.getElementById("date").setAttribute("min", today);
</script>