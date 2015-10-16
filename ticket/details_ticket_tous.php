<?php
/**
 * Liste de tout les tickets
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

/*
 * Init
 * ----
 */
$id = $d_ouverture = $d_resolution = $d_validation = $titre = $description = $note = $lieu = $h_ouverture = $h_resolution = $h_validation = $d_modif = $h_modif = $statut = $categorie = $importance = $id_responsable = "";
// Récupération de l'identifiant de l'utilisateur connecté

$session_id = $_SESSION['usr_connected']['id'];

/*
 * "IF" Au cas où l'url à été "trafiquée"
 * --------------------------------------
 */
if (isset($_GET['ticketid'])) {

	$id_ticket = $_GET['ticketid'];

	$action = isset($_POST['edition']) ? "edition" : "tous";

	$details = getDetails($action, $id_ticket, $bdd);

} else {

	header('Location: lister_ticket_tous.php');
	$_SESSION['msg'] = 'Vous ne pouvez pas accèder aux détails du ticket comme cela, choisissez un ticket en cliquant sur l\'icône dans la colonne "détails".';
	exit();

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

			<h1>Détail du ticket</h1>
			<?php echo getBack("Retour à la liste de tous les tickets", "lister_ticket_tous.php"); ?>
			<p>Sur cette page vous ne pouvez faire aucune action en particulier ormis consulter. Vous pouvez toujours ajouter un commentaire ou modifier le ticket si vos droits vous le permettent.</p>
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
			
			// echo getSupprimer($session_id);


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
