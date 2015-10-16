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
 * ~ Fonction test_input($data){}
 * ~ Snack bar (message d'info)
 */
include_once "php/test_input.php";
include_once "php/snackbar.php";


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
  		// Inclusion de l'instance de l'objet PDO
		include_once('php/t_connex_bd.php');

		$sql = "SELECT `ID`, `UTILISATEUR`.`NOM` AS `NOM`, `PRENOM`, `TEL`, `MAIL`, `UTILISATEUR`.`NUM_CLASSE` AS `CLASSE`, `CLASSE`.`NOM` AS `TYPE_DE_COMPTE`, `ID_CONNEX`, `PASS_CONNEX`
				FROM `UTILISATEUR`
				INNER JOIN `CLASSE`
						ON `UTILISATEUR`.`NUM_CLASSE` = `CLASSE`.`NUM`;";
		try {
			$res = $bdd->query($sql);
			$table = $res->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $pdoe) {
			$snack = "Erreur interne, impossible de comparé les identifiants.";
			$table = array(array());
		}

		foreach ($table as $row) {

			// Données de connexion présent dans la base
			$id_bdd = $row['ID_CONNEX'];
			$pass_bdd = $row['PASS_CONNEX'];

			if ($id == $id_bdd && password_verify($mdp, $pass_bdd)) {

				session_start();
				$_SESSION['usr_connected']['id'] = $row['ID'];
				$_SESSION['usr_connected']['password'] = $mdp;
				$_SESSION['usr_connected']['nom'] = ucfirst(strtolower($row['NOM']));
				$_SESSION['usr_connected']['prenom'] = ucfirst(strtolower($row['PRENOM']));
				$_SESSION['usr_connected']['classe'] = $row['CLASSE'];
				$_SESSION['usr_connected']['tdc'] = strtolower($row['TYPE_DE_COMPTE']);

				// et enfin, redirection vers la page d'accueil
				header('Location: accueil.php');
				exit();
			} else {
				$snackbar = "Identifiant ou mot de passe incorrect, désolé !";
			}

		}

	} else {
		$snackbar = "Identifiant ou mot de passe incorrect, désolé !";
	}

}

?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
	<?php

	// Inclusion des éléments placé dans la balise <head>
	include_once("struct/head.php");
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
		include_once "struct/footer.php";

		?>

	</div>
</body>
</html>