<?php
/**
 * Page d'accueil
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
 */
include_once $_SESSION['PHP_PATH'] . "struct/session.php";
include_once $_SESSION['PHP_PATH'] . "bdd/t_connex_bd.php";
include_once $_SESSION['PHP_PATH'] . "php/snackbar.php";
	
/*
 * Nb tickets
 */
$sql = "SELECT COUNT(*) AS `NB_TICKETS` FROM `TICKET` ;";
try {
	$res = $bdd->query($sql);
	$table = $res->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $pdoe) {
	$snackbar = "Impossible de récupérer les données ... <br><span>".$pdoe->getMessage()."</span>";
}

$nb_tickets = $table['NB_TICKETS'];

/*
 * Nb tickets attribuer
 */
$sql = "SELECT COUNT(*) AS `NB_TICKETS` FROM `TICKET` WHERE `NUM_STATUT` = ?;";
$sth = $bdd->prepare($sql);
try {
	$sth->execute(array(1));	
	$table = $sth->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $pdoe) {
	$snackbar = "Impossible de récupérer les données ... <br><span>".$pdoe->getMessage()."</span>";
}

$nb_tickets_attribuer = $table['NB_TICKETS'];

/*
 * Nb tickets en cours
 */
try {
	$sth->execute(array(2));	
	$table = $sth->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $pdoe) {
	$snackbar = "Impossible de récupérer les données ... <br><span>".$pdoe->getMessage()."</span>";
}

$nb_tickets_encours = $table['NB_TICKETS'];

/*
 * Nb tickets resolu
 */
try {
	$sth->execute(array(4));	
	$table = $sth->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $pdoe) {
	$snackbar = "Impossible de récupérer les données ... <br><span>".$pdoe->getMessage()."</span>";
}

$nb_tickets_resolu = $table['NB_TICKETS'];

/*
 * Nb tickets resolu
 */
try {
	$sth->execute(array(3));	
	$table = $sth->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $pdoe) {
	$snackbar = "Impossible de récupérer les données ... <br><span>".$pdoe->getMessage()."</span>";
}

$nb_tickets_attente = $table['NB_TICKETS'];

/*
 * Nb tickets resolu
 */
try {
	$sth->execute(array(5));
	$table = $sth->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $pdoe) {
	$snackbar = "Impossible de récupérer les données ... <br><span>".$pdoe->getMessage()."</span>";
}

$nb_tickets_cloture = $table['NB_TICKETS'];
if ($nb_tickets != 0) {
	$prc_attribuer = $nb_tickets_attribuer == 0 . "%" ? 0 : round($nb_tickets_attribuer / ($nb_tickets-$nb_tickets_cloture) * 100 ). "%";
	$prc_encours = $nb_tickets_encours == 0 . "%" ? 0 : round($nb_tickets_encours / ($nb_tickets-$nb_tickets_cloture) * 100 ). "%";
	$prc_attente = $nb_tickets_attente == 0 . "%" ? 0 : round($nb_tickets_attente / ($nb_tickets-$nb_tickets_cloture) * 100 ). "%";
	$prc_resolu = $nb_tickets_resolu == 0 . "%" ? 0 : round($nb_tickets_resolu / ($nb_tickets-$nb_tickets_cloture) * 100 ). "%";
} else {
	$prc_attribuer = $prc_encours = $prc_attente = $prc_resolu = "0%";
}

?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
	<?php

	// Inclusion des éléments placé dans la balise <head>
	include_once("struct/head.php");
	$titre_page = "Accueil". $title;
	echo '<title>'.$titre_page.'</title>';

	?>
	<link rel="stylesheet" type="text/css" href="css/accueil.css">
</head>
<body>
	<script type="text/javascript" src="js/graphique.js"></script>
	<div id="page">
		<?php

		// Inclusion de l'en-tête
		include_once "struct/header.php";

		?>
		<section id="accueil">
			<div class="container">
				<h2>Gestion de l'atelier</h2>
				<hr>
				<div>
					<p>Bienvenue, <?php echo $prenom_header, " ", $nom_header ?>.</p>
					<p>Utilisez la barre de naviguation se trouvant ci-dessus pour naviguer parmis les fonctionnalités.</p>
					<p>
						Si vous êtes un peu perdu, vous pouvez lire ce document :
						<ul>
							<li><a href="doc/doc_utilisateur.pdf" alt="Documentation d'utilisation" target="_blank">Documentation d'utilisation</a></li>
						</ul>
					</p>
					<p>Il y a au total <var><?php echo $nb_tickets; ?></var> ticket(s) enregistré(s) dans la base de données.</p>
					<ul>
						<li><var><?php echo $nb_tickets_attribuer; ?></var> à attribuer</li>
						<li><var><?php echo $nb_tickets_encours; ?></var> en cours</li>
						<li><var><?php echo $nb_tickets_attente; ?></var> en attente</li>
						<li><var><?php echo $nb_tickets_resolu; ?></var> résolu(s)</li>
						<li><var><?php echo $nb_tickets_cloture; ?></var> cloturé(s)</li>
					</ul>
					<div class="graph">
						<canvas id="canvas" width="300" height="300">
							Votre navigateur ne supporte pas canvas, pas de graphique pour vous !
						</canvas>
						<div class="legend">
							<var><?php echo $prc_attribuer ?></var><span> à attribuer</span>
							<var><?php echo $prc_encours ?></var><span> en cours</span>
							<var><?php echo $prc_attente ?></var><span> en attente</span>
							<var><?php echo $prc_resolu ?></var><span> résolu</span>
						</div>
					</div>
					<?php 

						$canvas = '<script type="text/javascript">'."\n"
								. "\t".'var canvas = document.getElementById(\'canvas\')'."\n"
								. "\t".'graph(canvas.getContext(\'2d\'), canvas.width, canvas.height, ['.$nb_tickets_attribuer.', '.$nb_tickets_encours.', '.$nb_tickets_attente.', '.$nb_tickets_resolu.']);'."\n"
								. '</script>'."\n";

						echo $canvas;

					?>
				</div>
			</div>
		</section>

		<?php

		// Inclusion du pied de page (footer)
		include_once "struct/footer.php";

		?>
	</div>
<script type="text/javascript" src="js/counter.js"></script>
</body>
</html>