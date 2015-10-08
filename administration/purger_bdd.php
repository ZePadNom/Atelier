<?php
/**
 * Purger la base de données
 *
 * @author Emilie Graton (V. 1 - 2012)
 * @author Anthony Lozano (2015)
 *
 * @version  2.0.0
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

//Si l'utilisateur a envoyer le formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	// Initialisation des variables d'erreur	
	$chiffre_err = $date_err = FALSE;

	/*
	Verification que l'element envoyer est bien numérique
	*/
	if (!is_int($_POST['duree1'])){
		$chiffre_err = TRUE;
	} else {
		$chiffre = $_POST['duree1'];
	}

	/*
	Verification que l'element envoyer est bien en mois ou en année
	*/

	if ($_POST['duree2'] != 'month' || $_POST['duree2'] != 'year'){
		$date_err = TRUE;
	} else {
		$date = $_POST['duree2'];
	}

	/*
	 * Si le chiffre & l'année ou le mois sont correct, on execute
	 */
	if (!($chiffre_err && $date_err)) {
		/*
		* Si il s'agit d'une année selectionner on entre dans le if
		* Sinon on execute le else pour les mois
		*/
		if ($_POST['duree2'] == "year") {
			/*
			* Requete de recuperation en fonction de l'année
			*/
			$annee = date(Y)- $_POST['duree2'];
			$sql = "SELECT `ID`, `ID_RESPONSABLE`, `NUM_CATEGORIE`, `NUM_LIEU`, `NUM_STATUT`, `ID_CREATEUR`, `NUM_IMPORTANCE`, `TITRE`, `DESCRIPTION`, `D_OUVERTURE`, `H_OUVERTURE`, `D_CLOTURE` \n"
			 		."FROM `ticket` \n"
			 		."WHERE Year(`D_CLOTURE`) NOT BETWEEN Year(`D_CLOTURE`) AND ".$annee."";

			$res = $bdd->query($sql);
			$table = $res->fetch(PDO::FETCH_ASSOC);	
						
		} else {
			/*
			* Requete de recuperation en fonction du mois
			*/
			$mois = date(m)- $_POST['duree2'];
			$sql = "SELECT `ID`, `ID_RESPONSABLE`, `NUM_CATEGORIE`, `NUM_LIEU`, `NUM_STATUT`, `ID_CREATEUR`, `NUM_IMPORTANCE`, `TITRE`, `DESCRIPTION`, `D_OUVERTURE`, `H_OUVERTURE`, `D_CLOTURE` \n"
			 		."FROM `ticket` \n"
			 		."WHERE month(`D_CLOTURE`) NOT BETWEEN month(`D_CLOTURE`) AND ".$mois."";

			$res = $bdd->query($sql);
			$table = $res->fetch(PDO::FETCH_ASSOC); 		
		}

		
		
	}

}	

?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
	<?php

	// Inclusion des éléments placé dans la balise <head>
	include_once("../struct/head.php");
	$titre_page = "Purger la base de données". $title;
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
		<h1>Purger les données de la base</h1>
		<p>Cette page supprime les tickets placés dans l'historique qui ont été validé depuis une durée choisie.</p>
			<div class="container">
				<div class="sep li">
					<h2>Purger les tickets</h2>
					<form method="Post">
						<p>
							Sélectionner les tickets validés depuis 
							<input type="number" min="1" max="12" name="duree1" id="duree1" value="<?= isset($_POST["duree1"]) ? $_POST["duree1"] : "2" ?>">
							<select name="duree2" id="duree2">
								<?php $value = isset($_POST["duree2"]) ?  $_POST["duree2"] : "mois" ?>
								<option value="month" <?= $value=="mois" ? "selected": "" ?> >mois</option>
								<option value="year" <?= $value=="ans" ? "selected": "" ?> >ans</option>
							</select>
						</p>
						<input class="button" type="submit" value="Purger">
					</form>
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