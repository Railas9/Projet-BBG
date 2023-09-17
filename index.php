<?php include"connexion_pdo/pdo.php";?>
<?php session_start();?>

<!DOCTYPE html>
<html>
<head>
	<title>BBG Brasserie Burger Grill</title>
	<link rel="stylesheet" type="text/css" href="css/pages.css">
	<link rel="stylesheet" type="text/css" href="css/accueil.css">
<!-- 	import de jquery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- 	import du jquery caroussel -->
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.css">
	<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
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

// Affiche les liens connexions et inscription si l'utilisateur n'est pas connecté. S'il est connecté, un lien pour se rendre sur la page compte sera affiché. Vérifie aussi les droits de l'utilisateur grace à la variable session dont l'index correspond au status de l'utilisateur, s'il est égal à 1, le lien vers la page admin sera affiché.

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
	// Si l'utilisateur est connecté, les pages commander en ligne et réserver une table sera affiché.
					if (!empty($_SESSION)) {
					echo "<li><a href='Commander_en_Ligne.php'><li>Commander en ligne</a></li>
					<li><a href='Reserver_table.php'><li>Reserver une table</a></li>";
					}
					?>
					<a href="Contact.php"><li>Contact</li></a>
				</ul>
			</nav>
	</header>

	<div id='banner'>
		<?php

		// Affiche différents messages a l'utilisateur s'il valide une commande, s'inscrit, reserve une table, enregistre un avis ou envoie un mail

		if (isset($_COOKIE['valid'])) {
			echo $_COOKIE['valid'];
			setcookie("valid", "", time()-3600);
		}
		if (isset($_COOKIE['inscrit'])) {
			echo $_COOKIE['inscrit'];
			setcookie("inscrit", "", time()-3600);
		}
		if (isset($_COOKIE['reserv'])) {
			echo $_COOKIE['reserv'];
			setcookie("reserv", "", time()-3600);
		}
		if (isset($_COOKIE['avis'])) {
			echo $_COOKIE['avis'];
			setcookie("avis", "", time()-3600);
		}
		if (isset($_COOKIE['mail'])) {
			echo $_COOKIE['mail'];
			setcookie("mail", "", time()-3600);
		}
		;?>
	</div>
	<h1 hidden>BBG Brasserie Burger Grill</h1>
	<article id="restaurant">
		<div id='presentation'>
			<h2>Présentation</h2>
			<p>
		        Appréciez l’originalité de la cuisine maison de BBG, établissement de restauration rapide. Avec une capacité d’accueil de 90 couverts, notre restaurant de burgers et grillades à Franconville vous reçoit pour vous proposer ses produits frais, ses viandes bio et ses sauces maison. Burgers, grillades, sandwichs et salades… le chef élabore une carte variée pour votre plus grand plaisir. Rendez-vous au 362 rue du Général Leclerc, au cœur de la zone commerçante du centre E. Leclerc à Franconville pour goûter à toutes nos spécialités. Le restaurant est ouvert du lundi au dimanche, de 11h45 à 23h. Nous proposons aussi un service Salon de Thé - Narguilé en soirée.
			</p>
			<a href=""><button>Menus</button></a>
		</div>
		<figure id="devanture">
			<img src="img/devanture.png">
		</figure>
	</article>
	<article id="service">
		<h2>Service</h2>
		<div id="serv3">
				<img src="img/food-package.png">
				<img src="img/delivery-man.png">
				<img src="img/reservation.png">
		</div>
	</article>
	<article>
		<h2>Mapping</h2>
		<div id="map">
			<div id="google_api"></div>
			<p>Adresse : 362 Rue du Général Leclerc, 95130 Franconville<br><br>
					Horaires :<br><br>
					jeudi :   11:30–22:30<br>
					vendredi :   11:30–22:30<br>
					samedi :   11:30–22:30<br>
					dimanche :   11:30–22:30<br>
					lundi :   11:30–22:30<br>
					mardi :   11:30–22:30<br>
					mercredi :   11:30–22:30
			</p>
		</div>
	</article>
	<article>
		<h2>Livre d'or</h2>

<!-- 		caroussel jquery slick -->
		<div class="slide">
			<?php 
				$pdo = connexpdo("bbg");

				// Recupere toutes les données contenu dans la table avis émis par les utilisateurs ainsi que le nom, le prénom et la photo de profil de ces derniers grace à une jointure entre la table avis et visiteur

				$tab = $pdo->query("SELECT a.message,a.note,v.nom,v.profil_pic,v.prenom,a.status
				FROM avis as a 
				JOIN visiteur as v 
				ON v.id_visiteur = a.id_visiteur")->fetchAll(PDO::FETCH_ASSOC);

				// Pour chaque tuple récupéré, si le status de l'avis est = à 1 au préalable modifié par l'administrateur, alors l'avis sera affiché

				foreach ($tab as $key) {

					if($key['status'] == "1"){

						$note = intval($key['note']);
						echo "<div class='avis'>
						<img src='".$key['profil_pic']."' class='pp'>
						<p>".$key['nom']." ".$key['prenom']."</p>
						<div class='star'>";

					// boucle qui affiche le nombre d'étoiles en fonction de la note inscrit par un utilisateur
					// tant que le compteur de la boucle est inférieur ou égal à la note donnée par l'utilisateur, les étoiles jaunes seront affichées

						for ($i=1; $i < 6; $i++) {
							if ($i<= $note) {
								echo "<img src='img/etoile.svg' >";
							}
							else{
								echo "<img src='img/star.svg' >";
							}
						}

						echo "</div>
								<p style='font-style: italic;'> \" ".$key['message']." \"</p>
							</div>";
						}				
				}
			;?>

		</div>
	</article>
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

<script type="text/javascript">

// fonction permettant d'affiché une carte interactive du restaurant

	function initMap(){

		// variable contenant un objet ayant en propriété l'attribut zoom qui correspond au zoom de la carte ainsi que de l'attribut center correspondant aux coordonnées où sera centrée la carte
		var options = {
			zoom: 17,
			center: {lat:48.990469, lng: 2.205571}
		}
		// instancie une classe d'un objet qui prend en paramètre une fonction renvoyant l'objet element du dom correspondant à la balise qui contiendra la carte ainsi que de l'objet correspondant aux options précédemment déclaré. 
		var map = new google.maps.Map(document.getElementById('google_api'),options);
		// instancie une classe d'un objet qui prend en paramètre un objet ayant un attribut correspondant un objet ayant les attributs correspondant aux coordonnées du maqueur, et un attribut correspondant à l'objet map précédemment déclaré
		var marker = new google.maps.Marker({
			position:{lat:48.990465400112036, lng:2.206039584144874},
			map:map
		});
	}
// fonction jquery permettant de créer un caroussel responsive qui prend en paramettre l'element du DOM contenant le slider 
$(document).ready(function(){
$('.slide').slick({
// paramettrage du caroussel
  dots: true,
  infinite: true,
  speed: 300,
  autoplay: true,
  autoplaySpeed: 2000,
  slidesToShow: 4,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
      }
    },
    {
      breakpoint: 950,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
      }
    },
    {
      breakpoint: 500,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});
	});

</script>
<!-- 	import de l'api de google map de manière asynchrone-->
<script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDU9rdaHoeQjyNA6Xf4YgMyfWyHXeQl6sI&callback=initMap&libraries=&v=weekly"
      async
    ></script>

</html>