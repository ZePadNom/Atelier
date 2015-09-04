<?php
/**
 * Liste des tickets à attribuer les tickets
 *
 * @author Emilie Graton (V. 1 - 2012)
 * @author Anthony Lozano (2015)
 *
 * @version  2.2.0
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

	$action = isset($_POST['edition']) ? "edition" : "attribuer";

	$details = getDetails($action, $id_ticket, $bdd);

} else {

	header('Location: lister_ticket_attribuer.php');

}

// Si l'utilisateur a envoyer le formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if(isset($_POST['attribuer'])) {

		$importance = $_POST['importance'];
		$lieu = $_POST['lieu'];
		$categorie = $_POST['categorie'];
		$agent = $_POST['agent'];

		if (!($importance == 0 || $agent == 0) && !isset($_POST['edition'])) {

		$sql = "UPDATE `atelier`.`ticket`\n"
			 . "SET `ID_RESPONSABLE` = '$agent',\n\t"
			 . "`NUM_CATEGORIE` = '$categorie',\n\t"
			 . "`NUM_LIEU` = '$lieu',\n\t"
			 . "`NUM_IMPORTANCE` = '$importance',\n\t"
			 . "`NUM_STATUT` = '2'\n"
			 . "WHERE `ID` = $id_ticket;";

		$bdd->exec($sql);

		// Selection de l'id pour créer l'identifiant relatif sur evolution
		// ----------------------------------------------------------------

		$sql = "SELECT MAX(ID) + 1 AS `ID_MAX`\n"
			 . "FROM `evolution`\n"
			 . "WHERE `ID_TICKET` = $id_ticket";

		$res = $bdd->query($sql);
		$table = $res->fetch(PDO::FETCH_ASSOC);

		if ($table['ID_MAX'] == NULL)
			$id_evo = 1;
		else
			$id_evo = $table['ID_MAX'];


		// Insertion de l'evolution
		// -----------------------

		$sql = "INSERT INTO `evolution`(`ID_TICKET`, `ID`, `ID_UTILISATEUR`, `NUM_ACTION`, `CHAMP`, `H_EVO`, `D_EVO`)\n"
			 . "VALUES ($id_ticket, $id_evo, $session_id, 1, NULL, CURTIME(), CURDATE())";

		$bdd->exec($sql);

		$msg = 'Le ticket à été correctement attribuer, il est désormais "En cours".';

		$_SESSION['msg'] = $msg;

		header('Location: lister_ticket_attribuer.php');
		exit();

		unset($_POST);
		
		} elseif (!isset($_POST['edition'])) {
			$snackbar = "Votre ticket n'a pas été attribuer puisque il manque l'agent et/ou l'importance";
		}
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

			<h1>Détail du ticket à attribuer</h1>
			<?php echo getBack("Retour à la liste des à attribuer", "lister_ticket_attribuer.php"); ?>
			<p>Sur cette page vous devez attribuer un <b>agent responsable</b> au ticket et lui définir une <b>importance</b>, un <b>lieu</b>, et une <b>catégorie</b>, si ce n'est pas déjà fait. Essayer de remplir correctement le ticket, si aucune valeur ne correspond dans les listes, laissez "<b>Aucun</b>".</p>
			<p>Vous devez <strong>obligatoirement attribué un responsable et définir l'importance</strong>, une fois cela fais, le ticket passera dans "<b>En Cours</b>".</p>
			<p>Cliquez sur le bouton "<b>Modifier le ticket</b>" le ticket pour entrer en mode <b>édition</b>, et ainsi modifier <b>tout et n'importe quoi</b>. Seulement faites attention <b>chacune de vos actions sera enregistré</b>.</p>
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
