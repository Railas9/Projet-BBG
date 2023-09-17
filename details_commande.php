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
	<link rel="stylesheet" type="text/css" href="css/details_commande.css">
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
				<a href='Account.php'><p>Mon compte</p></a>
				<?php
					if ($_SESSION['status'] == "1") {
						echo "<a href='crud.php'><p>Espace admin</p></a>";
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
		<h2>Mes commandes</h2>
		<?php 
		$pdo = connexpdo("bbg");
		$commande = $pdo->query("SELECT * FROM commande WHERE id_visiteur= '".$_SESSION['id_visiteur']."' ORDER BY date DESC")->fetchAll(PDO::FETCH_ASSOC);
		foreach ($commande as $key) {
			echo "<h2>Commande id ".$key['id_commande']."</h2>";
			echo "<h3>Ticket total = ".$key['prix']." €</h3>";
			echo "<p>".$key['date']."</p>";
			$detailCommande = $pdo->query("SELECT p.intitule as plat,cd.composition as compo, b.intitule as boisson,s.intitule as sauce,cd.quantite as 								quantite
										   FROM commande_details as cd
										   JOIN boisson as b on b.id_boisson = cd.id_boisson
										   JOIN plat as p on p.id_plat = cd.id_plat
										   JOIN sauce as s on s.id_sauce = cd.id_sauce
										   WHERE cd.id_commande = '".$key['id_commande']."'")->fetchAll(PDO::FETCH_ASSOC);
		echo "<table class='table table-dark table-hover' style='width: 90%; margin: 3em auto;'>
			<thead>
				<tr>
					<th>Nom plat</th>
					<th>Composition</th>
					<th>Boisson</th>
					<th>Sauce</th>
					<th>Quantité</th>
				</tr>
			</thead>
			<tbody>";
			foreach ($detailCommande as $k) {
					echo "<tr>";
					echo "<td>".$k["plat"]."</td>";
					echo "<td>".$k["compo"]."</td>";
					echo "<td>".$k["boisson"]."</td>";
					echo "<td>".$k["sauce"]."</td>";
					echo "<td>".$k["quantite"]."</td>";
					echo "</tr>";
			}
		echo "</tbody>
		</table>";

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