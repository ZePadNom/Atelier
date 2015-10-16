<?php
/**
 * Liste des tickets en cours
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

$_SESSION['action'] = 'encours';
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
	$titre_page = "Tickets en cours". $title;
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
			<h1>Liste des tickets en cours</h1>
			<?php echo getFormulaire($action, $get); ?>
			<div class="container">
				<form action="print_fiche.php" method="POST">
			</div>
				<?php echo $liste; ?>
				<nav id="print">
					<input type="submit">
				</nav>
				</form>
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
