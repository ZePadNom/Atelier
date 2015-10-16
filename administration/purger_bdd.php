<?php
/**
 * Purger la base de données
 *
 * @author Emilie Graton (V. 1 - 2012)
 * @author Anthony Lozano (2015)
 *
 * @version  2.0.0
 */



/*
 * Inclusion(s)
 * ------------
 * ~ Session
 * ~ Connexion à la base
 */
include_once "../struct/session.php";
include_once "../php/t_connex_bd.php";

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
</head>
<body>
	<div id="page">
		<?php

		// Inclusion de l'en-tête
		include_once "../struct/header.php";

		?>

		<section>
		<h1>Purger les données de la base</h1>
		<p>Cette page supprime les tickets placés dans l'historique qui ont été validé depuis <b>plus de 2 ans</b>.</p>
			<div class="container">
				<p>Désolé mais les fonctionnalité(s) de cette page n'ont pas été implémentée(s).</p>
			</div>
		</section>

		<?php

		// Inclusion du pied de page (footer)
		include_once "../struct/footer.php";

		?>

	</div>
</body>
</html>