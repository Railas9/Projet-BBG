<?php include"connexion_pdo/pdo.php";?>
<?php session_start() ?>
<?php 
		$pdo = connexpdo("bbg");
?>
<?php 
// S'il n'y a pas eu de requête POST et si l'utilisateur ne c'est pas connecté alors il sera redirigé vers la page d'accueil
	if (empty($_POST) && empty($_SESSION)) {
	header("location:index.php");
} ?>
<!DOCTYPE html>
<html>
<head>
	<title>BBG Brasserie Burger Grill</title>
	<link rel="stylesheet" type="text/css" href="css/pages.css">
	<link rel="stylesheet" type="text/css" href="css/validation_commande.css">
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
					<li><a href="index.php">Accueil</a></li>
					<li><a href="Carte_des_Menus.php"><li>Carte des menus</a></li>
					<?php
					if (!empty($_SESSION)) {
					echo "<li><a href='Commander_en_Ligne.php'><li>Commander en ligne</a></li>
					<li><a href='Reserver_table.php'><li>Reserver une table</a></li>";
					}
					?>
					<li><a href="Contact.php"><li>Contact</a></li>
				</ul>
			</nav>
	</header>
	<section>
		<h1 hidden>BBG Brasserie Burger Grill</h1>
<?php

if (isset($_POST['valid'])) {

	// Stocke les menus choisi par l'utilisateur ainsi que le type de commande et le prix total dans des variables

	$menus = $_POST["menu"];
	$type = $_POST["type"];
	$total_prix = $_POST['prix_total'];


	// liste de tous les choix de l'utilisateur

	echo "<div class='list'>";

		foreach ($menus as $key) {
			echo "<div>";
			// Affiche le nom du plat en fonction de l'id du plat
			$req = $pdo->prepare("SELECT intitule FROM plat where id_plat = ? ");
			$req->execute(array($key['id_plat']));
			$platNom = $req->fetch(PDO::FETCH_ASSOC);
			echo "<p>".$key["quantite"]." X ".$platNom['intitule']."</p>";

			// Affiche le nom de la boisson en fonction de l'id de la boisson
			$req = $pdo->prepare("SELECT intitule FROM boisson where id_boisson = ? ");
			$req->execute(array($key['boisson']));
			$boissonTab = $req->fetch(PDO::FETCH_ASSOC);
				
				$boisson = $boissonTab['intitule'];

			echo "<p>".$boisson."</p>";
			// Affiche le nom de la sauce en fonction de l'id de la sauce
			$sauceTab = $pdo->query("SELECT intitule FROM sauce where id_sauce = '".$key['sauce']."' ")->fetch(PDO::FETCH_ASSOC);

			$sauce = $sauceTab['intitule'];

			echo "<p>".$sauce."</p>";
			echo "<p>".$key['condiment']."</p>";

			// Si le message est vide il affichera le string "Vide."
			if($key['message'] == ""){
				$message = "Vide";
			}else{
				$message = $key['message'];
			}
			echo "<p>message : ".$message."</p>";
			echo "<hr style='width:80%'></hr>";
			echo "</div>";
		}

	echo "</div>";

	// Affiche differents display en fonction du type de commande

	if ($type == "recuperer") {

		echo "<h4>Ticket Total = ".$total_prix." € </h4>";
		echo "<h3>Voulez vous validez cette commande ?</h3>";

		// affiche un formulaire dont les inputs sont cachés et qui contiennent les détails de chaque menu
		echo "<form action='validation_commande.php' method='POST'>
		<input type='hidden' name='prix_total' value='".$total_prix."'>
		<input type='hidden' name='type' value='".$type."'>";
		foreach ($menus as $key) {
			// boucle sur chaque menu et insère les détails des menus dans des inputs cachés en nommant les inputs avec un tableau associatif afin de trier les données
			echo "<input type='hidden' value='".$key['id_plat']."' name='menu[".$key['id']."][id_plat]'>
  				  <input type='hidden' value='".$key['sauce']."' name='menu[".$key['id']."][sauce]'>
  			      <input type='hidden' value='".$key['message']."' name='menu[".$key['id']."][message]'>
  			      <input type='hidden' value='".$key['quantite']."' name='menu[".$key['id']."][quantite]'>
       			  <input type='hidden' value='".$key['condiment']."' name='menu[".$key['id']."][condiment]'>
                  <input type='hidden' value='".$key['boisson']."' name='menu[".$key['id']."][boisson]'>";
		}
		echo "<input type='submit' name='validationRecuperer'>";
		echo "</form>";
	}

	// si l'utilisateur a choisi la livraison alors un formulaire sera rajouté pour connaitre l'adresse de la livraison ainsi que le numéro de téléphone. Les inputs correspondant à ses données seront par défaut rempli par l'actuelle adresse et numéro de l'utilisateur grace au $_SESSION
	if ($type == "livraison") {
		// frais supplementaire
		echo "<h4>Ticket Total = ".$total_prix." € (+ 1,5 € de frais de livraison)</h4>";
		echo "<h3>Voulez vous validez cette commande ?</h3>";
		echo "<form action='validation_commande.php' method='POST'>
		<input type='hidden' name='prix_total' value='".$total_prix."'>
		<input type='hidden' name='type' value='".$type."'>";
		echo "<label for='phone'>Téléphone : </label><input type='text' name='phone' value='".$_SESSION['telephone']."'><br>
			<label for='road_number'>Numéro de rue * : </label><input type='text' name='road_number' value='".$_SESSION['numero_rue']."'><br>
			<label for='adress'>Adresse : </label><input type='text' name='adress' value='".$_SESSION['adresse']."'><br>
			<label for='town'>Ville : </label><input type='text' name='town' value='".$_SESSION['ville']."'><br>
			<label for='code'>Code Postal : </label><input type='text' name='code' value='".$_SESSION['code_postal']."'><br>";
		foreach ($menus as $key) {
	// boucle sur chaque menu et insère les détails des menus dans des inputs cachés en nommant les inputs avec un tableau associatif afin de trier les données
			echo "<input type='hidden' value='".$key['id_plat']."' name='menu[".$key['id_plat']."][id_plat]'>
  				  <input type='hidden' value='".$key['sauce']."' name='menu[".$key['id_plat']."][sauce]'>
  			      <input type='hidden' value='".$key['message']."' name='menu[".$key['id_plat']."][message]'>
  			      <input type='hidden' value='".$key['quantite']."' name='menu[".$key['id_plat']."][quantite]'>
       			  <input type='hidden' value='".$key['condiment']."' name='menu[".$key['id_plat']."][condiment]'>
                  <input type='hidden' value='".$key['boisson']."' name='menu[".$key['id_plat']."][boisson]'>";
		}
		echo "<input type='submit' name='validationLivraison'>";
		echo "</form>";
	}

}

	if (isset($_POST['validationRecuperer'])) {

		$menus = $_POST["menu"];
		$type = $_POST["type"];

		$total_prix = $_POST['prix_total'];

		// insere dans la table commande les données général de la commande c'est a dire l'id du client, le prix, la date et l'heure à laquel la commande a été validé avec la fonction date et le type
		$pdo->prepare("INSERT INTO `commande` (`id_visiteur`,`prix`,`date`,`type`) VALUES (?,?,?,?)")->execute(array($_SESSION['id_visiteur'],$total_prix,date("Y-m-d H:i:s"),$type));
		// Méthode permettant de récupérer le dernier id de la commande inséré afin de pouvoir insérer cet id dans la table commande détails en tant que clé étrangère
		$last_id = $pdo->lastInsertId();
		foreach ($menus as $key) {
		// insertion les détails de chaque menu dans la table commande_details avec la clé étrangère qui est l'id de la commande générale
		   $pdo->prepare("INSERT INTO `commande_details` (`id_commande`,`id_plat`,`id_boisson`,`id_sauce`,`quantite`,`composition`,`message`) VALUES (?,?,?,?,?,?,?)")->execute(array($last_id,$key['id_plat'],$key['boisson'],$key['sauce'],$key['quantite'],$key['condiment'],$key['message']));
		}
		$ok = "<h2 style='color:green'>La commande a bien été envoyé</h2>";
		setcookie("valid",$ok, time() + 86400);
		header('location: index.php');
		}

	if (isset($_POST['validationLivraison'])) {

		$menus = $_POST["menu"];
		$type = $_POST["type"];
	// Concatenation de l'adresse de l'utilisateur
		$adresse = htmlspecialchars($_POST["road_number"])." ".htmlspecialchars($_POST["adress"])." ".htmlspecialchars($_POST["town"])." ".htmlspecialchars($_POST["code"]);
		$phone = htmlspecialchars($_POST['phone']);
		$total_prix = $_POST['prix_total'];

		// insere dans la table commande les données général de la commande c'est a dire l'id du client, le prix, la date et l'heure à laquel la commande a été validé avec la fonction date, le type, l'adresse, et le numéro de téléphone
		$pdo->prepare("INSERT INTO `commande` (`id_visiteur`,`prix`,`date`,`type`,`adresse_livraison`,`telephone`) VALUES (?,?,?,?,?,?)")->execute(array($_SESSION['id_visiteur'],$total_prix,date("Y-m-d H:i:s"),$type,$adresse,$phone));
		// Méthode permettant de récupérer le dernier id de la commande inséré afin de pouvoir insérer cet id dans la table commande détails en tant que clé étrangère
		$last_id = $pdo->lastInsertId();

		foreach ($menus as $key) {
		// insertion les détails de chaque menu dans la table commande_details avec la clé étrangère qui est l'id de la commande générale
		   $pdo->prepare("INSERT INTO `commande_details` (`id_commande`,`id_plat`,`id_boisson`,`id_sauce`,`quantite`,`composition`,`message`) VALUES (?,?,?,?,?,?,?)")->execute(array($last_id,$key['id_plat'],$key['boisson'],$key['sauce'],$key['quantite'],$key['condiment'],$key['message']));
		}
		$ok = "<h2 style='color:green'>La commande a bien été envoyé</h2>";
		setcookie("valid",$ok, time() + 86400);
		header('location: index.php');
	}


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