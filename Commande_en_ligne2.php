<?php include"connexion_pdo/pdo.php";?>
<?php session_start() ?>
<!-- si l'utilisateur n'a pas choisi de type de commande ou si il n'est pas connecté, il sera redirigé vers la page index --> 
<?php if (!isset($_GET['type']) || empty($_SESSION)) {
	header("location:index.php");
} ?>
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
<!-- 	import du lien bootstrap me permettant d'utilisé les modals -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/pages.css">
	<link rel="stylesheet" type="text/css" href="css/commande_en_ligne2.css">
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
		<article>
		<?php

			foreach ($mes_categories as $une_categorie) {

				// affiche chaque bouton correspondant aux catégories qui fait appel à la fonction permettant d'afficher les différents plats et aussi faisant appel à la modal contenant ces derniers

				echo "<div class='categ' onclick='affichePlat(".$une_categorie["id_categorie"].")'data-bs-toggle='modal' data-bs-target='#modalSelectionCategorie'>";
				echo "<h2>".$une_categorie["nom"]."</h2>";
				echo "</div>";

			}

		;?>
		</article>
		<div id="panier">

				<!-- 	balise span contenant le total de la commande -->

			<p id="panier_total">Total du panier : <span id="myTotal">0</span> €</p>

			<form method="POST" action="validation_commande.php">

				<!--  les données prix total et type de commande sont stocké dans des inputs de type hidden  -->

				<input type="hidden" name="prix_total">
				<input type="hidden" name="type" value="<?php echo $_GET["type"];?>">

			<div id='detail_panier'>
				<!-- stockage des informations du plat dans le formulaire -->
			</div>

			<input type="submit" disabled value="Valider" id="Button" name="valid" class="btn btn-success"></input>
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



<!-- Selection plat modal -->
<div class="modal fade" id="modalSelectionCategorie" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Selectionnez un plat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id='listePlat'>
        
      </div>
    </div>
  </div>
</div>
<!-- 	lien modal js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
	<script type="text/javascript">

		// déclaration des variables plat et total
		var plat;
		var total = 0;
		var id = 0;
		function affichePlat(evt){

			// Création d'une promesse me permettant de récupérer les plats qui sont situés dans le fichier modal_commande_plat sans recharger la page, envoie sur cette page une requête $_GET dont l'index est "id categ" contenant l'id de la catégorie entrée en paramètre de la fonction, le verdict de la promesse sera stocké dans la modal

			var promesse= new Promise((resolve, reject) =>{
				url="modal_commande_plat.php?id_categ="+evt;
				const xhr = new XMLHttpRequest();
				xhr.open("GET",url);
				xhr.onload = () => resolve(xhr.responseText);
				xhr.onerror = () => reject(xhr.statusText);
				xhr.send(null);
			});
			promesse
			.then((verdict) => {document.getElementById("listePlat").innerHTML = verdict; console.log("ca marche")})
			.catch((verdict) => {console.log(verdict)})

		}

		function detailPlat(evt){

			// création d'une promesse me permettant de récupérer un formulaire pour sélectionner les détails du menu (quantité, sauce, boisson, message(optionnel)), envoie sur la page "modal_commande_plat.php" une requête $_GET dont l'index est "detailFormulaire" contenant l'id du plat entré en paramètre de la fonction, le verdict de la promesse sera stocké dans la modal

			// stockage du plat dans une variable
			plat = evt;
			var promesse= new Promise((resolve, reject) =>{
				url="modal_commande_plat.php?detailFormulaire="+evt;
				const xhr = new XMLHttpRequest();
				xhr.open("GET",url);
				xhr.onload = () => resolve(xhr.responseText);
				xhr.onerror = () => reject(xhr.statusText);
				xhr.send(null);
			});
			promesse
			.then((verdict) => {document.getElementById("listePlat").innerHTML = verdict; console.log("ca marche")})
			.catch((verdict) => {console.log(verdict)})

		}

		function infoRecup(){

			// stocke les info prix, message et quantité dans des variable 
			id++;
			var prix = document.getElementsByName('prix')[0].value;
			var message = document.getElementsByName('commentaire')[0].value;
		    var quantite = parseInt(document.getElementsByName('quantite')[0].value);

		    // stocke l'ensemble des radios pour les sauces et l'ensemble des radios pour les boissons dans des variables respectives
		    // stocke l'ensemble des checkbox dans une variable

			var radios1 = document.getElementsByName('sauce');
			var radios2 = document.getElementsByName('boisson');
			var checkbox = document.getElementsByName('condi');

			var condiment = "";
			
			// boucle sur l'ensemble des radios des sauces et stocke ce dernier si celui si contient l'attribut "checked"
			for(var i = 0; i < radios1.length; i++){
		    	if(radios1[i].checked){
 				sauce = parseInt(radios1[i].value);
 				}
			}
			// boucle sur l'ensemble des radios des boissons et stocke ce dernier si celui si contient l'attribut "checked"
			for(var i = 0; i < radios2.length; i++){
		    	if(radios2[i].checked){
 				boisson = parseInt(radios2[i].value);
 				}
			}
			// boucle sur l'ensemble des checkbox des condiments et stocke ces derniers lorsqu'ils contiennent l'attribut "checked"
			for (var i = 0; i < checkbox.length; i++) {
				if(checkbox[i].checked){
 				condiment += checkbox[i].value + ", ";
 				}

			}

			// si aucun attribut n'est validé la variable condiment contiendra le string "Nature.". Sinon il retournera l'ensemble des condiment en retirant la virgule a la fin du string grâce a la fonction native "slice" qui retourne une copie du string en retirant les 2 deniers élément du string (de 0 à -2)
			if(condiment== ""){
				condiment = "Nature.";
			}
			else{
				condiment = condiment.slice(0, -2) + '.';
			}

			console.log(plat, sauce, condiment, message, quantite, boisson, id);
			// Création d'une promesse qui renvoie l'ensemble des informations du menu dans le panier depuis la page modal_commande_plat
				var promesse= new Promise((resolve, reject) =>{
				url="modal_commande_plat.php?plat="+plat+"&sauce="+sauce+"&condiment="+condiment+"&message="+message+"&quantite="+quantite+"&boisson="+boisson+"&id="+id;
				const xhr = new XMLHttpRequest();
				xhr.open("GET",url);
				xhr.onload = () => resolve(xhr.responseText);
				xhr.onerror = () => reject(xhr.statusText);
				xhr.send(null);
			});
			promesse
			.then((verdict) => {document.getElementById("detail_panier").innerHTML += verdict; console.log("ca marche")})
			.catch((verdict) => {console.log(verdict)});
			console.log(prix);

			// Incrémente le total en multipliant la quantité et le prix puis le stock dans l'element du DOM content l'id prix total puis active la possibilité de pouvoir valide la commande en affectant false à l'attribut disable du bouton
			total += quantite*prix;
			document.getElementById('myTotal').innerHTML = total;
			document.getElementsByName('prix_total')[0].value = total;
			console.log(total);
			document.getElementById("Button").disabled = false;
		}

		function annule(evt){
			// retire le menu du panier en retirant la div contenant le formulaire du menu
			var menu;
			var prix_menu;
			var quantite_menu;
			// Sélectionne l'element parent du bouton appelant la fonction annule
			menu = evt.parentNode;
			// Sélectionne le prix ainsi que la quantité du menu annulé grace au queryselector en récupérant le contenu texte des balises
			prix_menu = parseInt(menu.querySelector('#prix_menu').textContent);
			quantite_menu = parseInt(menu.querySelector('#quantite_menu').textContent);
			// Décrémente le total puis l'affiche dans l'element DOM contenant l'id prix total
			document.getElementById('myTotal').innerHTML = (total -= quantite_menu*prix_menu);
			document.getElementsByName('prix_total')[0].value = total;
			document.getElementById('detail_panier').removeChild(menu);

			// si le total de la commande est égal à zéro alors l'attribut disabled du bouton de validation vaudra true
			if(total == 0){
			document.getElementById("Button").disabled = true;	
			}
		}

	</script>
</body>
</html>