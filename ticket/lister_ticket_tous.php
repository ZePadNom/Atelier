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
 * ~ Module de tri
 */
include_once "../struct/session.php";
include_once "../php/t_connex_bd.php";
include_once "../php/classer_tickets.php";


$_SESSION['action'] = 'tous';
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
	$titre_page = "Tous les tickets". $title;
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
			<h1>Liste de tout les tickets</h1>
			<?php echo getFormulaire($action, $get); ?>
			<form action="print_fiche.php" method="POST">
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
