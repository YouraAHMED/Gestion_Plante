<?php
require_once("includes/Lib.php");
require_once("includes/Plante.php");
$action = key_exists('action', $_GET)? trim($_GET['action']): null;
//$sauvegarde = key_exists('sauvegarde', $_GET)? trim($_GET['sauvegarde']): null;

$erreur=array('nom'=>null,"categorie"=>null,"prix"=>null,"dateP"=>null);
$idP = null;$nom = null;$categorie = null;$prix = null;$dateP = null;
$tab_plante=array();
$zonePrincipale = "";
$titre = "Accueil";


switch ($action) {
	case "liste":
		$titre = "Liste des plantes";

		// Connexion à la base de données
		$connexion = connecter();

		// Calcul du nombre total de résultats
		$requete = "SELECT COUNT(*) as nb_plantes FROM plante";
		$query = $connexion->query($requete);
		$nb_plantes = $query->fetchColumn();

		// Calcul du nombre total de pages
		$results_per_page = 10;
		// ceil() arrondit au nombre supérieur
		$total_pages = ceil($nb_plantes / $results_per_page);

		// Récupération du numéro de page spécifié dans l'URL
		$numPage = 1;
		if(key_exists('numPage', $_GET)){
			// Vérification que c'est un entier
			$numPage = trim($_GET['numPage']);
			// Vérification que c'est un entier positif
			if(!preg_match("/^[0-9]+$/", $numPage)){
				$numPage = 1;
			}
			// Vérification que la page demandée existe
			if($numPage > $total_pages){
				$numPage = $total_pages;
			}
		}
		// Récupération de l'attribut tri spécifié dans l'URL
		$tri = "idP";
		if(key_exists('tri', $_GET)){
			$tri = trim($_GET['tri']);
			// Vérification que l'attribut tri est valide
			if(!in_array($tri, array("idP", "nom", "categorie"))){
				$tri = "idP";
			}
		}

		// Récupération de l'ordre de tri spécifié dans l'URL
		$ordre = "ASC";
		if(key_exists('ordre', $_GET)){
			$ordre = trim($_GET['ordre']);
			// Vérification que l'ordre de tri est valide
			if(!in_array($ordre, array("ASC", "DESC"))){
				$ordre = "ASC";
			}
		}

		// Récupération des resultats pour la page demandée et le tri demandé
		$offset = ($numPage - 1) * $results_per_page;

		// Vérifie que $tri est une colonne valide pour le tri
		$tri_options = array('nom', 'categorie');
		if (!in_array($tri, $tri_options)) {
			$tri = 'idP';
		}

		// Vérifie que $ordre est une option de tri valide
		$ordre_options = array('ASC', 'DESC');
		if (!in_array($ordre, $ordre_options)) {
			$ordre = 'ASC';
		}

		// Prépare la requête SQL avec des paramètres pour le tri et l'offset
		$requete = "SELECT * FROM plante ORDER BY $tri $ordre LIMIT :limit OFFSET :offset";
		$stmt = $connexion->prepare($requete);
		$stmt->bindValue(':limit', $results_per_page, PDO::PARAM_INT);
		$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
		$stmt->execute();

		// Récupère les résultats de la requête
		$results = $stmt->fetchAll(PDO::FETCH_OBJ);

		//formulaire pour la selection du tri
		$corps = "<form action='index.php' method='get'>";
		$corps.= "<input type='hidden' name='action' value='liste'>";
		$corps.= "<label for='tri'>Trier par : </label>";
		$corps.= "<select name='tri' id='tri'>";
		$corps.= "<option value='nom' ".($tri == "nom"? "selected": "").">Nom</option>";
		$corps.= "<option value='categorie' ".($tri == "categorie"? "selected": "").">Categorie</option>";
		$corps.= "</select>";
		$corps.= "<label for='ordre'>Ordre : </label>";
		$corps.= "<select name='ordre' id='ordre'>";
		$corps.= "<option value='ASC' ".($ordre == "ASC"? "selected": "").">Croissant</option>";
		$corps.= "<option value='DESC' ".($ordre == "DESC"? "selected": "").">Décroissant</option>";
		$corps.= "</select>";
		$corps.= "<input type='submit' value='Trier' class='action'>";
		$corps.= "</form>";

		// Affiche les résultats de la requête
		$corps.="<h4><span class='c1'>Nom</span> <span class='c1'>Categorie</span> <span class='c1'>Action</span></h4>";
		foreach($results as $row){
			$idP = $row->idP;
			$nom = $row->nom;
			$categorie =$row->categorie;
			$corps.= "<span class='c1'>". htmlspecialchars($row->nom)."</span> <span class='c1'>".htmlspecialchars($row->categorie)."</span>"; //htmlspecialchars pour éviter les failles XSS (Cross Site Scripting)
			$corps.= "<span class='c1'><a href='index.php?action=select&idP=$idP'><span class='glyphicon glyphicon-eye-open'></span></a>  ";
			$corps.= "<a href='index.php?action=update&idP=$idP'><span class='glyphicon glyphicon-pencil'></span></a>  ";
			$corps.= "<a href='index.php?action=delete&idP=$idP'><span class='glyphicon glyphicon-trash'></span></a></span>";
			$corps.= "<br>";
			$corps.= "<div style='margin-bottom: 10px;'></div>";
		}

		for($i=1; $i<=$total_pages; $i++){
		if($i==$numPage){
		$corps.="<input type='submit' value='$i' onclick='window.location.href=\"?action=liste&numPage=".$i."&tri=".$tri."&ordre=".$ordre."\"' class='active'>";
		}else{
		$corps.="<input type='submit' value='$i' onclick='window.location.href=\"?action=liste&numPage=".$i."&tri=".$tri."&ordre=".$ordre."\"'>";
		}
		}
		$zonePrincipale = $corps;
		$connexion = null;
		break;
	case "select":
		$titre = "Sélection d'une plante";
		$idP = key_exists('idP', $_GET)? trim($_GET['idP']): null;
		$connect = connecter();
		$requete = "SELECT * FROM plante WHERE idP = :idP";
		$stmp = $connect->prepare($requete);
		$stmp->execute(array(':idP'=>$idP));
		$resultat = $stmp->fetchAll();
		$plante = new Plante($idP,$resultat[0]['nom'],$resultat[0]['categorie'],$resultat[0]['prix'],$resultat[0]['dateP']);
		$corps = "<h3>vous avez choisi la plante : " . $plante->getNom() . "</h3><br>";
		$corps .= "<h3>la categorie de la plante est : " . $plante->getCategorie() . "</h3><br>";
		$corps .= "<h3>le prix de la plante est : " . $plante->getPrix() . "</h3><br>";
		$corps .= "<h3>la date d'ajout de la plante est : " . $plante->getDateP() . "</h3><br>";
		//ajouter un bouton retour qui permet de revenir à la liste des plantes
		$corps .= "<input type='submit' value='Retour' onclick='window.location.href=\"?action=liste\"' class='action'>";
		$zonePrincipale = $corps;
		$connect = null;
		break;

	case "insert":
		$titre = "Ajout d'une plante";
		$cible = 'insert';
		if(!key_exists('nom' , $_POST) && !key_exists('categorie' , $_POST) && !key_exists('prix' , $_POST) && !key_exists('dateP' , $_POST)){
			include("includes/formulairePlante.php");
		}
		else{
			$nom = key_exists('nom' , $_POST)? trim($_POST['nom']): null;
			$categorie = key_exists('categorie' , $_POST)? trim($_POST['categorie']): null;
			$prix = key_exists('prix' , $_POST)? trim($_POST['prix']): null;
			$dateP = key_exists('dateP' , $_POST)? trim($_POST['dateP']): null;
			if($nom === "") $erreur['nom'] = "Le nom est obligatoire";
			if($categorie === "") $erreur['categorie'] = "La categorie est obligatoire";
			if($prix === "") $erreur['prix'] = "Le prix est obligatoire";
			if($dateP === "") $erreur['dateP'] = "La date est obligatoire";
			$compteur_erreur = count($erreur);
			foreach ($erreur as $key => $value) {
				if($value == "") $compteur_erreur--;
			}
			if($compteur_erreur == 0){
				$connect = connecter();
				//si la date est au format jj/mm/aaaa on la transforme en aaaa-mm-jj
				if(controlerDate($dateP)){

					$dateP = date("Y-m-d", strtotime($dateP));
				}
				//verifier si la date n'est pas une date future
				if($dateP > date("Y-m-d")){
					$erreur['dateP'] = "La date n'est pas valide";
					$compteur_erreur++;
				}
				$requete = 'INSERT INTO `plante` (nom,categorie,prix,dateP) VALUES (:nom,:categorie,:prix,:dateP)';
				$stmp = $connect->prepare($requete);
				$stmp->execute(array('nom'=>$nom,'categorie'=>$categorie,'prix'=>$prix,'dateP'=>$dateP));
				$stmp->fetchAll();
				$connect = null;
				header("Location: index.php?action=liste");
			}
			else{
				include("includes/formulairePlante.php");
			}
		}
		break;
	case "update":
		$titre = "Modification d'une plante";
		$cible = 'update';
		$idP = key_exists('idP', $_GET)? trim($_GET['idP']): null;
		$nom = null; $categorie = null; $prix = null; $dateP = null;
		if(!isset($_POST["nom"]) && !isset($_POST["categorie"]) && !isset($_POST["prix"]) && !isset($_POST["dateP"])){
			$connect = connecter();
			$requete = "SELECT * FROM plante WHERE idP = :idP";
			$stmp = $connect->prepare($requete);
			$stmp->execute(array(':idP'=>$idP));
			$resultat = $stmp->fetchAll();
			$nom = $resultat[0]['nom']; $categorie = $resultat[0]['categorie']; $prix = $resultat[0]['prix']; $dateP = $resultat[0]['dateP'];
			include("includes/formulaireModification.php");
			$connect = null;
		}
		else{
			$nom = key_exists('nom' , $_POST)? trim($_POST['nom']): null;
			$categorie = key_exists('categorie' , $_POST)? trim($_POST['categorie']): null;
			$prix = key_exists('prix' , $_POST)? trim($_POST['prix']): null;
			$dateP = key_exists('dateP' , $_POST)? trim($_POST['dateP']): null;
			if($nom === "") $erreur["nom"] = "Il manque le nom";
			if($categorie === "") $erreur["categorie"] = "Il manque la categorie";
			if($prix === "") $erreur["prix"] = "Il manque le prix";
			if($dateP === "") $erreur["dateP"] = "Il manque la date";

			$compteur_erreur = count($erreur);
			foreach ($erreur as $key => $value) {
				if($value == "") $compteur_erreur--;
			}
			if($compteur_erreur == 0){
				$connect = connecter();
				//si la date est au format jj/mm/aaaa on la transforme en aaaa-mm-jj
				if(controlerDate($dateP)){
					$dateP = date("Y-m-d", strtotime($dateP));
				}
				$requete = 'UPDATE `plante` SET nom = :nom, categorie = :categorie, prix = :prix, dateP = :dateP WHERE idP = :idP';
				$stmp = $connect->prepare($requete);
				$stmp->execute(array(':idP'=>$idP,':nom'=>$nom,':categorie'=>$categorie,':prix'=>$prix,':dateP'=>$dateP));
				$stmp->fetchAll();
				header("Location: index.php?action=select&idP=".$idP);

				$connect = null;

			}
			else{

				include("includes/formulairePlante.php");
			}
		}
		break;
	case "delete":
		$titre = "Suppression d'une plante";
		$idP = key_exists('idP', $_GET)? trim($_GET['idP']): null;
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			$connect = connecter();
			$requete = 'DELETE FROM `plante` WHERE idP = :idP';
			$stmp = $connect->prepare($requete);
			$stmp->execute(array(':idP'=>$idP));
			$resultat = $stmp->fetchAll();
			$connect = null;
			// Redirection vers la liste des plantes
			header("Location: index.php?action=liste");
		}
		else{
			$connect = connecter();
			$requete = "SELECT * FROM plante WHERE idP = :idP";
			$stmp = $connect->prepare($requete);
			$stmp->execute(array(':idP'=>$idP));
			$resultat = $stmp->fetchAll();
			$plante = new Plante($idP,$resultat[0]['nom'],$resultat[0]['categorie'],$resultat[0]['prix'],$resultat[0]['dateP']);
			$corps = "<h3>Suppression de la plante</h3>";
			$corps .= "<h3>".$plante."</h3>";
			$corps .= "<form method='post' action='index.php?action=delete&idP=".$idP."'>";
			$corps .= "<input type='submit' value='Supprimer' class='btn-supprimer'>";
			//ajouter un bouton annuler qui renvoie vers la liste des plantes
			$corps .= "<input type='button' value='Annuler' onclick='location.href=\"index.php?action=liste\"' class='action'>";
			$corps .= "</form>";
			$zonePrincipale = $corps;
			$connect = null;
		}
		break;

	case "search":
		$titre = "Recherche d'une plante";
		$cible = 'search';
		// verification existant d'une donnée valide pour la recherche
		$recherche = key_exists('recherche', $_POST)? trim($_POST['recherche']): null;
		$resultat = [];
		// si la donnée est valide(non nulle ou non vide)
		if($recherche != null && $recherche!= ""){
			$connect = connecter();
			$requete = "SELECT * FROM plante WHERE nom LIKE :q OR categorie LIKE :q";
			$stmt = $connect->prepare($requete);
			$stmt->execute(array(':q'=>"%".$recherche."%")); //
			$resultat = $stmt->fetchAll();
			$connect = null;
		}

		$corps = "<h3>Résultat de la recherche</h3>";
		$corps .= "<table>";
		$corps .= "<tr><th>idP</th><th>nom</th><th>categorie</th><th>prix</th><th>dateP</th><th>Modifier</th><th>Supprimer</th></tr>";
		foreach ($resultat as $key => $value) {
			$corps .= "<tr>";
			$corps .= "<td>".$value['idP']."</td>";
			$corps .= "<td>".$value['nom']."</td>";
			$corps .= "<td>".$value['categorie']."</td>";
			$corps .= "<td>".$value['prix']."</td>";
			$corps .= "<td>".$value['dateP']."</td>";
			$corps .= "<td><a href='index.php?action=update&idP=".$value['idP']."'>Modifier</a></td>";
			$corps .= "<td><a href='index.php?action=delete&idP=".$value['idP']."'>Supprimer</a></td>";
			$corps .= "</tr>";
		}
		$corps .= "</table>";
		$corps .= "<input type='button' value='Retour' onclick='location.href=\"index.php?action=liste\"' class='action'>";
		$zonePrincipale = $corps;

		break;

	case "apropos":
		$titre = "À propos";
		include("includes/aPropos.php");
		break;


	default:
		$titre = "Accueil";
		$corps = "<h3>Bienvenue sur le site de gestion des plantes</h3>";
		$corps .= "<h3>Vous pouvez gérer les plantes en cliquant sur les liens suivants :</h3>";
		$corps .= "<p>";
		$corps .= "<a href='index.php?action=liste'>Liste des plantes</a> ";
		$corps .= "<a href='index.php?action=insert'>Ajouter une plante</a> ";
		$corps .= "<a href='index.php?action=search'>Rechercher une plante</a>";
		$corps .= "</p>";
		$zonePrincipale = $corps;
		break;
}
include("includes/squelette.php");

?>

