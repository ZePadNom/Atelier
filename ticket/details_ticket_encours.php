<?php
/**
 * Liste de tout les tickets
 *
 * @author Emilie Graton (V. 1 - 2012)
 * @author Anthony Lozano (2015)
 *
 * @version  2.3.0
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

	$action = isset($_POST['edition']) ? "edition" : "encours";

	$details = getDetails($action, $id_ticket, $bdd);

} else {

	header('Location: lister_ticket_encours.php');
	$_SESSION['msg'] = 'Vous ne pouvez pas accèder aux détails du ticket comme cela, choisissez un ticket en cliquant sur l\'icône dans la colonne "détails".';
	exit();

}


// Si l'utilisateur a envoyer le formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$session_id = $_SESSION['usr_connected']['id'];

	/*
	 *  Resoudre 
	 */ 
	if (isset($_POST['resoudre'])) {

		// MAJ du ticket
		// -------------

		$sql = "UPDATE `TICKET`
				SET `NUM_STATUT` = '4'
				WHERE `ID` = $id_ticket;";
		
		try {
			$bdd->exec($sql);
		} catch(PDOException $pdoe) {
			$_SESSION['msg'] = "Erreur interne:"
							 . '<span>'
							 . $pdoe->getMessage()
							 . '</span>'; 
		}

		// Ajout de l'evolution
		// --------------------

		newEvo($id_ticket, $session_id, 2, $bdd);

		// Message d'info & redirection vers la liste
		// ------------------------------------------

		$msg = 'Le ticket à été correctement résolu, il est désormais dans "Résolus".';

		$_SESSION['msg'] = $msg;

					 echo $sql;


		header('Location: lister_ticket_encours.php');
		exit();

	}

	/*
	 * Attente
	 * -------
	 */
	if (isset($_POST['attente'])) {

		$sql = "UPDATE `TICKET`
				SET `NUM_STATUT` = '3'
				WHERE `ID` = $id_ticket;";
		
		
		try {
			$bdd->exec($sql);
		} catch(PDOException $pdoe) {
			$_SESSION['msg'] = "Erreur interne:"
							 . '<span>'
							 . $pdoe->getMessage()
							 . '</span>'; 
		}


		// Ajout de l'evolution
		// --------------------

		newEvo($id_ticket, $session_id, 3, $bdd);

		// Message d'info & redirection vers la liste
		// ------------------------------------------

		$msg = 'Le ticket à été correctement mis en attente, il est toujours dans "En cours".';

		$_SESSION['msg'] = $msg;

		header('Location: lister_ticket_encours.php');
		exit();

		} /*elseif ($num_statut == '3') {
		$snackbar = 'Ce ticket est déjà "En attente" ...'; 
	}*/
} else {


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

			<h1>Détail du ticket en cours</h1>
			<?php echo getBack("Retour à la liste des tickets en cours", "lister_ticket_encours.php"); ?>
			<p>Sur cette page vous pouvez mettre le statut du ticket sur résolu, ou en attente en cliquant sur les boutons correspondant</p>
			<p>Cliquez sur le bouton "<b>Modifier le ticket</b>" pour entrer en mode <b>édition</b>, et ainsi modifier <b>tout et n'importe quoi</b>. Seulement faites attention <b>chacune de vos actions sera enregistré</b>.</p>
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
