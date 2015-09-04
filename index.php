<?php
/**
 * Cette page d'index contient le formulaire de connexion à l'application
 *
 * Version PHP >= 5.5.0
 *
 * @author Emilie Graton (V. 1 - 2012)
 * @author Anthony Lozano (2015)
 *
 * @version  2.0.0
 */


/*
 * Inclusion(s)
 * ------------
 * ~ Constantes de configurations
 */
require_once "config.php";


// Remplissage de la session
session_start();

// Chemins
$_SESSION['HTML_PATH'] = HTML_PATH;
$_SESSION['PHP_PATH'] = PHP_PATH;

/*
 * Inclusion(s)
 * ------------
 * ~ Fonction test_input($data){}
 * ~ Instance de PDO
 * ~ Snack bar (message d'info)
 */
require_once PHP_PATH . "php/test_input.php";
require_once PHP_PATH . "bdd/t_connex_bd.php";
include_once PHP_PATH . "php/snackbar.php";

// Déclaration des variables à vide
$id = $mdp = "";
$id_err = $mdp_err = FALSE;


// Si l'utilisateur à envoyer le formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {


	/*
	 * Identifiant
	 * -----------
	 * ~ Requis
	 * ~ Pas de condition de validation particulière 
	 */
	if (empty($_POST["id"])) {
		$id_err = TRUE;
	} else {
		$id = test_input($_POST["id"]);
	}

	/*
	 * Mot de passe
	 * ------------
	 * ~ Requis
	 * ~ Pas de condition de validation particulière
	 */
	if (empty($_POST["password"])) {
		$mdp_err = TRUE;
	} else {
		$mdp = test_input($_POST["password"]);
	}

	if (!($id_err && $mdp_err)) {

		$sql = "SELECT `ID`, `UTILISATEUR`.`NOM` AS `NOM`, `PRENOM`, `TEL`, `MAIL`, `UTILISATEUR`.`NUM_CLASSE` AS `CLASSE`, `CLASSE`.`NOM` AS `TYPE_DE_COMPTE`, `ID_CONNEX`, `PASS_CONNEX`\n"
			 . "FROM `UTILISATEUR`\n"
			 . "INNER JOIN `CLASSE`\n\t"
			 . "ON `UTILISATEUR`.`NUM_CLASSE` = `CLASSE`.`NUM`\n"
			 . "WHERE `ID_CONNEX` = \"".$id."\";";

		try {
			$res = $bdd->query($sql);
			$table = $res->fetch(PDO::FETCH_ASSOC);
		} catch(PDOException $pdoe) {
			$snack = "Erreur interne, impossible de comparé les identifiants.";
			$table = array(array());
		}

		if ($id == $table['ID_CONNEX']) {

			// Utilisateur connecté
			$_SESSION['usr_connected']['id'] = $table['ID'];
			$_SESSION['usr_connected']['password'] = $mdp;
			$_SESSION['usr_connected']['nom'] = ucfirst(strtolower($table['NOM']));
			$_SESSION['usr_connected']['prenom'] = ucfirst(strtolower($table['PRENOM']));
			$_SESSION['usr_connected']['classe'] = $table['CLASSE'];
			$_SESSION['usr_connected']['tdc'] = strtolower($table['TYPE_DE_COMPTE']);

			// et enfin, redirection vers la page d'accueil
			header('Location: ' . HTML_PATH . 'accueil.php');
			exit();

		} else {
			$snackbar = "Identifiant ou mot de passe incorrect, désolé !";
		}

	} else {
		$snackbar = "Champ(s) incorrect(s), réessayez !";
	}

}

?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
	<?php

	// Inclusion des éléments placé dans la balise <head>
	include_once PHP_PATH . "struct/head.php";
	$titre_page = "Connexion". $title;
	echo '<title>'.$titre_page.'</title>';

	?>
	<link rel="stylesheet" href="css/connexion.css">
</head>
<body>
	<div id="page">
		<?php

		// Inclusion de l'en-tête
		include_once "struct/header.html";

		?>
		<section>
			<div class="container">
				<h2>Bienvenue sur l'application de gestion de l'atelier</h2>
				<p>Pour commencer, authentifiez vous</p>
				<form method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
					<div>
						<label for="id">Identifiant</label>
						<input type="text" id="id" name="id" placeholder="Identifiant" value="<?php echo $id ?>" required>
						<span><?php //if($id_err) echo "Identifiant vide ou incorrect"; ?></span>
					</div>
					<div>
						<label for="password">Mot de passe</label>
						<input type="password" id="password" name="password" placeholder="Mot de passe" value="<?php echo $mdp ?>" required>
						<span><?php //if($mdp_err) echo "Mot de passe vide ou incorrect" ?></span>
					</div>
					<input id="Bconnex" class="button" type="submit" name="Connexion" value="Connexion">
				</form>
			</div>
		</section>
		<?php

		// Inclusion du pied de page (footer)
		include_once PHP_PATH . "struct/footer.php";

		?>

	</div>
</body>
</html>