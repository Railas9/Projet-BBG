<?php
	include"connexion_pdo/pdo.php";
	$pdo = connexpdo("bbg");

// retour de la promesse affichant les plats en fonction de la catégorie sélectionnée

if(isset($_GET['id_categ'])){
	$id_categ = $_GET['id_categ'];
  // Recupere les tuples ayant pour id catégorie l'id catégorie reçu via la requête $_GET
	$req = $pdo->prepare("SELECT * FROM plat where id_categorie= ? ");
  $req->execute(array($id_categ));
  $recupPlat = $req->fetchAll(PDO::FETCH_ASSOC);
	foreach ($recupPlat as $plat) {
    // Pour chaque plat s'affiche une div contenant l'image, l'intitule et le prix. La balise contient un événement "onclick" appelant la fonction detailplat() prenant en paramètre l'id du plat
		echo "<div class='choixPlat' onclick='detailPlat(".$plat['id_plat'].")' data-dismiss='modal'>
				<img src='".$plat['image']."' style='width:80%;'>
				<h3>".$plat['intitule']."</h3>
				<h5>".$plat['prix']." €</h5>
		       </div>";
	}
}
//  Retour de la promesse affichant le formulaire permettant de sélectionner les détails du menu
else if(isset($_GET['detailFormulaire'])){
	$id_plat = $_GET['detailFormulaire'];

  // Récupère le prix du plat
	$req = $pdo->prepare("SELECT prix FROM plat WHERE id_plat = ?");
  $req->execute(array($id_plat));
  $plat = $req->fetch(PDO::FETCH_ASSOC);
  //

  // Récupère les tuples de toutes les boissons ainsi que de toutes les sauces
	$req = $pdo->prepare("SELECT * FROM boisson WHERE status = ?");
  $req->execute(array(0));
  $boisson = $req->fetchAll(PDO::FETCH_ASSOC);

	$req = $pdo->prepare("SELECT * FROM sauce WHERE status = ?");
  $req->execute(array(0));
  $sauce = $req->fetchAll(PDO::FETCH_ASSOC);

  // choix des condiments + stocke le prix dans un input de type "hidden"

	echo "<form>
			<h3>Composition</h3>
			<input type='hidden' name='prix' value='".$plat['prix']."'>
			 <input type='checkbox' name='condi' value='Salade'>
  			 <label for='condi1'>Salade</label><br>
  			 <input type='checkbox' name='condi' value='Tomate'>
  			 <label for='condi2'>Tomate</label><br>
  			 <input type='checkbox' name='condi' value='Oignon'>
 			 <label for='condi3'>Oignon</label><br><br>
 			<h3>Boisson</h3>";

foreach ($boisson as $key) {

	echo "<input type='radio' name='boisson' value='".$key['id_boisson']."' checked>
 			 <label for='".$key['intitule']."'>".$key['intitule']."</label><br>";

}
	echo "<h3>Sauces</h3>";

foreach ($sauce as $key) {

  echo "<input type='radio' name='sauce' value='".$key['id_sauce']."' checked>
       <label for='".$key['intitule']."'>".$key['intitule']."</label><br>";

}
// input commentaire et quantité
 echo "<h3>Commentaire</h3>
    <textarea name='commentaire'></textarea>
    <h3>Quantité<h3>
    <input type='number' name='quantite' min='1' value='1'>";
// bouton qui appelle la fonction inforecup() aux cliques en stockant les infos du formulaire
	     echo "<div class='modal-footer'>
	     <button type='button' class='btn btn-primary' data-bs-dismiss='modal' onclick='infoRecup()'>Ajouter</button>
        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Fermer</button>
        </form>
      </div>";
  }

//  retour de la promesse stockant les détails du menu dans le panier

  else if (isset($_GET['plat']) && isset($_GET['sauce']) && isset($_GET['condiment']) && isset($_GET['message']) && isset($_GET['quantite']) && isset($_GET['boisson']) && isset($_GET['id'])) {

  // affecte l'ensemble des données envoyé en $_GET à des variables.
    $id = $_GET['id'];
  	$id_plat = $_GET['plat'];
  	$sauce = $_GET['sauce'];
  	$condiment = $_GET['condiment'];
  	$message = htmlspecialchars($_GET['message']);
  	$quantite = $_GET['quantite'];
    $boisson = $_GET['boisson'];


    // Recupére l'image, l'intitule et le prix du plat sélectionné et l'affiche

  	$plat = $pdo->query("SELECT image, intitule, prix FROM plat where id_plat ='".$id_plat."' ")->fetch(PDO::FETCH_ASSOC);

    // Stocke les détails du menu dans des balises de type hidden aussi le nom de chaque input correspondent à des tableaux associatifs permettant de trier les détails des menus en fonction de l'id
  	echo "<div class='info_detail'>
        <input type='hidden' value='".$id."' name='menu[".$id."][id]'>
  			<input type='hidden' value='".$id_plat."' name='menu[".$id."][id_plat]'>
  			<input type='hidden' value='".$sauce."' name='menu[".$id."][sauce]'>
  			<input type='hidden' value='".$message."' name='menu[".$id."][message]'>
  			<input type='hidden' value='".$quantite."' name='menu[".$id."][quantite]'>
        <input type='hidden' value='".$condiment."' name='menu[".$id."][condiment]'>
        <input type='hidden' value='".$boisson."' name='menu[".$id."][boisson]'>
  			<img src='".$plat['image']."' class='mini_photo'>
  			<h4>".$plat['intitule']."</h4>
  			<p><span id='prix_menu'>".$plat['prix']."</span> €</p>
  			<p> X <span id='quantite_menu'>".$quantite."</span></p>
  			<img src='img/cancel.svg' class='trash' onclick='annule(this)'></img>
  		</div>";
  	  }
    else{
      // si aucune de ces conditions n'est remplie l'utilisateur sera redirigé vers la page d'accueil
      header("location:index.php");
    }
?>