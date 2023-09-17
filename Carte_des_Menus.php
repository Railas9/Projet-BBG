<?php include"connexion_pdo/pdo.php";?>
<?php session_start() ?>
<?php 
		$pdo = connexpdo("bbg");
		$req = $pdo->prepare("Select * from categorie");
		$req->execute();
		$mes_categories = $req->fetchAll(PDO::FETCH_ASSOC);

		// stocke toutes les catégories dans une variable

	?>
<!DOCTYPE html>
<html>
<head>
	<title>BBG Brasserie Burger Grill</title>
	<link rel="stylesheet" type="text/css" href="css/pages.css">
	<link rel="stylesheet" type="text/css" href="css/carte_des_menus.css">
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
	<h1 hidden>BBG Brasserie Burger Grill</h1>
	<div id="nav_categ">
		<ul id="categories">
			<?php

			// affiche dans une bar de navigation toutes les categories se trouvant dans la $mes_categorie et les affiche dynamiquement

			foreach ($mes_categories as $une_categorie) {

			// chaque lien contient une variable $_GET ayant pour index "categ" contenant l'id de la catégories de manière dynamique, le nom de ces catégories sont affichées 
			echo "<li><a href='http://localhost:8888/Projet_BBG/Carte_des_Menus.php?categ=".$une_categorie["id_categorie"]."'>".$une_categorie["nom"]."</a></li>";

				}

		;?>
		<li><a href="http://localhost:8888/Projet_BBG/Carte_des_Menus.php">Tout</a></li>
		</ul>
	</div>
	<section>
		<?php
		// Si l'utilisateur a cliqué sur une des catégorie présent dans la barre de navigation alors cela affiche uniquement le plat correspondant à la catégorie sélectionné
			if(isset($_GET["categ"])){


			// Stocke le tuple du plat correspondant a l'id catégorie + jointure avec la table catégorie permettant de récupérer le nom de la catégorie dans le tuple et pouvoir afficher chaque plat dans sa catégorie dans le DOM

				$categ = $_GET["categ"];
				$recup = $pdo->prepare("Select * from plat as p
				 JOIN categorie as c on p.`id_categorie`=c.id_categorie 
				 where c.id_categorie=?");
				$recup->execute(array($categ));
				$selec = $recup->fetchAll(PDO::FETCH_ASSOC);

			// stocke le tuple contenant le nom de la catégorie qui sera stocké dans un tableau $liste
				$req = $pdo->prepare("Select nom from categorie where id_categorie=?");
				$req->execute(array($categ));
				$carte = $req->fetchAll(PDO::FETCH_ASSOC);
				$liste = [];

			}
			
			else{

			// Si l'utilissateur ne ne clique sur aucune catégorie (ou sur lien 'Tout'), stocke chaque tuple + jointure avec la table catégorie permettant de récupérer le nom de la catégorie dans le tuple et pouvoir afficher chaque plat dans sa catégorie dans le DOM
				$req = $pdo->prepare("Select * from plat as p
				 JOIN categorie as c on p.`id_categorie`=c.id_categorie");
				 $req->execute();
				 $selec = $req->fetchAll(PDO::FETCH_ASSOC);

			// stocke le tuple contenant le nom de la catégorie qui sera stocké dans un tableau $liste

				$req = $pdo->prepare("Select nom from categorie");
				$req->execute();
				$carte= $req->fetchAll(PDO::FETCH_ASSOC);
				$liste = [];

			}

			foreach ($carte as $cate){
			// stocke le ou les catégories dans un tableau
				array_push($liste, $cate["nom"]);

			}

			// pour chaque catégorie, une carte contenant les plats correspondant à cette catégorie sera affichées
			foreach ($liste as $name) {
				echo "<article>";
				echo "<h3>".$name."</h3>";
				echo "<div class='card'>";
				
				foreach ($selec as $value){
				// pour chaque tuple de plats sélectionnés si l'index 'nom' correspondant au nom de sa catégorie est égal au nom présent dans le tableau $liste alors les informations du plat seront affiché de manière dynamique
					if($name == $value["nom"]){
						echo "<div class='carte'><ul>";
						echo "<img src='".$value['image']."'>";
						echo "<li>".$value['intitule']."</li>";
						echo "<li>".$value['ingredient']."</li>";
						echo "<li>".$value['prix']." €</li>";
						echo "</ul></div>";
					}
				}
				echo "</div>";
				echo "</article>";
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