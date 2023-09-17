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
		<?php

// modification du profil

	if(isset($_POST['submitInfo'])){



			if (isset($_FILES['profil_pic']) && !empty($_FILES['profil_pic']['name'])) {
				// modification de photo de profil
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
				 				}
				 				else{
				 					$imageDestination = $_SESSION["profil_pic"];
				 					echo "<p style='text-align:center' color='warning'>la taille du fichier est trop élevé</p>";
				 				}
				 			}
				 			else{
				 				$imageDestination = $_SESSION["profil_pic"];
				 				echo "<p style='text-align:center' color='warning'>il y'a eu une erreur lors de l'envoi</p>";
				 			}
				 		}
				 		else{
				 			$imageDestination = $_SESSION["profil_pic"];
				 			echo "<p style='text-align:center' color='red'>le type du fichier n'est pas autorisé (seul .png, .jpg autorisé)</p>";
				 		}

			 	}
			 else{
			 		$imageDestination = $_SESSION["profil_pic"];
			 		}

				$firstname = htmlspecialchars($_POST['firstname']);
				$lastname = htmlspecialchars($_POST['lastname']);
				$phone= htmlspecialchars($_POST['phone']);
				$road_number = htmlspecialchars($_POST['road_number']);
				$adress = htmlspecialchars($_POST['adress']);
				$town = htmlspecialchars($_POST['town']);
				$code = htmlspecialchars($_POST['code']);
				$pdo = connexpdo("bbg");
				$insert = $pdo->prepare("
					UPDATE visiteur
						SET nom = ? ,
						 prenom = ? ,
						 telephone = ? ,
						 numero_rue = ? ,
						 adresse = ? ,
						 ville = ? ,
						 code_postal = ? ,
						 profil_pic = ? 
						WHERE id_visiteur = ? ");
				
			$insert->execute(array($firstname,$lastname,$phone,$road_number,$adress,$town,$code,$imageDestination,$_SESSION['id_visiteur']));

					$_SESSION['nom'] = $firstname;
					$_SESSION['prenom'] = $lastname;
					$_SESSION['telephone'] = $phone;
					$_SESSION['numero_rue'] = $road_number;
					$_SESSION['adresse'] = $adress;
					$_SESSION['ville'] = $town;
					$_SESSION['code_postal'] = $code;
					$_SESSION['profil_pic'] = $imageDestination;	
			
		}

// modification du mot de passe

		if (isset($_POST['submitMdp'])) {
			$errors = [];
			if ($_POST['password1'] != $_POST['password2']){
				array_push($errors, 'les mots de passes ne sont pas similaires');
			}
			if (md5($_POST['password']) != $_SESSION['mot_de_passe']){
				array_push($errors, 'le mot de passe est incorrect');
			}
				if(count($errors) > 0){
				 	foreach ($errors as $error) {
				 		echo "<p>".$error."</p>";
				 	}
			 	}
			 else{
			 	var_dump($_SESSION['mot_de_passe']);
			 	$password = md5(htmlspecialchars($_POST['password1']));
				$pdo = connexpdo("bbg");
				$insert = $pdo->prepare("
					UPDATE visiteur
						SET mot_de_passe = ? 
						WHERE id_visiteur = ? ");
				$insert->execute(array($password,$_SESSION['id_visiteur']));
				var_dump("c'est bon");
				$_SESSION['mot_de_passe'] = $password;
				var_dump($_SESSION['mot_de_passe']);
			 }
		}
	 ?>
	 <h2>Editer information compte</h2>
		<form method="POST" action="" enctype="multipart/form-data">
			<label for="firstname">Nom : </label><input type="text" name="firstname" value="<?php echo $_SESSION["nom"];?>" required><br>
			<label for="lastname">Prenom : </label><input type="text" name="lastname" value="<?php echo $_SESSION["prenom"];?>" required><br>
			<label for="phone">Téléphone : </label><input type="text" name="phone" value="<?php echo $_SESSION["telephone"];?>" required><br>
			<label for="road_number">Numéro de rue * : </label><input type="text" name="road_number" value="<?php echo $_SESSION["numero_rue"];?>" required><br>
			<label for="adress">Adresse : </label><input type="text" name="adress" value="<?php echo $_SESSION["adresse"];?>" required><br>
			<label for="town">Ville : </label><input type="text" name="town" value="<?php echo $_SESSION["ville"];?>" required><br>

			<label for="code">Code Postal : </label><input type="text" name="code" value="<?php echo $_SESSION["code_postal"];?>" required><br>
			<label for="profil_pic">Image de profil :</label><input type="file" name="profil_pic"><br>
			<input type="submit" name="submitInfo">
		</form>
	<h2>Editer mot de passe</h2>
			<form method="POST" action="">
				<label for="password">Ancien mot de passe : </label><input type="password" name="password" required><br>
				<label for="password1">Nouveau mot de passe : </label><input type="password" name="password1" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" required><br>
				<label for="password2">Confirmer mot de passe : </label><input type="password" name="password2" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" required><br>
			<input type="submit" name="submitMdp">
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
	