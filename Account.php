<?php include"connexion_pdo/pdo.php";?>
<?php session_start() ?>
<?php if (empty($_SESSION)) {
	header("location:index.php");
} ?>
<!DOCTYPE html>
<html>
<head>
	<title>BBG Brasserie Burger Grill</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/pages.css">
	<link rel="stylesheet" type="text/css" href="css/account.css">
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
		<div class="banner">
			<div  style="width: 50px; margin-right: 0.5rem;">
				<?php echo "<img id='pp' src='".$_SESSION['profil_pic']."'>";?>
			</div>
			<h3><?php echo $_SESSION['nom']; ?></h3>
			<h3 style="margin-left: 1rem"><?php echo $_SESSION['prenom']; ?></h3>
		</div>
		<a href="deco.php">Déconnexion</a>
		<h2>Mes Informations</h2>
		<form>
			<label for="firstname">Nom : </label><input type="text" name="firstname" value="<?php echo $_SESSION["nom"];?>" readonly><br>
			<label for="lastname">Prenom : </label><input type="text" name="lastname" value="<?php echo $_SESSION["prenom"];?>" readonly><br>
			<label for="phone">Téléphone : </label><input type="text" name="phone" value="<?php echo $_SESSION["telephone"];?>" readonly><br>
			<label for="road_number">Numéro de rue * : </label><input type="text" name="road_number" value="<?php echo $_SESSION["numero_rue"];?>"  readonly><br>
			<label for="adress">Adresse : </label><input type="text" name="adress" value="<?php echo $_SESSION["adresse"];?>" readonly><br>
			<label for="town">Ville : </label><input type="text" name="town" value="<?php echo $_SESSION["ville"];?>" readonly><br>

			<label for="code">Code Postal : </label><input type="text" name="code" value="<?php echo $_SESSION["code_postal"];?>"  readonly><br>
		</form>
		<a href="edit_account.php"><h2>Editer profil</h2></a>
		<a href="details_commande.php"><h2>Mes commandes</h2></a>
		<h2>Laissez un avis</h2>
		<form method="POST" action="">
			<label for="avis">Message :</label><textarea name="avis"></textarea><br>
			<label for="rate">Avis :</label><input type='number' name="rate" min="1" max="5" value="1">
			<input type="submit" name="submitAvis">
		</form>
		<?php
		if(isset($_POST['submitAvis'])){
			$avis = htmlspecialchars($_POST['avis']);
			$rate = $_POST['rate'];
			$pdo = connexpdo("bbg");
			$pdo->prepare("INSERT INTO avis (`id_visiteur`,`message`,`date`,`note`) VALUES(?,?,?,?)")->execute(array($_SESSION['id_visiteur'],$avis,date("Y-m-d"),$rate));
			$ok = "<h2 style='color:green'>Votre avis a bien été pris en compte</h2>";
			setcookie("avis",$ok, time() + 86400);
			header('location: index.php');
		};	
		 ?>
		
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