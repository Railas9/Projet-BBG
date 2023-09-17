<?php include"connexion_pdo/pdo.php";?>
<?php session_start() ?>
<?php

	// Accès réservé à l'admin du site
	if(empty($_SESSION)) {
		if($_SESSION['status'] != "1") {
			header("location:index.php");
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>BBG Brasserie Burger Grill</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/pages.css">
	<link rel="stylesheet" type="text/css" href="css/crud.css">
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
						echo "<a href='Account.php'><p>Mon compte</p></a>";
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

<!-- 	table visiteur -->

		<h2 id="visiteur">Visiteur</h2>
<?php

// Si l'utilisateur a rempli le formulaire deletevisitor alors on supprime le tuple ou l'id du visiteur est égal à l'id du visiteur dans le formulaire

		if (isset($_POST['deleteVisitor'])) {

		$id_visiteur = $_POST['id_visiteur'];
		$pdo->prepare("DELETE FROM visiteur WHERE id_visiteur=? ")->execute(array($id_visiteur));
		echo "<p color='green' style='text-align:center;'>Le client id ".$id_visiteur." a bien été supprimé</p>";
		}
?>
		<table class="table table-dark table-hover" style="width: 90%; margin: 3em auto;">
			<thead>
				<tr>
					<th>Id visiteur</th>
					<th>Nom</th>
					<th>Prenom</th>
					<th>Numero rue</th>
					<th>Adresse</th>
					<th>Ville</th>
					<th>Code postal</th>
					<th>Mail</th>
					<th>Téléphone</th>
					<th>Date d'inscription</th>
					<th>Supprimer</th>
				</tr>
			</thead>
			<tbody>
				<?php 

				$pdo = connexpdo("bbg");
				$req = $pdo->query("SELECT * FROM visiteur where NOT status = 1 ")->fetchAll(PDO::FETCH_ASSOC);
			// récupère les infos de chaque client qui n'est pas admin et les affiche dans le tableau
				foreach ($req as $key) {
					echo "<tr>";
					echo "<td>".$key["id_visiteur"]."</td>";
					echo "<td>".$key["nom"]."</td>";
					echo "<td>".$key["prenom"]."</td>";
					echo "<td>".$key["numero_rue"]."</td>";
					echo "<td>".$key["adresse"]."</td>";
					echo "<td>".$key["ville"]."</td>";
					echo "<td>".$key["code_postal"]."</td>";
					echo "<td>".$key["mail"]."</td>";
					echo "<td>".$key["telephone"]."</td>";
					echo "<td>".$key["date_inscription"]."</td>";
			// bouton qui appelle une fonction qui apporte un formulaire permettant de supprimer un visiteur en prenant en paramètre son id et appelle aussi la modal "suppression"
					echo "<td><button onclick='deleteVisitor(".$key["id_visiteur"].")' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#modalSuppression'>Supprimer</button></td>";
					echo "</tr>";
				}

				 ?>
			</tbody>
		</table>

<!-- 	table plat -->

		<h2 id="plat">Plat</h2>
		<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAjout">
 		 Ajouter un plat
		</button>

<?php
	
	// ajout plat pdo

	if (isset($_POST['ajout']) && $_POST['ajout'] != null) {
	 	$intitule = htmlspecialchars($_POST['intitule']);
	 	$ingredient = htmlspecialchars($_POST['ingredient']);
	 	$prix = htmlspecialchars($_POST['prix']);
	 	$categorie = $_POST['categorie'];
	 	

	 	// verification de l'image

	 	if (isset($_FILES['img'])) {
	 		$image = $_FILES['img'];
	 		$imageName = $_FILES['img']['name'];
	 		$imageTmp = $_FILES['img']['tmp_name'];
	 		$imageSize = $_FILES['img']['size'];
	 		$imageError = $_FILES['img']['error'];

	 		// separe le nom du ficher de son extention
	 		$imageExt = explode(".", $imageName);
	 		$imageExtention = end($imageExt);

	 		// fichier autorisé
	 		$autorise = array('png','jpg');

	 		// Vérifie si l'image est autorisée, s'il n'y a pas d'erreur et si la taille du fichier est inférieure à 1000000 octets alors le fichier aura un nouveau nom avec la fonction uniqid puis sera transporté de son emplacement temporaire à son nouvel emplacement

	 		if (in_array($imageExtention, $autorise)) {
	 			if ($imageError === 0) {
	 				if ($imageSize < 1000000) {
	 					$imageNewName = uniqid('', true).".".$imageExtention;
	 					$imageDestination = 'img/'.$imageNewName;
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
	 			echo "<p style='text-align:center' color='warning'>le type du fichier n'est pas autorisé</p>";
	 		}
	 	}
	 // insere le nouveau plat dans la table plat
	$pdo->prepare("INSERT INTO `plat` (`intitule`,`ingredient`,`prix`,`image`,`id_categorie`)
				VALUES (?,?,?,?,?)")->execute(array($intitule,$ingredient,$prix,$imageDestination,$categorie));
	echo "<p color='green' style='text-align:center;'>Le plat a bien été ajouté</p>";
	}

?>
<!-- edition du plat  -->

<?php 
	if (isset($_POST['editPlat']) && $_POST['editPlat'] != null) {

		$id_plat = $_POST['id_plat'];
		$intitule = htmlspecialchars($_POST['intitule']);
		$prix = htmlspecialchars($_POST['prix']);
		$ingredient = htmlspecialchars($_POST['ingredient']);
		$categorie = $_POST['categorie'];

		// modifie le plat ou l'id plat est égal à l'id_plat du formulaire
		$pdo->prepare("UPDATE plat SET intitule=?, prix=?, ingredient=?, id_categorie=? 
			WHERE id_plat=?")->execute(array($intitule,$prix,$ingredient,$categorie,$id_plat));
		echo "<p color='green' style='text-align:center;'>Le plat id ".$id_plat." a bien été modfié</p>";
	}
		if (isset($_POST['deletePlat'])) {

		$id_plat = $_POST['id_plat'];
		// supprime le plat ou l'id plat est égal à l'id_plat du formulaire
		$pdo->prepare("DELETE FROM plat WHERE id_plat=? ")->execute(array($id_plat));
		echo "<p color='green' style='text-align:center;'>Le plat id ".$id_plat." a bien été supprimé</p>";
	}
	
?>


		<table class="table table-dark table-hover" style="width: 90%; margin: 3em auto;">
			<thead>
				<tr>
					<th>Id plat</th>
					<th>Intitulé</th>
					<th>Ingrédient</th>
					<th>Prix</th>
					<th>Id catégorie</th>
					<th>Modifier</th>
					<th>Supprimer</th>
				</tr>
			</thead>
			<tbody>
				<?php 

				$pdo = connexpdo("bbg");
				$req = $pdo->query("SELECT * FROM plat")->fetchAll(PDO::FETCH_ASSOC);

				foreach ($req as $key) {
					echo "<tr>";
					echo "<td>".$key["id_plat"]."</td>";
					echo "<td>".$key["intitule"]."</td>";
					echo "<td>".$key["ingredient"]."</td>";
					echo "<td>".$key["prix"]."</td>";
					echo "<td>".$key["id_categorie"]."</td>";
					// bouton qui appelle une fonction qui apporte un formulaire permetant d'editer un plat en prenant en parametre son id et appelle aussi la modal "edition"
					echo "<td><button onclick='editPlat(".$key["id_plat"].")' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modalEdition'>Editer</button></td>";
					// bouton qui appelle une fonction qui apporte un formulaire permetant de supprimer un visiteur en prenant en parametre son id et appelle aussi la modal "suppression"
					echo "<td><button onclick='deletePlat(".$key["id_plat"].")' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#modalSuppression'>Supprimer</button></td>";
					echo "</tr>";
				}

				 ?>
			</tbody>
		</table>

<!-- 	table Commande -->

		<h2 id="commande">Commande</h2>

		<table class="table table-dark table-hover" style="width: 90%; margin: 3em auto;">
			<thead>
				<tr>
					<th>Id commande</th>
					<th>Id visiteur</th>
					<th>Prix</th>
					<th>Date</th>
					<th>Adresse livraison</th>
					<th>Telephone</th>
					<th>Type</th>
					<th>Details</th>
				</tr>
			</thead>
			<tbody>
				<?php 

				$pdo = connexpdo("bbg");
				$req = $pdo->query("SELECT * FROM commande")->fetchAll(PDO::FETCH_ASSOC);

				foreach ($req as $key) {
					echo "<tr>";
					echo "<td>".$key["id_commande"]."</td>";
					echo "<td>".$key["id_visiteur"]."</td>";
					echo "<td>".$key["prix"]."</td>";
					echo "<td>".$key["date"]."</td>";
					echo "<td>".$key["adresse_livraison"]."</td>";
					echo "<td>".$key["telephone"]."</td>";
					echo "<td>".$key["type"]."</td>";
					// bouton qui appelle une fonction qui apporte un formulaire permettant d'afficher les details d'une commande en prenant en paramètre son id et appelle aussi la modal "details"
					echo "<td><button onclick='details(".$key["id_commande"].")' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modalDetails'>Details</button></td>";
					echo "</tr>";
				}

				 ?>
			</tbody>
		</table>

<!-- 	table Reservation -->

		<h2 id="reservation">Reservation</h2>
<?php
		if (isset($_POST['deleteReserv'])) {

		$id_reservation = $_POST['id_reservation'];
		$pdo->prepare("DELETE FROM reservation WHERE id_reservation=? ")->execute(array($id_reservation));
		echo "<p style='text-align:center;'>La reservation ".$id_reservation." a bien été supprimé</p>";
	}

?>
		<table class="table table-dark table-hover" style="width: 90%; margin: 3em auto;">
			<thead>
				<tr>
					<th>Id reservation</th>
					<th>Id visiteur</th>
					<th>Nombre de personne</th>
					<th>Date</th>
					<th>Heure</th>
					<th>Telephone</th>
					<th>Supprimer</th>
				</tr>
			</thead>
			<tbody>
				<?php 

				$pdo = connexpdo("bbg");
				$req = $pdo->query("SELECT * FROM reservation")->fetchAll(PDO::FETCH_ASSOC);

				foreach ($req as $key) {
					echo "<tr>";
					echo "<td>".$key["id_reservation"]."</td>";
					echo "<td>".$key["id_visiteur"]."</td>";
					echo "<td>".$key["nbr_personne"]."</td>";
					echo "<td>".$key["date"]."</td>";
					echo "<td>".$key["heure"]."</td>";
					echo "<td>".$key["telephone"]."</td>";
					// bouton qui appelle une fonction qui apporte un formulaire permettant de supprimer une reservation en prenant en paramètre son id et appelle aussi la modal "suppression"
					echo "<td><button onclick='deleteReserv(".$key["id_reservation"].")' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#modalSuppression'>Supprimer</button></td>";
					echo "</tr>";
				}

				 ?>
			</tbody>
		</table>

<!-- 	table Sauce -->

		<h2 id="sauce">Sauce</h2>

		<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAjoutSauce">
 		 Ajouter une sauce
		</button>
<?php
		if (isset($_POST['deleteSauce'])) {

		$id_sauce = $_POST['id_sauce'];
		// supprime la sauce ou l'id sauce est égal à l'id_sauce du formulaire
		$pdo->prepare("DELETE FROM sauce WHERE id_sauce=? ")->execute(array($id_sauce));
		echo "<p style='text-align:center;'>La sauce id ".$id_sauce." a bien été supprimé</p>";
	}

?>
<?php 
	if (isset($_POST['ajoutSauce']) && $_POST['ajoutSauce'] != null) {
		$intitule = htmlspecialchars($_POST['intitule']);
		$status = $_POST['status'];
		// Ajoute la sauce dans la table sauce
		$pdo->prepare("INSERT INTO `sauce` (`intitule`,`status`) VALUES (?,?)")->execute(array($intitule,$status));
		echo "<p style='text-align:center;'>La sauce a bien été ajouté</p>";
	}
?>
<?php 
	if (isset($_POST['editSauce']) && $_POST['editSauce'] != null) {

		$id_sauce = $_POST['id_sauce'];
		$status = $_POST['status'];
		// modifie la sauce ou l'id sauce est égal à l'id_sauce du formulaire
		$pdo->prepare("UPDATE sauce SET status=? WHERE id_sauce=?")->execute(array($status,$id_sauce));
		echo "<p color='green' style='text-align:center;'>La sauce id ".$id_sauce." a bien été modfié</p>";
	}
?>
		<table class="table table-dark table-hover" style="width: 90%; margin: 3em auto;">
			<thead>
				<tr>
					<th>Id sauce</th>
					<th>Intitule</th>
					<th>Status</th>
					<th>Modifier</th>
					<th>Supprimer</th>
				</tr>
			</thead>
			<tbody>
				<?php 

				$pdo = connexpdo("bbg");
				$req = $pdo->query("SELECT * FROM sauce")->fetchAll(PDO::FETCH_ASSOC);

				foreach ($req as $key) {
					echo "<tr>";
					echo "<td>".$key["id_sauce"]."</td>";
					echo "<td>".$key["intitule"]."</td>";
					if($key['status'] == "0"){
					echo "<td><span class='badge bg-success'>Disponible</span></td>";
					}
					else{
					echo "<td><span class='badge bg-danger'>Indisponible</span></td>";
					}
					// bouton qui appelle une fonction qui apporte un formulaire permettant d'editer une sauce en prenant en paramètre son id et appelle aussi la modal "edition"
					echo "<td><button onclick='editSauce(".$key["id_sauce"].")' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modalEdition'>Editer</button></td>";
					// bouton qui appelle une fonction qui apporte un formulaire permettant de supprimer une sauce en prenant en paramètre son id et appelle aussi la modal "suppression"
					echo "<td><button onclick='deleteSauce(".$key["id_sauce"].")' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#modalSuppression'>Supprimer</button></td>";
					echo "</tr>";
				}

				 ?>
			</tbody>
		</table>
<!-- 	table Boisson -->
		<h2 id="boisson">Boisson</h2>

		<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAjoutBoisson">
 		 Ajouter une boisson
		</button>
<?php
		if (isset($_POST['deleteBoisson'])) {
		// supprime la boisson ou l'id boisson est égal à l'id_boisson du formulaire
		$id_boisson = $_POST['id_boisson'];
		$pdo->prepare("DELETE FROM boisson WHERE id_boisson=? ")->execute(array($id_boisson));
		echo "<p style='text-align:center;'>La boisson id ".$id_boisson." a bien été supprimé</p>";
	}

?>
<?php 
	if (isset($_POST['ajoutBoisson']) && $_POST['ajoutBoisson'] != null) {
		$intitule = htmlspecialchars($_POST['intitule']);
		$status = $_POST['status'];
		// ajout la boissondans la table boisson
		$pdo->prepare("INSERT INTO `boisson` (`intitule`,`status`) VALUES (?,?)")->execute(array($intitule,$status));
		echo "<p style='text-align:center;'>La boisson a bien été ajouté</p>";
	}
?>
<?php 
	if (isset($_POST['editBoisson']) && $_POST['editBoisson'] != null) {

		$id_boisson = $_POST['id_boisson'];
		$status = $_POST['status'];
		// modifie la boisson ou l'id boisson est égal à l'id_boisson du formulaire
		$pdo->prepare("UPDATE boisson SET status=? WHERE id_boisson=?")->execute(array($status,$id_boisson));
		echo "<p color='green' style='text-align:center;'>La boisson id ".$id_boisson." a bien été modfié</p>";
	}
?>
		<table class="table table-dark table-hover" style="width: 90%; margin: 3em auto;">
			<thead>
				<tr>
					<th>Id boisson</th>
					<th>Intitule</th>
					<th>Status</th>
					<th>Modifier</th>
					<th>Supprimer</th>
				</tr>
			</thead>
			<tbody>
				<?php 

				$pdo = connexpdo("bbg");
				$req = $pdo->query("SELECT * FROM boisson")->fetchAll(PDO::FETCH_ASSOC);

				foreach ($req as $key) {
					echo "<tr>";
					echo "<td>".$key["id_boisson"]."</td>";
					echo "<td>".$key["intitule"]."</td>";
					if($key['status'] == "0"){
					echo "<td><span class='badge bg-success'>Disponible</span></td>";
					}
					else{
					echo "<td><span class='badge bg-danger'>Indisponible</span></td>";
					}
					// bouton qui appelle une fonction qui apporte un formulaire permettant d'editer une boisson en prenant en paramètre son id et appelle aussi la modal "edition"
					echo "<td><button onclick='editBoisson(".$key["id_boisson"].")' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modalEdition'>Editer</button></td>";
					// bouton qui appelle une fonction qui apporte un formulaire permettant de supprimer une boisson en prenant en paramètre son id et appelle aussi la modal "suppression"
					echo "<td><button onclick='deleteBoisson(".$key["id_boisson"].")' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#modalSuppression'>Supprimer</button></td>";
					echo "</tr>";
				}

				 ?>
			</tbody>
		</table>
<!-- 	table Avis -->
		<h2 id="avis">Avis</h2>
	<?php
		if (isset($_POST['supprimeAvis'])) {

		$id_avis = $_POST['id_avis'];
		// supprime l'avis ou l'id avis est égal à l'id_avis du formulaire
		$pdo->prepare("DELETE FROM avis WHERE id_avis=? ")->execute(array($id_avis));
		echo "<p style='text-align:center;'>L'avis' id ".$id_avis." a bien été supprimé</p>";
	}
		if (isset($_POST['afficheAvis'])) {

		$id_avis = $_POST['id_avis'];
		// modifie le status de l'avis pour être affiché.
		$pdo->prepare("UPDATE avis SET status = '1' WHERE id_avis=? ")->execute(array($id_avis));
		echo "<p style='text-align:center;'>L'avis' id ".$id_avis." a bien été affiché</p>";
	}

	?>
		<table class="table table-dark table-hover" style="width: 90%; margin: 3em auto;">
			<thead>
				<tr>
					<th>Id avis</th>
					<th>Id visiteur</th>
					<th>Message</th>
					<th>Date</th>
					<th>Note</th>
					<th>Status</th>
					<th>Afficher</th>
					<th>Supprimer</th>
				</tr>
			</thead>
			<tbody>
				<?php 

				$pdo = connexpdo("bbg");
				$req = $pdo->query("SELECT * FROM avis")->fetchAll(PDO::FETCH_ASSOC);

				foreach ($req as $key) {
					echo "<tr>";
					echo "<td>".$key["id_avis"]."</td>";
					echo "<td>".$key["id_visiteur"]."</td>";
					echo "<td>".$key["message"]."</td>";
					echo "<td>".$key["date"]."</td>";
					echo "<td>".$key["note"]."</td>";
					echo "<td>".$key["status"]."</td>";
					// bouton qui appelle une fonction qui apporte un formulaire permettant de valider un avis en prenant en paramètre son id et appelle aussi la modal "edition", désactivé si l'avis est déja validé.
					if ($key['status'] == "1") {
					echo "<td><button onclick='afficheAvis(".$key["id_avis"].")' class='btn btn-success' data-bs-toggle='modal' data-bs-target='#modalEdition' disabled>Valider</button></td>";
					}else{
					echo "<td><button onclick='afficheAvis(".$key["id_avis"].")' class='btn btn-success' data-bs-toggle='modal' data-bs-target='#modalEdition'>Valider</button></td>";
					}
					// bouton qui appelle une fonction qui apporte un formulaire permettant de supprimer un avis en prenant en paramètre son id et appelle aussi la modal "suppression"
					echo "<td><button onclick='supprimeAvis(".$key["id_avis"].")' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#modalSuppression'>Supprimer</button></td>";
					echo "</tr>";
				}

				 ?>
			</tbody>
		</table>

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

	<!-- ancres -->
	<div id="ancre">
		<a href="#visiteur" class='badge bg-success'>visiteur</a>
		<a href="#plat" class='badge bg-success'>plat</a>
		<a href="#commande" class='badge bg-success'>commande</a>
		<a href="#reservation" class='badge bg-success'>reservation</a>
		<a href="#sauce" class='badge bg-success'>sauce</a>
		<a href="#boisson" class='badge bg-success'>boisson</a>
		<a href="#avis" class='badge bg-success'>avis</a>
	</div>



	<!-- modals -->

	     <!-- ajout plat modal -->

	     <!-- recupere les catégories -->
<?php 
		$mes_categories = $pdo->query("Select * from categorie")->fetchAll(PDO::FETCH_ASSOC);
	?>

<div class="modal fade" id="modalAjout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Ajouter un plat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="" enctype="multipart/form-data">
         <label for="intitule"> Intitulé : </label><input type="text" name="intitule" required><br>
         <label for="ingredient"> Ingredient : </label><input type="text" name="ingredient" required><br>
         <label for="prix"> Prix : </label><input type="number" min="0" name="prix" required><br>
         <label for="img"> Image : </label><input type="file" name="img"><br>
         <label for="select"> Catégorie : </label><select type="select" class='form-select form-select-lg mb-3' aria-label='.form-select-lg example' name="categorie" required>
         	<?php
         	foreach ($mes_categories as $categorie) {
         		echo "<option value=".$categorie['id_categorie']." selected>".$categorie['nom']."</option>";
         	}
         	 ?>
         </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <input type="submit" class="btn btn-primary" name="ajout"></input>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ajout sauce modal -->

<div class="modal fade" id="modalAjoutSauce" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Ajouter une sauce</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="" enctype="multipart/form-data">
         <label for="intitule"> Intitulé : </label><input type="text" name="intitule" required><br>
         <label for="status"> Status : </label><select  type="select" name="status">
         	<option value="0" selected>Disponible</option>
         	<option value="1">Indisponible</option>
         </select><br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <input type="submit" class="btn btn-primary" name="ajoutSauce"></input>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ajout Boisson modal -->

<div class="modal fade" id="modalAjoutBoisson" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Ajouter une boisson</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="" enctype="multipart/form-data">
         <label for="intitule"> Intitulé : </label><input type="text" name="intitule" required><br>
         <label for="status"> Status : </label><select  type="select" name="status">
         	<option value="0" selected>Disponible</option>
         	<option value="1">Indisponible</option>
         </select><br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <input type="submit" class="btn btn-primary" name="ajoutBoisson"></input>
        </form>
      </div>
    </div>
  </div>
</div>

		<!-- edition modal -->

<div class="modal fade" id="modalEdition" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Modifier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="editModal">
    </div>
  </div>
 </div>
</div>


			<!-- delete modal -->

<div class="modal fade" id="modalSuppression" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Suppression</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="deleteModal">
      </div>
  	</div>
  </div>
</div>


			<!-- detail modal -->
			
<div class="modal fade" id="modalDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalDetails">
      </div>
    </div>
  </div>
</div>
		<!-- lien bootstrap modal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
<script type="text/javascript">
	
	function editPlat(evt){
		// Création d'une promesse qui renvoie un verdict qui est le formulaire pour éditer le plat entrée en paramètre
		var promesse= new Promise((resolve, reject) =>{
			url="update_tuple.php?editPlat="+evt;
			const xhr = new XMLHttpRequest();
			xhr.open("GET",url);
			xhr.onload = () => resolve(xhr.responseText);
			xhr.onerror = () => reject(xhr.statusText);
			xhr.send(null);
		});
		promesse
		.then((verdict) => {document.getElementById("editModal").innerHTML = verdict; console.log("ca marche")})
		.catch((verdict) => {console.log(verdict)})

}

	function editSauce(evt){
		// Création d'une promesse qui renvoie un verdict qui est le formulaire pour éditer la sauce entrée en paramètre
		var promesse= new Promise((resolve, reject) =>{
			url="update_tuple.php?editSauce="+evt;
			const xhr = new XMLHttpRequest();
			xhr.open("GET",url);
			xhr.onload = () => resolve(xhr.responseText);
			xhr.onerror = () => reject(xhr.statusText);
			xhr.send(null);
		});
		promesse
		.then((verdict) => {document.getElementById("editModal").innerHTML = verdict; console.log(verdict)})
		.catch((verdict) => {console.log(verdict)})

}

	function editBoisson(evt){
		// Création d'une promesse qui renvoie un verdict qui est le formulaire pour éditer la boisson entrée en paramètre
		var promesse= new Promise((resolve, reject) =>{
			url="update_tuple.php?editBoisson="+evt;
			const xhr = new XMLHttpRequest();
			xhr.open("GET",url);
			xhr.onload = () => resolve(xhr.responseText);
			xhr.onerror = () => reject(xhr.statusText);
			xhr.send(null);
		});
		promesse
		.then((verdict) => {document.getElementById("editModal").innerHTML = verdict; console.log(verdict)})
		.catch((verdict) => {console.log(verdict)})

}
	function deletePlat(evt){
		// Création d'une promesse qui renvoie un verdict qui est le formulaire pour supprimer le plat entrée en paramètre
		var promesse= new Promise((resolve, reject) =>{
			url="update_tuple.php?deletePlat="+evt;
			const xhr = new XMLHttpRequest();
			xhr.open("GET",url);
			xhr.onload = () => resolve(xhr.responseText);
			xhr.onerror = () => reject(xhr.statusText);
			xhr.send(null);
		});
		promesse
		.then((verdict) => {document.getElementById("deleteModal").innerHTML = verdict; console.log("ca marche")})
		.catch((verdict) => {console.log(verdict)})

}
	function deleteVisitor(evt){
		// Création d'une promesse qui renvoie un verdict qui est le formulaire pour supprimer le visiteur entrée en paramètre
		var promesse= new Promise((resolve, reject) =>{
			url="update_tuple.php?deleteVisitor="+evt;
			const xhr = new XMLHttpRequest();
			xhr.open("GET",url);
			xhr.onload = () => resolve(xhr.responseText);
			xhr.onerror = () => reject(xhr.statusText);
			xhr.send(null);
		});
		promesse
		.then((verdict) => {document.getElementById("deleteModal").innerHTML = verdict; console.log("ca marche")})
		.catch((verdict) => {console.log(verdict)})

}

	function details(evt){
		// Création d'une promesse qui renvoie un verdict qui est le tableau des différent menus de la commande entrée en paramètre
		var promesse= new Promise((resolve, reject) =>{
			url="update_tuple.php?details="+evt;
			const xhr = new XMLHttpRequest();
			xhr.open("GET",url);
			xhr.onload = () => resolve(xhr.responseText);
			xhr.onerror = () => reject(xhr.statusText);
			xhr.send(null);
		});
		promesse
		.then((verdict) => {document.getElementById("modalDetails").innerHTML = verdict; console.log("ca marche")})
		.catch((verdict) => {console.log(verdict)})

}


	function afficheAvis(evt){
		// Création d'une promesse qui renvoie un verdict qui est le formulaire pour modifié l'avis entrée en paramètre
		var promesse= new Promise((resolve, reject) =>{
			url="update_tuple.php?afficheAvis="+evt;
			const xhr = new XMLHttpRequest();
			xhr.open("GET",url);
			xhr.onload = () => resolve(xhr.responseText);
			xhr.onerror = () => reject(xhr.statusText);
			xhr.send(null);
		});
		promesse
		.then((verdict) => {document.getElementById("editModal").innerHTML = verdict; console.log("ca marche")})
		.catch((verdict) => {console.log(verdict)})

}

	function supprimeAvis(evt){
		// Création d'une promesse qui renvoie un verdict qui est le formulaire pour supprimer l'avis entrée en paramètre
		var promesse= new Promise((resolve, reject) =>{
			url="update_tuple.php?supprimeAvis="+evt;
			const xhr = new XMLHttpRequest();
			xhr.open("GET",url);
			xhr.onload = () => resolve(xhr.responseText);
			xhr.onerror = () => reject(xhr.statusText);
			xhr.send(null);
		});
		promesse
		.then((verdict) => {document.getElementById("deleteModal").innerHTML = verdict; console.log("ca marche")})
		.catch((verdict) => {console.log(verdict)})

}

		function deleteReserv(evt){
		// Création d'une promesse qui renvoie un verdict qui est le formulaire pour supprimer la réservation entrée en paramètre
		var promesse= new Promise((resolve, reject) =>{
			url="update_tuple.php?deleteReserv="+evt;
			const xhr = new XMLHttpRequest();
			xhr.open("GET",url);
			xhr.onload = () => resolve(xhr.responseText);
			xhr.onerror = () => reject(xhr.statusText);
			xhr.send(null);
		});
		promesse
		.then((verdict) => {document.getElementById("deleteModal").innerHTML = verdict; console.log("ca marche")})
		.catch((verdict) => {console.log(verdict)})

}

		function deleteSauce(evt){
		// Création d'une promesse qui renvoie un verdict qui est le formulaire pour supprimer la sauce entrée en paramètre
		var promesse= new Promise((resolve, reject) =>{
			url="update_tuple.php?deleteSauce="+evt;
			const xhr = new XMLHttpRequest();
			xhr.open("GET",url);
			xhr.onload = () => resolve(xhr.responseText);
			xhr.onerror = () => reject(xhr.statusText);
			xhr.send(null);
		});
		promesse
		.then((verdict) => {document.getElementById("deleteModal").innerHTML = verdict; console.log("ca marche")})
		.catch((verdict) => {console.log(verdict)})

}

		function deleteBoisson(evt){
		// Création d'une promesse qui renvoie un verdict qui est le formulaire pour supprimer la boisson entrée en paramètre
		var promesse= new Promise((resolve, reject) =>{
			url="update_tuple.php?deleteBoisson="+evt;
			const xhr = new XMLHttpRequest();
			xhr.open("GET",url);
			xhr.onload = () => resolve(xhr.responseText);
			xhr.onerror = () => reject(xhr.statusText);
			xhr.send(null);
		});
		promesse
		.then((verdict) => {document.getElementById("deleteModal").innerHTML = verdict; console.log("ca marche")})
		.catch((verdict) => {console.log(verdict)})

}

</script>
</body>
</html>