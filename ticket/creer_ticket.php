<?php
/**
 * Formulaire de création d'un ticket
 *
 * @author Emilie Graton (V. 1 - 2012)
 * @author Anthony Lozano (2015)
 *
 * @version  2.0.3
 */

session_start();

/*
 * Inclusion(s)
 * ------------
 * ~ Fonction test_input($data){...}
 * ~ Session
 * ~ Connexion à la base (instance objet PDO)
 * ~ Fonctions permettant de créer les listes HTML
 * ~ Snackbar
 * ~ Module evolution
 */ 
include_once $_SESSION['PHP_PATH'] . "php/test_input.php";
include_once $_SESSION['PHP_PATH'] . "struct/session.php";
include_once $_SESSION['PHP_PATH'] . "bdd/t_connex_bd.php";
include_once $_SESSION['PHP_PATH'] . "php/listes_formulaire.php";
include_once $_SESSION['PHP_PATH'] . "php/snackbar.php";
include_once $_SESSION['PHP_PATH'] . "ticket/evolution/evolution.php";


// Si l'utilisateur a envoyer le formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	// Initialisation des variables d'erreur	
	$titre_err = $description_err = $categorie_err = $importance_err = $lieu_err = $agent_err = FALSE;


	/*
	 * Titre
	 * -----------
	 * ~ Requis
	 * ~ Pas de condition de validation particulière 
	 */
	if (empty($_POST["titre"])) {
		$titre_err = TRUE;
	} else {
		$titre = test_input($_POST["titre"]);
	}

	/*
	 * Description
	 * -----------
	 * ~ Requis
	 * ~ Pas de condition de validation particulière 
	 */
	if (empty($_POST["description"])) {
		$description_err = TRUE;
	} else {
		$description = test_input($_POST["description"]);
	}

	/*
	 * Catégorie (liste)
	 * ------------
	 * ~ Facultatif
	 */
	if (($_POST["categorie"] == 1)) {
		$categorie_err = TRUE;
	}
	
	$categorie = $_POST["categorie"];

	/*
	 * Attribué à (liste)
	 * ------------------
	 * ~ Facultatif
	 */
	if (($_POST["agent"] == 0)) {
		$agent_err = TRUE;
	}

	$agent = $_POST["agent"];

	/*
	 * Importance (liste)
	 * ------------------
	 * ~ Facultatif
	 */
	if (($_POST["importance"] == 1)) {
		$importance_err = TRUE;
	}
	
	$importance = $_POST["importance"];
	
	/*
	 * Lieu (liste)
	 * ------------
	 * ~ Requis
	 */
	if (($_POST["lieu"] == 1)) {
		$lieu_err = TRUE;
	}

	$lieu = $_POST["lieu"];
	
	/*
	 * Si titre & description correct, on envoie le formulaire
	 */
	if (!($titre_err && $description_err)) {
		
		/*
		 * 2 cas possible
		 * - Soit tout le formulaire est remplis, le ticket vas directement dans "en cours" et on ajoute l'evolution "attribuer" pour ce ticket et cet utilisateur
		 * - Soit le formualire est incomplet avec au minimum titre et description, le ticket vas dans "à attribuer"
		 */

		if (!($importance_err || $agent_err || $lieu_err || $categorie_err)) {
			
			/*
			 * Cas 1
			 * -----
			 * Insertion du ticket
			 * Selection du ticket
			 * Création de l'évolution "attribuer" pour ce ticket
			 */
			
			// Création du ticket
			// ------------------

			$session_id = $_SESSION['usr_connected']['id'];

			$sql = "INSERT INTO `TICKET`(`ID_RESPONSABLE`, `NUM_CATEGORIE`, `NUM_LIEU`, `NUM_STATUT`, `ID_CREATEUR`, `NUM_IMPORTANCE`, `TITRE`, `DESCRIPTION`, `D_OUVERTURE`, `H_OUVERTURE`)\n"
				 ."VALUES (".$agent.", ".$categorie.", ". $lieu.", 2, ".$session_id.", ".$importance.", \"".$titre."\" , \"".$description."\", CURDATE(), CURTIME())";

			try {
				$bdd->exec($sql);
			} catch(PDOException $pdoe) {
				$_SESSION['msg'] = "Erreur interne:"
								 . '<span>'
								 . $pdoe->getMessage()
								 . '</span>'; 
			}


			// Selection du ticket fraîchement créé
			// ------------------------------------

			$sql = "SELECT `TICKET`.`ID` AS `ID_TICKET`\n"
			    . "FROM `TICKET`\n"
			    . "WHERE `TITRE` LIKE \"$titre\" AND `DESCRIPTION` LIKE \"$description\"\n"
			    . "ORDER BY `D_OUVERTURE`, `H_OUVERTURE` DESC";
			$res = $bdd->query($sql);
			$table = $res->fetch(PDO::FETCH_ASSOC);

			$id_ticket = $table['ID_TICKET'];

			newEvo($id_ticket, $session_id, 1, $bdd);

		} else {

			/*
			 * Cas 2
			 * -----
			 */

			$session_id = $_SESSION['usr_connected']['id'];

			$sql = "INSERT INTO `TICKET`(`ID_RESPONSABLE`, `NUM_CATEGORIE`, `NUM_LIEU`, `NUM_STATUT`, `ID_CREATEUR`, `NUM_IMPORTANCE`, `TITRE`, `DESCRIPTION`, `D_OUVERTURE`, `H_OUVERTURE`)\n"
				 ."VALUES (NULL, ".$categorie.", ". $lieu.", 1, ".$session_id.", ".$importance.", \"".$titre."\" , \"".$description."\", CURDATE(), CURTIME())";

			try {
				$bdd->exec($sql);
			} catch(PDOException $pdoe) {
				$_SESSION['msg'] = "Erreur interne:"
								 . '<span>'
								 . $pdoe->getMessage()
								 . '</span>'; 
			}

		}


		// Initialisation des variables
		$titre = $description = "";
		$titre_err = $description_err = FALSE;
		$lieu = $categorie = $importance = $agent = 1;
		$_POST = array();
		unset($_POST);
		$_SESSION['msg'] = 'Ticket correct !<br> Le formulaire est <b>correct</b>, et votre ticket à bien été ajouté !<br>Vous pouvez faire un <b>nouvel ajout</b> si vous le souhaitez.';
		header('location: '.$_SERVER['PHP_SELF']);
		exit();

	} else {

		$snackbar = 'Le formulaire est <b>incorrect</b>, certaines données sont érronées ou absentes.';
		
	}

} else {

	$msg = "";

	// Initialisation des variables
	$titre = $description = "";
	$lieu = $categorie = $importance = $agent = 1;

}

?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
	<?php

	// Inclusion des éléments placé dans la balise <head>
	include_once "../struct/head.php";
	$titre_page = "Créer un ticket". $title;
	echo '<title>'.$titre_page.'</title>';

	?>
	<link rel="stylesheet" href="../css/creer_ticket.css">
</head>
<body>
	<div id="page">
		<?php

		// Inclusion de l'en-tête
		include_once "../struct/header.php";

		?>
		<section>
			<h1>Créer un nouveau ticket</h1>
			<p>Les champs indiqués par un * sont obligatoires. L'<b>importance</b>, l'agent <b>responsable</b>, la <b>catégorie</b>, le <b>lieu</b> ne sont pas obligatoire, vous pourrez les spécifier plus tard si besoin.</p>
			<p>Si <b>tout</b> les champs sont remplis correctement le ticekt sera directement "En cours", sinon il sera dans "Attribuer".</p>
			<div class="container">
				<form id="new_ticket" method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

						<div class="mi_largeur">
							<label for="titre">Titre *</label>
							<input id="titre" name="titre" type="text" placeholder="Titre du ticket" required value="<?php echo $titre; ?>">
							<span class="info">Veuillez mettre un titre court explicite, par exemple "Ampoule à changer"</span>
						</div><!--

					 --><div class="mi_largeur">
							<label for="importance">Importance</label>
							<?php echo listerImportance($importance, $bdd); ?>
						</div><!--

					 --><div class="pleine_largeur">
					 		<label for="description">Description *</label>
					 		<textarea id="description" name="description" type="text" placeholder="Description simple du ticket ..." required value="<?php echo $description; ?>"></textarea>
							<span class="info">
								Veuillez décrire de façon brève mais précise le problème, évitez les "bonjour/aurevoir". C'est bien d'être poli mais ça n'a pas lieu d'être ici
							</span>
					 	</div><!--

					 --><div class="mi_largeur">
							<label for="lieu">Lieu</label>
							<?php echo listerLieu($lieu, $bdd); ?>
					 	</div><!--

					 --><div class="mi_largeur">
							<label for="categorie">Catégorie</label>
							<?php echo listerCategorie($categorie, $bdd); ?>
					 	</div><!--

					 --><div class="mi_largeur">
							<label for="agent">Responsable du ticket</label>
							<?php echo listerAgent($agent, $bdd); ?>	
					 	</div><!--
						
					 --><div id="boutons" class="pleine_largeur">
							<input class="button" type="submit" value="Créer le ticket">
							<input class="button" type="reset" value="Vider le formulaire">
						</div>
				</form>
			</div>
		</section>
		<?php

		// Inclusion du pied de page (footer)
		include_once $_SESSION['PHP_PATH'] . "struct/footer.php";

		?>

	</div>
<script type="text/javascript" src="<?php echo $_SESSION['HTML_PATH']; ?>js/valid_form.js"></script>
</body>
</html>