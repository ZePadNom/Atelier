<?php
/**
 * Liste des tickets résolus
 *
 * @author Anthony Lozano (2015)
 *
 * @version  1.0.0
 */

session_start();

/*
 * Inclusion(s)
 * ------------
 * ~ Session
 * ~ Connexion à la base
 * ~ Module de tri
 * ~ Snack bar (message d'info)
 */
include_once $_SESSION['PHP_PATH'] . "struct/session.php";
include_once $_SESSION['PHP_PATH'] . "bdd/t_connex_bd.php";
include_once $_SESSION['PHP_PATH'] . "php/classer_tickets.php";
include_once $_SESSION['PHP_PATH'] . "php/snackbar.php";


$_SESSION['action'] = 'resolu';
$action = $_SESSION['action'];


$get['regroup'] = isset($_GET['regroup']) ? $_GET['regroup'] : 'd_ouverture';


$order_by = array('D_OUVERTURE', 'NUM_IMPORTANCE');
$ordre = "ASC";
$liste = grouperTicket(strtoupper($get['regroup']), $order_by, $ordre, $action, $bdd);


if (isset($_SESSION['msg'])) {
	$snackbar = $_SESSION['msg'];
	unset($_SESSION['msg']);
}

?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
	<?php

	// Inclusion des éléments placé dans la balise <head>
	include_once "../struct/head.php";
	$titre_page = "Tickets résolus". $title;
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
			<h1>Liste des tickets résolus</h1>
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
	<script type="text/javascript" src="../js/description.js"></script>
</body>
</html>
