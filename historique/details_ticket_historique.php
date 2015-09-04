<?php
/**
 * Détails des tickets cloturés 
 * @author Emilie Graton (V. 1 - 2012)
 * @author Anthony Lozano (2015)
 *
 * @version  2.0.0
 */

session_start();

/**
 * Inclusion(s)
 * ------------
 * ~ Session
 * ~ Connexion à la base
 * ~ Fonction pour obtenir une icone pour l'attribut importance
 * ~ Fonctions permettant de créer les listes HTML
 * ~ Snack bar (message d'info)
 * ~ Retour vers la page précédente
 * ~ Module commentaire
 * ~ Module evolution
 * ~ Module suprresiont
 * ~ Accesseur ticket
 * ~ Accesseur détails
 */
include_once $_SESSION['PHP_PATH'] . "struct/session.php";
include_once $_SESSION['PHP_PATH'] . "bdd/t_connex_bd.php";
include_once $_SESSION['PHP_PATH'] . "php/get_icon_importance.php";
include_once $_SESSION['PHP_PATH'] . "php/listes_formulaire.php";
include_once $_SESSION['PHP_PATH'] . "php/snackbar.php";
include_once $_SESSION['PHP_PATH'] . 'php/detail_back.php';
include_once $_SESSION['PHP_PATH'] . "ticket/commentaire/commentaire.php";
include_once $_SESSION['PHP_PATH'] . "ticket/evolution/evolution.php";
include_once $_SESSION['PHP_PATH'] . "ticket/supprimer.php";
include_once $_SESSION['PHP_PATH'] . "ticket/get_ticket.php";
include_once $_SESSION['PHP_PATH'] . "ticket/get_details.php";

// Récupération de l'identifiant de l'utilisateur connecté

$session_id = $_SESSION['usr_connected']['id'];

/*
 * "IF" Au cas où l'url à été "trafiquée"
 * --------------------------------------
 */
if (isset($_GET['ticketid'])) {

	$id_ticket = $_GET['ticketid'];
	$details = getDetails("historique", $_GET['ticketid'], $bdd);

} else {

	header('Location: lister_ticket_historique.php');

}

?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
	<?php

	// Inclusion des éléments placé dans la balise <head>
	include_once "../struct/head.php";
	$titre_page = "Détails d'un ticket". $title;
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

			<h1>Détail du ticket dans l'historique</h1>
			<?php
			echo getBack("Retour à la liste des tickets cloturés", "lister_ticket_historique.php");
			?>
			<div class="container">

			<?php

			/*
			 * Détails du ticket
			 */

			echo $details;

			/*
			 * Commentaires
			 */
	
			echo getCommentaire($id_ticket, $bdd);

			/*
			 * Evolutions
			 */
			
			echo getEvolution($id_ticket, $bdd);


			?>
			</div>
		</section>
		<?php

		// Inclusion du pied de page (footer)
		include_once "../struct/footer.php";

		?>

	</div>
</body>
</html>
