<?php session_start() ?>
<!DOCTYPE html>
<html>
<head>
	<title>BBG Brasserie Burger Grill</title>
	<link rel="stylesheet" type="text/css" href="css/pages.css">
	<link rel="stylesheet" type="text/css" href="css/contact.css">
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
		<div id="contact">
		<h2>Contact</h2>
		<form action="" method="POST">
<!-- 			si l'utilisateur est connecté la valeur de l'input sera son adresse  -->
			<label for="email">Email :</label><input type="text" name="email" 
			value="<?php echo (!empty($_SESSION)) ? $_SESSION['mail'] : ''; ?>" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required><br>
			<label for="sujet">Sujet :</label><input type="text" name="sujet" required><br>
			<label for="message" style="vertical-align: top;">Message :</label><textarea name="message" required></textarea>
			<input type="submit" name="submit">
			<?php
			if(isset($_POST['submit'])){
				$email = htmlspecialchars($_POST['email']);
				$sujet = htmlspecialchars($_POST['sujet']);		
				$message = htmlspecialchars($_POST['message']);
				$destinataire = "railas.benoun92@gmail.com";
				$header = "MIME-Version: 1.0 \r\n";
				$header .= "Content-Type: text/html;charset=utf8 \r\n";
				$header .= "From: ".$email." \r\n";
				$result = mail($destinataire,$sujet,$message,$header);
				$ok = ($result ? "<h2 style='color:green'>Votre message à bien été transmis !</h2>" : '<h2 style="color:red"> Echec dans l\'envoi du message</h2>');
				setcookie("mail",$ok, time() + 86400);
				header("location:index.php");
			}
			?>
		</form>
	</div>
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