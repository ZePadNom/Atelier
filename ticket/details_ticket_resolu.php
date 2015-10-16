<?php
/**
 * Détails d'un ticket résolu
 *
 * @author Emilie Graton (V. 1 - 2012)
 * @author Anthony Lozano (2015)
 *
 * @version  2.0.2
 */



/*
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
include_once "../struct/session.php";
include_once "../php/t_connex_bd.php";
include_once "../php/get_icon_importance.php";
include_once "../php/listes_formulaire.php";
include_once "../php/snackbar.php";
include_once '../php/detail_back.php';
include_once "commentaire/commentaire.php";
include_once "evolution/evolution.php";
include_once "supprimer.php";
include_once "get_ticket.php";
include_once "get_details.php";

// Récupération de l'identifiant de l'utilisateur connecté

$session_id = $_SESSION['usr_connected']['id'];

/*
 * "IF" Au cas où l'url à été "trafiquée"
 * --------------------------------------
 */
if (isset($_GET['ticketid'])) {

	$id_ticket = $_GET['ticketid'];

	$action = isset($_POST['edition']) ? "edition" : "resoudre";

	$details = getDetails($action, $id_ticket, $bdd);

} else {

	header('Location: lister_ticket_resolu.php');
	exit();

}


// Si l'utilisateur a envoyer le formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$session_id = $_SESSION['usr_connected']['id'];

	/*
	 * Cloture
	 * -------
	 */
	if (isset($_POST['cloturer'])) {

		$sql = "UPDATE `TICKET`
				SET `NUM_STATUT` = '5'
				WHERE `ID` = $id_ticket;";
		
		try {
			$bdd->exec($sql);
		} catch(PDOException $pdoe) {
			$pdo_erreur = TRUE;
		}


		// Ajout de l'evolution
		// --------------------

		newEvo($id_ticket, $session_id, 5, $bdd);

		// Message d'info & redirection vers la liste
		// ------------------------------------------
	
		$snackbar = $pdo_erreur ? "Désolé mais une erreur est apparue".'<br><span>'.$pdoe->getMessage().'</span>'
								: 'Le ticket à été correctement cloturer, il est désormais dans "Historisé".';

		$_SESSION['msg'] = $snackbar;

		header('Location: lister_ticket_resolu.php');
		exit();

	}

	/*
	 * Refus
	 * -----
	 */
	if (isset($_POST['refuser'])) {

		$sql = "UPDATE `TICKET`
				SET `NUM_STATUT` = '2'
				WHERE `ID` = $id_ticket;";
		
		
		try {
			$bdd->exec($sql);
		} catch(PDOException $pdoe) {
			$pdo_erreur = TRUE;
		}

		// Ajout de l'evolution
		// --------------------

		newEvo($id_ticket, $session_id, 6, $bdd);

		// Message d'info & redirection vers la liste
		// ------------------------------------------

		$snackbar = $pdo_erreur ? "Désolé mais une erreur est apparue".'<br><span>'.$pdoe->getMessage().'</span>'
								: 'Le ticket à été correctement refuser, il a été remis dans "En cours".';

		$_SESSION['msg'] = $snackbar;

		header('Location: lister_ticket_resolu.php');
		exit();

		}

} else {


}


?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
	<?php

	// Inclusion des éléments placé dans la balise <head>
	include_once "../struct/head.php";
	$titre_page = "Détails d'un ticket résolu". $title;
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

			<h1>Détail du ticket résolu</h1>
			<?php echo getBack("Retour à la liste des tickets en cours", "lister_ticket_resolu.php"); ?>
			<p>Sur cette page vous pouvez valider le ticket pour le <b>cloturer et le mettre dans l'historique</b>, ou le <b>refuser</b>. Dans ce dernier cas il sera automatiquement remis dans "<b>En cours</b>".</p>
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


			/*
			 * Suppression
			 */
			
			echo getSupprimer($session_id);


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
