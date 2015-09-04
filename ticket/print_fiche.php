<?php
/**
 * Page d'accueil
 *
 * @author Anthony Lozano (2015)
 *
 * @version  1.0.1
 */



/*
 * Inclusion(s)
 * ------------
 * ~ Session
 * ~ Connexion à la base
 * ~ Retour (navigation)
 */
include_once $_SESSION['HTML_PATH'] . "struct/session.php";
include_once $_SESSION['HTML_PATH'] . "php/t_connex_bd.php";
include_once $_SESSION['HTML_PATH'] . "php/detail_back.php";
include_once $_SESSION['HTML_PATH'] . "get_ticket.php";

$liste = "Oups ! c'est vide";

if (isset($_POST)) {
	
	$liste = "";

	foreach ($_POST as $rowpost) {

		$table = getTicket($rowpost, $bdd);

		foreach ($table as $row) {
			$liste .= '<table id="table_print">'
					. '<tr>'
					. '<th colspan="2">'."Titre".'</th><th>'."Importance".'</th>'
					. '</tr>'
					. '<td colspan="2">'.$row['TITRE'].'</td>'.'<td>'.$row['NOM_IMPORTANCE'].'</td>'
					. '</tr><tr>'
					. '<th colspan="3">'."Description".'</th>'
					. '</tr><tr>'
					. '<td colspan="3">'.$row['DESCRIPTION'].'</td>'
					. '</tr><tr>'
					. '<th>'."Date ouverture".'</th><th>'."Lieu".'</th>'.'<th>'."Catégorie".'</th>'
					. '</tr><tr>'
					. '<td>'.$row['D_OUVERTURE'].'</td><td>'.$row['NOM_LIEU'].'</td><td>'.$row['NOM_CATEGORIE'].'</td>'
					. '</tr>';

			
			$liste .= "</table>";
		}

		$liste .= '<hr>';

	}

	// $liste .= "<hr>";

}

?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
	<?php

	// Inclusion des éléments placé dans la balise <head>
	include_once("../struct/head.php");
	$titre_page = "Gestion des fiches avant impression". $title;
	echo '<title>'.$titre_page.'</title>';

	?>
	<link rel="stylesheet" href="../css/detail_ticket.css">
</head>
<body>
	<div id="page">
		<?php

		// Inclusion de l'en-tête
		include_once "../struct/header.php";

		?>

		<section>
			<h1>Fiche de tickets</h1>
			<?php echo getBack("Retour à la liste des tickets en cours", "lister_ticket_encours.php"); ?>
			<div class="container">
				<?php 
				echo $liste;
				?>
				<br>
				<button type="button" class="button" onclick="window.print()">Imprimer la page</button>
				
			</div>
		</section>

		<?php

		// Inclusion du pied de page (footer)
		include_once "../struct/footer.php";

		?>

	</div>
</body>
</html>