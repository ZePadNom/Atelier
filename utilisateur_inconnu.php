<?php
/**
 * Page de redirection de l'utilisateur si sa session n'est plus active
 *
 * @author Anthony Lozano (2015)
 *
 * @version  1.0.0
 */

header("Refresh: 7; URL=index.php");

?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
	<?php

	// Inclusion des éléments placé dans la balise <head>
	include_once("struct/head.php");
	$titre_page = "Utilisateur inconnu". $title;
	echo '<title>'.$titre_page.'</title>';

	?>
</head>
<body>
	<div id="page">
		<?php

		// Inclusion de l'en-tête
		include_once "struct/header.html";

		?>

		<section class="inconnu">
			<div class="container">
				<h2>Désolé</h2>
				<p>Mais il semble que vous n'êtes pas authentifié, ou que votre session à expirée<small> (brigand).</small></p>
				<p>Vous allez être redirigé vers la page de connexion dans quelque seconde, ou cliquez sur le lien ci-dessous</p>
				<img src="images/ajax-loader.gif" alt="Patientez ...">
				<a href="index.php" title="Connexion">Page de connexion</a>
			</div>
		</section>

		<?php

		// Inclusion du pied de page (footer)
		include_once "struct/footer.php";

		?>

	</div>
</body>
</html>