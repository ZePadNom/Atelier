<?php
/**
 * Liste des tickets à attribuer
 *
 * @author Anthony Lozano (2015)
 *
 * @version  1.0.0
 */


/*
 * Inclusion(s)
 * ------------
 * ~ Session
 * ~ Connexion à la base
 * ~ Module de tri
 * ~ Snack bar (message d'info)
 */
include_once "../struct/session.php";
include_once "../php/t_connex_bd.php";
include_once "../php/classer_tickets.php";
include_once "../php/snackbar.php";

if ($_SESSION['usr_connected']['classe'] == 3){

	$_SESSION['msg'] = "Vous n'avez pas l'autorisation d'accèder à cette page.";
	header('Location: ../accueil.php');
	exit();

}


$_SESSION['action'] = 'attribuer';
$action = $_SESSION['action'];


$get['regroup'] = isset($_GET['regroup']) ? $_GET['regroup'] : 'd_ouverture';


$order_by = array('D_OUVERTURE', 'NUM_IMPORTANCE');
$ordre = "ASC";
$liste = grouperTicket(strtoupper($get['regroup']), $order_by, $ordre, $action, $bdd);

?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
	<?php

	// Inclusion des éléments placé dans la balise <head>
	include_once "../struct/head.php";
	$titre_page = "Tickets à attibuer". $title;
	echo '<title>'.$titre_page.'</title>';

	?>
	<link rel="stylesheet" media="screen" type="text/css" href="../css/lister_ticket.css">
</head>
<body>
	<div id="page">
		<?php

		// Inclusion de l'en-tête
		include_once "../struct/header.php";

		?>
		<section>
			<h1>Liste des tickets à atribuer</h1>
			<p>Voici la liste des tickets à attribuer, pour les mettre en cours, aller sur leurs fiche de détails et renseigner y les informations nécéssaires à sa réalisation.</p>
			<?php echo getFormulaire($action, $get); ?>
			<div class="container">
			</div>
				<?php echo $liste; ?>
		</section>
		<nav id="totop">
			<a href="#page" title="Retour vers le haut de la page"></a>
		</nav>
		<?php

		// Inclusion du pied de page (footer)
		include_once "../struct/footer.php";

		?>
	</div>
	<script type="text/javascript" src="../js/trier.js"></script>
	<script type="text/javascript" src="../js/description.js"></script>
</body>
</html>
