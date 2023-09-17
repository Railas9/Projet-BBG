<?php
include"connexion_pdo/pdo.php";
$pdo = connexpdo("bbg");

// formulaire edition plat
	if(isset($_GET['editPlat'])){

		$editPlat = $_GET['editPlat'];

		// Sélection des plats où l'id plat est égal à l'id entrée en paramètre + jointure pour récupérer le nom de sa catégorie

		$selecPlat = $pdo->query("Select * from plat as p
						 JOIN categorie as c 
						 on p.`id_categorie`=c.id_categorie 
						 where p.id_plat='".$editPlat."'")->fetch(PDO::FETCH_ASSOC);

		$mes_categories = $pdo->query("Select * from categorie")->fetchAll(PDO::FETCH_ASSOC);
		echo 
		"<form method='POST' action=''>
			<input type='hidden' value='".$selecPlat['id_plat']."' name='id_plat'>
			<label for='intitule'>Intitule :</label><input type='text' value='".$selecPlat['intitule']."' name='intitule'><br>
			<label for='prix'>Prix :</label><input type='number' min='0' value='".$selecPlat['prix']."' name='prix'><br>
			<label for='ingredient'>Ingredient :</label><input type='text' value='".$selecPlat['ingredient']."' name='ingredient'><br>
			<label for='categorie'>Categorie :</label>
			<select type='select' class='form-select form-select-lg mb-3' aria-label='.form-select-lg example' name='categorie'>
			<option value='".$selecPlat['id_categorie']."' selected>".$selecPlat['nom']."</option>";
			// option par défaut étant la valeur actuelle
         	foreach ($mes_categories as $categorie) {
         		if($selecPlat['id_categorie'] != $categorie['id_categorie']){
         			// affiche le reste des options qui n'est pas égal à l'id catégorie actuelle
         		echo "<option value=".$categorie['id_categorie']." >".$categorie['nom']."</option>";
         		}
         	}
			echo 
			"</select>
			<div class='modal-footer'>
        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Fermer</button>
        <input type='submit' class='btn btn-primary' name='editPlat'></input>
        </form>
      </div>";

	}

// formulaire edition sauce
	else if(isset($_GET['editSauce'])){
		$editSauce = $_GET['editSauce'];
		// Sélection des sauces où l'id sauce est égal à l'id entrée en paramètre
		$selecSauce = $pdo->query("Select * from sauce where id_sauce='".$editSauce."'")->fetch(PDO::FETCH_ASSOC);
		echo 
		"<form method='POST' action=''>
			<input type='hidden' value='".$selecSauce['id_sauce']."' name='id_sauce'>
			<label for='status'>Status :</label>
			<select type='select' class='form-select form-select-lg mb-3' aria-label='.form-select-lg example' name='status'>
			<option value='0'>Disponible</option>
			<option value='1'>Indisponible</option>
			</select>
			<div class='modal-footer'>
        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Fermer</button>
        <input type='submit' class='btn btn-primary' name='editSauce'></input>
        </form>
      </div>";
	}
// formulaire edition boisson
	else if(isset($_GET['editBoisson'])){
		$editBoisson = $_GET['editBoisson'];
		// Sélection des boissons où l'id boisson est égal à l'id entrée en paramètre
		$selecBoisson = $pdo->query("Select * from boisson where id_boisson='".$editBoisson."'")->fetch(PDO::FETCH_ASSOC);
		echo 
		"<form method='POST' action=''>
			<input type='hidden' value='".$selecBoisson['id_boisson']."' name='id_boisson'>
			<label for='status'>Status :</label>
			<select type='select' class='form-select form-select-lg mb-3' aria-label='.form-select-lg example' name='status'>
			<option value='0'>Disponible</option>
			<option value='1'>Indisponible</option>
			<div class='modal-footer'>
        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Fermer</button>
        <input type='submit' class='btn btn-primary' name='editBoisson'></input>
        </form>
      </div>";
	}
// formulaire suppression plat
	else if(isset($_GET['deletePlat'])){
		$deletePlat = $_GET['deletePlat'];
		$selecPlat = $pdo->query("Select * from plat where id_plat='".$deletePlat."'")->fetch(PDO::FETCH_ASSOC);
		echo 
		"<form method='POST' action=''>
			<input type='hidden' value='".$selecPlat['id_plat']."' name='id_plat'></input>
			<p>Voulez-vous vraiment supprimer ce plat ?</p>
			<div class='modal-footer'>
        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Fermer</button>
        <input type='submit' class='btn btn-primary' name='deletePlat'></input>
        </form>
      </div>";
	}
// formulaire suppression visiteur
	else if(isset($_GET['deleteVisitor'])){
		$deleteVisitor = $_GET['deleteVisitor'];
		$selecVisitor = $pdo->query("Select * from visiteur where id_visiteur='".$deleteVisitor."'")->fetch(PDO::FETCH_ASSOC);
		echo 
		"<form method='POST' action=''>
			<input type='hidden' value='".$selecVisitor['id_visiteur']."' name='id_visiteur'></input>
			<p>Voulez-vous vraiment supprimer ce compte ?</p>
			<div class='modal-footer'>
        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Fermer</button>
        <input type='submit' class='btn btn-primary' name='deleteVisitor'></input>
        </form>
      </div>";
	}
// tableau details menu commande
	else if(isset($_GET['details'])){
		$details = $_GET['details'];
		$selecDetails = $pdo->query("Select * from commande_details where id_commande='".$details."'")->fetchAll(PDO::FETCH_ASSOC);
		echo " <table class='table table-dark table-hover' style='width: 90%; margin: 3em auto;'>
			<thead>
				<tr>
					<th>Id commande</th>
					<th>Id plat</th>
					<th>Id boisson</th>
					<th>Id sauce</th>
					<th>Quantité</th>
					<th>Composition</th>
					<th>Message</th>
				</tr>
			</thead>
			<tbody>";

				foreach ($selecDetails as $key) {
					echo "<tr>";
					echo "<td>".$key["id_commande"]."</td>";
					echo "<td>".$key["id_plat"]."</td>";
					echo "<td>".$key["id_boisson"]."</td>";
					echo "<td>".$key["id_sauce"]."</td>";
					echo "<td>".$key["quantite"]."</td>";
					echo "<td>".$key["composition"]."</td>";
					echo "<td>".$key["message"]."</td>";
					echo "</tr>";
				}

	echo "</tbody>
	     </table>";
	}
// formulaire affiche avis
	else if(isset($_GET['afficheAvis'])){
		$avis = $_GET['afficheAvis'];
		echo 
		"<form method='POST' action=''>
			<input type='hidden' value='".$avis."' name='id_avis'></input>
			<p>Voulez-vous vraiment afficher cet avis ?</p>
			<div class='modal-footer'>
        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Fermer</button>
        <input type='submit' class='btn btn-primary' name='afficheAvis'></input>
        </form>
      </div>";
	}
// formulaire supprime avis
	else if(isset($_GET['supprimeAvis'])){
		$avis = $_GET['supprimeAvis'];
		$selecAvis = $pdo->query("Select * from avis where id_avis='".$avis."'")->fetch(PDO::FETCH_ASSOC);
		echo 
		"<form method='POST' action=''>
			<input type='hidden' value='".$selecAvis['id_avis']."' name='id_avis'></input>
			<p>Voulez-vous vraiment supprimer cet avis ?</p>
			<div class='modal-footer'>
        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Fermer</button>
        <input type='submit' class='btn btn-primary' name='supprimeAvis'></input>
        </form>
      </div>";
	}
// formulaire supprime réservation
	else if(isset($_GET['deleteReserv'])){
		$deleteReserv = $_GET['deleteReserv'];
		echo 
		"<form method='POST' action=''>
			<input type='hidden' value='".$deleteReserv."' name='id_reservation'></input>
			<p>Voulez-vous vraiment supprimer cette réservation ?</p>
			<div class='modal-footer'>
        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Fermer</button>
        <input type='submit' class='btn btn-primary' name='deleteReserv'></input>
        </form>
      </div>";
	}
// formulaire supprime sauce
	else if(isset($_GET['deleteSauce'])){
		$deleteSauce = $_GET['deleteSauce'];
		$selecSauce = $pdo->query("Select * from sauce where id_sauce='".$deleteSauce."'")->fetch(PDO::FETCH_ASSOC);
		echo 
		"<form method='POST' action=''>
			<input type='hidden' value='".$selecSauce['id_sauce']."' name='id_sauce'></input>
			<p>Voulez-vous vraiment supprimer cette sauce ?</p>
			<div class='modal-footer'>
        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Fermer</button>
        <input type='submit' class='btn btn-primary' name='deleteSauce'></input>
        </form>
      </div>";
	}
// formulaire supprime boisson
	else if(isset($_GET['deleteBoisson'])){
		$deleteBoisson = $_GET['deleteBoisson'];
		$selecBoisson = $pdo->query("Select * from boisson where id_boisson='".$deleteBoisson."'")->fetch(PDO::FETCH_ASSOC);
		echo 
		"<form method='POST' action=''>
			<input type='hidden' value='".$selecBoisson['id_boisson']."' name='id_boisson'></input>
			<p>Voulez-vous vraiment supprimer cette boisson ?</p>
			<div class='modal-footer'>
        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Fermer</button>
        <input type='submit' class='btn btn-primary' name='deleteBoisson'></input>
        </form>
      </div>";
	}
	else{
		header("location:index.php");
	}
 ?>