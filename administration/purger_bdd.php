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
							<input type="number" min="1" max="11" name="duree1" id="duree1" value="<?= isset($_POST["duree1"]) ? $_POST["duree1"] : "2" ?>">
							<select name="duree2" id="duree2">
								<?php $value = isset($_POST["duree2"]) ?  $_POST["duree2"] : "mois" ?>
								<option value="mois" <?= $value=="mois" ? "selected": "" ?> >mois</option>
								<option value="ans" <?= $value=="ans" ? "selected": "" ?> >ans</option>
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