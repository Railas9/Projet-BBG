<?php include"connexion_pdo/pdo.php";?>
<?php session_start() ?>
<!DOCTYPE html>
<html>
<head>
	<title>BBG Brasserie Burger Grill</title>
	<link rel="stylesheet" type="text/css" href="css/pages.css">
	<link rel="stylesheet" type="text/css" href="css/inscription.css">
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
				<a href="connexion.php"><p>Connexion</p></a>
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
		<form method="POST" action='' enctype="multipart/form-data">
			<label for="firstname">Nom :</label><input type="text" name="firstname"><br>
			<label for="lastname">Prenom :</label><input type="text" name="lastname"><br>
			<label for="phone">Téléphone :</label><input type="tel" name="phone" maxlength="10" pattern="^[0-9]{10}$"><br>
			<label for="road_number">Numéro de rue * :</label><input type="text" name="road_number"><br>
			<label for="adress">Adresse :</label><input type="text" name="adress"><br>
			<label for="town">Ville :</label><input type="text" name="town"><br>
			<label for="code">Code Postal :</label><input type="text" name="code"><br>
			<label for="mail">Mail :</label><input type="text" name="mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"><br>
			<label for="password">Mot de passe :</label><input type="password" name="password" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$">
			<p>Password (UpperCase, LowerCase, Number/SpecialChar and min 8 Chars)</p>
			<label for="password2">Confirmation du mot de passe :</label><input type="password" name="password2"><br>
			<label for="profil_pic">Image de profil :</label><input type="file" name="profil_pic"><br>
			<input type="submit" name="submit">
		</form>
		<?php

		$errors = [];
// gere l'ensemble des possibles erreurs dans un tableau
		if (isset($_POST['submit'])) {
			if (empty($_POST['firstname'])) {
				array_push($errors, 'entrez un nom ');
			}
			if (empty($_POST['lastname'])) {
				array_push($errors, 'entrez un prenom ');
			}
			if (empty($_POST['phone'])) {
				array_push($errors, 'entrez un numéro de téléphone ');
			}
			if (empty($_POST['adress'])) {
				array_push($errors, 'entrez une adresse ');
			}
			if (empty($_POST['town'])) {
				array_push($errors, 'entrez une ville ');
			}
			if (empty($_POST['code'])) {
				array_push($errors, 'entrez un code postal ');
			}
			if (empty($_POST['mail'])) {
				array_push($errors, 'entrez une adresse mail ');
			}
			if (empty($_POST['password'])) {
				array_push($errors, 'entrez un mot de passe ');
			}
			else if ($_POST['password'] != $_POST['password2']){
				array_push($errors, 'les mots de passes ne sont pas similaires');
			}
			if (count($errors) > 0) {
			// si il y'a plus de 0 erreurs
			echo "<div>
					<ul>";
			foreach ($errors as $error) {
				echo "<li>".$error."</li>";
			}
			echo"</ul>
				</div>";
			}
			else{
				// sinon c'est bon

				$firstname = htmlspecialchars($_POST['firstname']);
				$lastname = htmlspecialchars($_POST['lastname']);
				$phone= htmlspecialchars($_POST['phone']);
				$road_number = empty(htmlspecialchars($_POST['road_number'])) ? null : htmlspecialchars($_POST['road_number']);
				$adress = htmlspecialchars($_POST['adress']);
				$town = htmlspecialchars($_POST['town']);
				$code = htmlspecialchars($_POST['code']);
				$mail = htmlspecialchars($_POST['mail']);
				$password = md5($_POST['password']);

				$date = date("Y-m-d");
				$pdo = connexpdo('bbg');

				// verification si mail déja existant
				$exist = $pdo->query("SELECT * from `visiteur` WHERE `mail`= '".$mail."'")->fetch((PDO::FETCH_ASSOC));

				if (empty($exist)) {

				 	if (isset($_FILES['profil_pic']) && !empty($_FILES['profil_pic']['name'])) {

				 		$image = $_FILES['profil_pic'];
				 		$imageName = $_FILES['profil_pic']['name'];
				 		$imageTmp = $_FILES['profil_pic']['tmp_name'];
				 		$imageSize = $_FILES['profil_pic']['size'];
				 		$imageError = $_FILES['profil_pic']['error'];
				 		$imageExt = explode(".", $imageName);
				 		$imageExtention = end($imageExt);

				 		$autorise = array('png','jpg');

				 		if (in_array($imageExtention, $autorise)) {
				 			if ($imageError === 0) {
				 				if ($imageSize < 1000000) {
				 					$imageNewName = uniqid('', true).".".$imageExtention;
				 					$imageDestination = 'profilpic/'.$imageNewName;
				 					move_uploaded_file($imageTmp, $imageDestination);
				 				}else{
				 					echo "<p style='text-align:center' color='warning'>la taille du fichier est trop élevé</p>";
				 				}
				 			}
				 			else{
				 				echo "<p style='text-align:center' color='warning'>il y'a eu une erreur lors de l'envoi</p>";
				 			}
				 		}
				 		else{
				 			echo "<p style='text-align:center' color='red'>le type du fichier n'est pas autorisé (seul .png, .jpg autorisé)</p>";
				 		}
			 		}
			 		else{
			 			// image par defaut
			 			$imageDestination = 'profilpic/no_profil.png';
			 		}

			 		// créer le nouvel utilisateur avec pour status "0" 
					$insert = $pdo->prepare("INSERT INTO `visiteur` (`nom`,`prenom`,`telephone`,`numero_rue`,`adresse`,`ville`,`code_postal`,`mail`,`mot_de_passe`,`status`,`date_inscription`,`profil_pic`)
					VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
				$insert->execute(array($firstname,$lastname,$phone,$road_number,$adress,$town,$code,$mail,$password,0,$date,$imageDestination));
				$ok = "<h2 style='color:green'>Vous êtes inscrit, connectez vous !</h2>".$road_number;
				
				if ($insert->errorInfo()[0] != "00000") {
					// Une erreur s'est produite lors de l'exécution de la requête SQL
					// Afficher les informations d'erreur pour aider à résoudre le problème
					print_r(gettype($road_number));
					//print_r($insert->errorInfo());
				}else{
					setcookie("inscrit",$ok, time() + 86400);
					header('location: index.php');
				}
	
				}else{
					echo "<p>Email déja existant</p>";
				}
			
			}
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