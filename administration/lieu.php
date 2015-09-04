<?php
/**
 * Gestion des lieux
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

$lieu = 0;
$lieu_mod = isset($_GET['categ_mod']) ? $_GET['categ_mod'] : 0; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$pdo_erreur = FALSE;

	/*
	 * Nouveau lieu
	 */
	if(isset($_POST['nv_lieu'])) {

		// init
		// ----
		
		$lieu_err = FALSE;

		/*
		 * Lieu (liste)
		 * ------------
		 * ~ Requis
		 */
		if (empty($_POST["nom_lieu"])) {
			$lieu_err = TRUE;
		} else {
			$lieu = test_input($_POST["nom_lieu"]);
		}

		/*
		 * S'il n'y a pas d'erreur, ajout du lieu dans la base
		 * Sinon, message d'erreur
		 */
		if (!$lieu_err) {

			$sql = "INSERT INTO `LIEU`(`NOM`)\n"
				 . "VALUES (\"$lieu\");";

			try {
				$bdd->exec($sql);
			} catch(PDOException $pdoe) {
				$pdo_erreur = TRUE;
			}

			$snackbar = $pdo_erreur ? "Désolé mais une erreur est apparue, impossible d'ajouté le lieu".'<br><span>'.$pdoe->getMessage().'</span>'
									: "La lieu \"$lieu\" a correctement été ajouté !";

		} else {

			$snackbar = "La lieu que vous avez rentré est incorrect.";

		}

	}

	/*
	 * Supprimé lieu
	 */
	if(isset($_POST['sup_lieu'])) {

		$lieu = $_POST['lieu'];

			$sql = "DELETE FROM `LIEU`\n"
				 . "WHERE `NUM` = $lieu;";

			try {
				$bdd->exec($sql);
			} catch(PDOException $pdoe) {
				$pdo_erreur = TRUE;
			}

			$snackbar = $pdo_erreur ? "Désolé mais une erreur est apparue, impossible d'ajouté le lieu".'<br>'.'<br><span>'.$pdoe->getMessage().'</span>'
									: "La lieu a correctement été supprimé&nbsp;!";

	}

	/*
	 * Supprimé lieu
	 */
	if(isset($_POST['mod_lieu'])) {

		$categ = $_POST['categ'];

		foreach ($categ as $key => $row) {

			$sql = "UPDATE `LIEU`\n"
				 . "SET `NOM` = '$row'\n"
				 . "WHERE `NUM` = $key;";

			try {
				$bdd->exec($sql);
			} catch(PDOException $pdoe) {
				$pdo_erreur = TRUE;
			}

		}

		$snackbar = $pdo_erreur ? "Désolé mais une erreur est apparue, impossible de modifier les lieux".'<br>'.'<br><span>'.$pdoe->getMessage().'</span>'
								: "Les lieux ont correctement été modifiée&nbsp;!";

	}

}

/**
 * Permet de créer une liste HTML (élément <select/>) contenant les colonnes de la table Lieu
 * @param  int $num Permet de déterminé l'option de la liste pré-selectionné
 * @param  PDO $pdo Instance de l'objet PDO permettant la connexion à la base de données
 * @return string Retourne les éléments HTML
 */
function listerLieu($num, PDO $pdo) {

	$sql = "SELECT `NUM`, `NOM` FROM `LIEU` ORDER BY `NOM` ASC;";
	$res = $pdo->query($sql);
	$table = $res->fetchAll(PDO::FETCH_ASSOC);

	$liste = '<select name="lieu" required>'."\n";
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
 * Liste lieu
 * ----------
 */
function listerTabLieu(PDO $pdo) {


	$sql = "SELECT `NUM`, `NOM`\n"
		 . "FROM `LIEU`\n"
		 . "ORDER BY `NOM` ASC";

	$res = $pdo->query($sql);
	$table = $res->fetchAll(PDO::FETCH_ASSOC);

	$liste_lieu = '<table>'
				. '<tr>'
				. '<th>Nom</th>'
				. '</tr>';

	foreach ($table as $row) {

		if ($row['NUM'] != 1) {
			$liste_lieu .= '<tr>'
							  . '<td>'.$row['NOM'].'</td>'
							  . '</tr>';
		}

	}

	$liste_lieu .= '</table>';

	return $liste_lieu;

}


/* 
 * Liste lieu
 * ----------
 */
function listerModLieu(PDO $pdo) {


	$sql = "SELECT `NUM`, `NOM`\n"
		 . "FROM `LIEU`\n"
		 . "ORDER BY `NOM` ASC";

	$res = $pdo->query($sql);
	$table = $res->fetchAll(PDO::FETCH_ASSOC);

	$liste_lieu = '<table>'
				. '<tr>'
				. '<th>Nom</th>'
				. '</tr>';

	foreach ($table as $row) {

		if ($row['NUM'] != 1) {
			$liste_lieu .= '<tr>'
							  . '<td>'.'<input type="text" name="categ['.$row['NUM'].']" value="'.$row['NOM'].'">'.'</td>'
							  . '</tr>';
		}

	}

	$liste_lieu .= '</table>';

	return $liste_lieu;

}

?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
	<?php

	// Inclusion des éléments placé dans la balise <head>
	include_once("../struct/head.php");
	$titre_page = "Gestion des lieux". $title;
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
		<h1>Gestion des lieux</h1>
		<p>Sur cette page vous pouvez ajouter, consulter, supprimer et modifier les lieux.</p>
			<div class="container">
				<div class="sep li">
					<h2>Liste des lieux</h2>
					<?php

					echo listerTabLieu($bdd);

					?>
				</div>
			</div>
			<div class="container">
				<div class="sep aj">
					<h2>Ajouter une lieu</h2>
					<p>Ajoutez une lieu en remplissant le formulaire ci-dessous.</p>
					<form method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
						<div class="pleine_largeur">
							<label for="nom_lieu">Nom du lieu</label>
							<input type="text" id="nom_lieu" name="nom_lieu" required placeholder="Nom du lieu ...">
						</div><!--
					 --><div class="boutons">
							<input class="button" type="submit" name="nv_lieu" value="Ajouter le lieu">
						</div>
					</form>
				</div>
			</div>
			<div class="container">
				<div class="sep sup">
					<h2>Supprimer un lieu</h2>
					<p>Supprimez un lieu en le selectionnant dans la liste ci-dessous.</p>
					<form method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
						<div class="pleine_largeur">
							<?php
							echo listerLieu($lieu, $bdd);
							?>
						</div><!--
					 --><div class="boutons">
							<input class="button" type="submit" name="sup_lieu" value="Supprimer le lieu">
						</div>
					</form>
				</div>
			<div class="container">
				<div class="sep mod">
					<h2>Modifier les lieux</h2>
					<form method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
					<?php

					echo listerModLieu($bdd);
					
					?>
					<div class="boutons">
							<input class="button" type="submit" name="mod_lieu" value="Modifier la/les lieu(s)">
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