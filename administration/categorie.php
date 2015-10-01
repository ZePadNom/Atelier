<?php
/**
 * Gestion des catégories
 *
 * @author Anthony Lozano (2015)
 *
 * @version  1.1.1
 */

session_start();

/*
 * Inclusion(s)
 * ------------
 * ~ Session
 * ~ Connexion à la base
 * ~ Fonctions permettant de créer les listes HTML
 * ~ Snack bar (message d'info)
 * ~ Fonction test_input($data)
 */
include_once $_SESSION['PHP_PATH'] . "struct/session.php";
include_once $_SESSION['PHP_PATH'] . "bdd/t_connex_bd.php";
include_once $_SESSION['PHP_PATH'] . "php/snackbar.php";
include_once $_SESSION['PHP_PATH'] . "php/test_input.php";
// include_once "../php/listes_formulaire.php";

$categorie = 0;
$categorie_mod = isset($_GET['categ_mod']) ? $_GET['categ_mod'] : 0; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$pdo_erreur = FALSE;

	/*
	 * Nouveau categorie
	 */
	if(isset($_POST['nv_categorie'])) {

		// init
		// ----
		
		$categorie_err = FALSE;

		/*
		 * Catégorie (liste)
		 * ------------
		 * ~ Requis
		 */
		if (empty($_POST["nom_categorie"])) {
			$categorie_err = TRUE;
		} else {
			$categorie = test_input($_POST["nom_categorie"]);
		}

		/*
		 * S'il n'y a pas d'erreur, ajout du categorie dans la base
		 * Sinon, message d'erreur
		 */
		if (!$categorie_err) {

			$sql = "INSERT INTO `CATEGORIE`(`NOM`)\n"
				 . "VALUES (\"$categorie\");";

			try {
				$bdd->exec($sql);
			} catch(PDOException $pdoe) {
				$pdo_erreur = TRUE;
			}

			$snackbar = $pdo_erreur ? "Désolé mais une erreur est apparue, impossible d'ajouter la catégorie".'<br><span>'.$pdoe->getMessage().'</span>'
									: "La catégorie \"$categorie\" a correctement été ajouté !";

		} else {

			$snackbar = "La catégorie que vous avez rentré est incorrect.";

		}

	}

	/*
	 * Supprimé categorie
	 */
	if(isset($_POST['sup_categorie'])) {

		$categorie = $_POST['categorie'];

			$sql = "DELETE FROM `CATEGORIE`\n"
				 . "WHERE `NUM` = $categorie;";

			try {
				$bdd->exec($sql);
			} catch(PDOException $pdoe) {
				$pdo_erreur = TRUE;
			}

			$snackbar = $pdo_erreur ? "Désolé mais une erreur est apparue, impossible d'ajouté le categorie".'<br>'.'<br><span>'.$pdoe->getMessage().'</span>'
									: "La catégorie a correctement été supprimé&nbsp;!";

	}

	/*
	 * Supprimé categorie
	 */
	if(isset($_POST['mod_categorie'])) {

		$categ = $_POST['categ'];

		foreach ($categ as $key => $row) {

			$sql = "UPDATE `CATEGORIE`\n"
				 . "SET `NOM` = '$row'\n"
				 . "WHERE `NUM` = $key;";

			try {
				$bdd->exec($sql);
			} catch(PDOException $pdoe) {
				$pdo_erreur = TRUE;
			}

		}

		$snackbar = $pdo_erreur ? "Désolé mais une erreur est apparue. Impossible de modifier les catégories".'<br>'.'<br><span>'.$pdoe->getMessage().'</span>'
								: "Les catégories ont correctement été modifiées !";

	}

}

/**
 * Permet de créer une liste HTML (élément <select/>) contenant les colonnes de la table Catégorie
 * @param  int $num Permet de déterminé l'option de la liste pré-selectionné
 * @param  PDO $pdo Instance de l'objet PDO permettant la connexion à la base de données
 * @return string Retourne les éléments HTML
 */
function listerCategorie($num, PDO $pdo) {

	$sql = "SELECT `NUM`, `NOM` FROM `CATEGORIE` ORDER BY `NOM` ASC;";
	$res = $pdo->query($sql);
	$table = $res->fetchAll(PDO::FETCH_ASSOC);

	$liste = '<select class="lg" name="categorie" required>'."\n";
	foreach ($table as $row){

		$selected = "";

		if ($row['NUM'] == $num)
			$selected = 'selected';

		if ($row['NUM'] != 1) {
			$liste .= '<option value="'.$row['NUM'].'" '.$selected.'>'
					. $row['NOM']
					. '</option>'."\n";
		}
	}
	$liste .= '</select>';
	return $liste;
}


/* 
 * Liste categorie
 * ----------
 */
function listerTabCateg(PDO $pdo) {


	$sql = "SELECT `NUM`, `NOM`\n"
		 . "FROM `CATEGORIE`\n"
		 . "ORDER BY `NOM` ASC";

	$res = $pdo->query($sql);
	$table = $res->fetchAll(PDO::FETCH_ASSOC);

	$liste_categorie = '<table>'
				. '<tr>'
				. '<th>Nom</th>'
				. '</tr>';

	foreach ($table as $row) {

		if ($row['NUM'] != 1) {
			$liste_categorie .= '<tr>'
							  . '<td>'.$row['NOM'].'</td>'
							  . '</tr>';
		}

	}

	$liste_categorie .= '</table>';

	return $liste_categorie;

}


/* 
 * Liste categorie
 * ----------
 */
function listerModCateg(PDO $pdo) {


	$sql = "SELECT `NUM`, `NOM`\n"
		 . "FROM `CATEGORIE`\n"
		 . "ORDER BY `NOM` ASC";

	$res = $pdo->query($sql);
	$table = $res->fetchAll(PDO::FETCH_ASSOC);

	$liste_categorie = '<table>'
				. '<tr>'
				. '<th>Nom</th>'
				. '</tr>';

	foreach ($table as $row) {

		if ($row['NUM'] != 1) {
			$liste_categorie .= '<tr>'
							  . '<td>'.'<input type="text" name="categ['.$row['NUM'].']" value="'.$row['NOM'].'">'.'</td>'
							  . '</tr>';
		}

	}

	$liste_categorie .= '</table>';

	return $liste_categorie;

}

?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
	<?php

	// Inclusion des éléments placé dans la balise <head>
	include_once("../struct/head.php");
	$titre_page = "Gestion des catégories". $title;
	echo '<title>'.$titre_page.'</title>';

	?>
	<link rel="stylesheet" type="text/css" href="../css/admin.css">
</head>
<body>
	<div id="page">
		<?php

		// Inclusion de l'en-tête
		include_once "../struct/header.php";

		?>

		<section>
		<h1>Gestion des catégories</h1>
		<p>Sur cette page vous pouvez ajouter, consulter, supprimer et modifier les catégories.</p>
			<div class="container">
				<div class="sep li">
					<h2>Liste des catégories</h2>
					<?php

					echo listerTabCateg($bdd);

					?>
				</div>
			</div>
			<div class="container">
				<div class="sep aj">
					<h2>Ajouter une catégorie</h2>
					<p>Ajoutez une catégorie en remplissant le formulaire ci-dessous.</p>
					<form method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
						<div class="pleine_largeur">
							<label for="nom_categorie">Nom de la catégorie</label>
							<input type="text" id="nom_categorie" name="nom_categorie" required placeholder="Nom du categorie ...">
						</div><!--
					 --><div class="boutons">
							<input class="button" type="submit" name="nv_categorie" value="Ajouter la catégorie">
						</div>
					</form>
				</div>
			</div>
			<div class="container">
				<div class="sep sup">
					<h2>Supprimer une catégorie</h2>
					<p>Supprimez une catégorie en la selectionnant dans la liste ci-dessous.</p>
					<form method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
						<div class="pleine_largeur">
							<?php
							echo listerCategorie($categorie, $bdd);
							?>
						</div><!--
					 --><div class="boutons">
							<input class="button" type="submit" name="sup_categorie" value="Supprimer la catégorie">
						</div>
					</form>
				</div>
			<div class="container">
				<div class="sep mod">
					<h2>Modifier les catégories</h2>
					<form method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
					<?php

					echo listerModCateg($bdd);
					
					?>
					<div class="boutons">
							<input class="button" type="submit" name="mod_categorie" value="Modifier la/les catégorie(s)">
							<input class="button" type="reset" value="Annuler les modifications">
					</div>
					</form>
				</div>
			</div>
				</div>
		</section>

		<?php

		// Inclusion du pied de page (footer)
		include_once "../struct/footer.php";

		?>

	</div>
</body>
</html>