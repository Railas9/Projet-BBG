<?php include"connexion_pdo/pdo.php";?>
<?php session_start() ?>
<!DOCTYPE html>
<html>
<head>
	<title>BBG Brasserie Burger Grill</title>
	<link rel="stylesheet" type="text/css" href="css/pages.css">
	<link rel="stylesheet" type="text/css" href="css/commande_en_ligne.css">
	<link rel="stylesheet" type="text/css" href="css/connexion.css">
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
					<?php
					if (!empty($_SESSION)) {
					echo "<li><a href='Commander_en_Ligne.php'><li>Commander en ligne</a></li>
					<li><a href='Reserver_table.php'><li>Reserver une table</a></li>";
					}
					?>
					<a href="Contact.php"><li>Contact</li></a>
				</ul>
			</nav>
	</header>
	<section>
	<h1 hidden>BBG Brasserie Burger Grill</h1>
	<form method="post" action="" enctype="multipart/form-data">
    <label for="username">mail</label><br />
    <input type="text" id="username" name="username" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"/><br /> <br />

    <label for="password">Mot de passe</label><br />
    <input type="password" id="password" name="password"/><br /><br />

     <input type="submit" value="Se connecter"/>

     <?php

		if($_POST){
			// Vérification si le formulaire est bien rempli
			if($_POST['username'] == null || $_POST['password'] == null){

				echo "<p style='color : red;'>Veuillez remplir le formulaire</p>";
			}
			else{

				$username = htmlspecialchars($_POST['username']);
				$password = md5($_POST['password']);
				$pdo = connexpdo("bbg");
				// Sélectionne le tuple où le mail et le Mdp sont présents
				$selec = $pdo->prepare("select * from visiteur where mail=? and mot_de_passe= ?");
				$selec->execute(array($username,$password));
				$tableauResultats=$selec->fetch(PDO::FETCH_ASSOC);

				//si la variable renvoie true alors le compte est trouvée et la session peut contenir les infos de l'utilisateur et le renvoie vers la page d'accueil
				if($tableauResultats){
					$_SESSION['id_visiteur'] = $tableauResultats["id_visiteur"];
					$_SESSION['nom'] = $tableauResultats["nom"];
					$_SESSION['status'] = $tableauResultats["status"];
					$_SESSION['prenom'] = $tableauResultats["prenom"];
					$_SESSION['telephone'] = $tableauResultats["telephone"];
					$_SESSION['numero_rue'] = $tableauResultats["numero_rue"];
					$_SESSION['adresse'] = $tableauResultats["adresse"];
					$_SESSION['ville'] = $tableauResultats["ville"];
					$_SESSION['code_postal'] = $tableauResultats["code_postal"];
					$_SESSION['mail'] = $tableauResultats["mail"];
					$_SESSION['mot_de_passe'] = $tableauResultats["mot_de_passe"];
					$_SESSION['date_inscription'] = $tableauResultats["date_inscription"];
					$_SESSION['profil_pic'] = $tableauResultats["profil_pic"];

					header("location:index.php");
				}
				else{

				// Si la variable renvoie false alors les informations entrées son incorrectes et un message est affiché
				echo "<p style='color : red;'>Mot de passe ou identifiant incorrect</p>";

				}
			}

		}
?>

<a href="inscription.php">Inscription</a>

</form>
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