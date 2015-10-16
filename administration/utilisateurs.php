<?php
/**
 * Gestion des utilisateurs
 *
 * @author Anthony Lozano (2015)
 *
 * @version  1.2.0
 */



/*
 * Inclusion(s)
 * ------------
 * ~ Session
 * ~ Connexion à la base
 * ~ Fonctions permettant de créer les listes HTML
 * ~ Snack bar (message d'info)
 * ~ Fonction test_input($data)
 */
include_once "../struct/session.php";
include_once "../php/t_connex_bd.php";
include_once "../php/snackbar.php";
// include_once "../php/listes_formulaire.php";
include_once "../php/test_input.php";

$agent = 0;
$nom = $prenom = $classe = $fonction = $tel = $email = $id_connex = $mdp_connex = $mdpv_connex = NULL;


if ($_SERVER["REQUEST_METHOD"] == "POST") {


	/*
	 * Nouvel utilisateur
	 */
	if(isset($_POST['nv_utilisateur'])) {

		/*
		 * init
		 * ----
		 */
		
		$nom_err = $prenom_err = $classe_err = $fonction_err = $tel_err = $email_err = $id_connex_err = $mdp_connex_err = false;
		$pdo_erreur = false;

		/*
		 * Nom
		 * ---
		 * ~ Requis
		 * ~ Texte simple
		 */
		if (empty($_POST["nom"])) {
			$nom_err = TRUE;
		} else {
			$nom = test_input($_POST["nom"]);
		}

		/*
		 * Prenom
		 * ------
		 * ~ Requis
		 * ~ Texte simple
		 */
		if (empty($_POST["prenom"])) {
			$prenom_err = TRUE;
		} else {
			$prenom = test_input($_POST["prenom"]);
		}

		/*
		 * Classe
		 * ------
		 * ~ Requis (liste)
		 */
		$classe = test_input($_POST["classe"]);
		
		/*
		 * Fonction
		 * ------------
		 * ~ Facultatif
		 * ~ Texte simple
		 */
		if (empty($_POST["fonction"])) {
			$fonction_err = TRUE;
		} else {
			$fonction = test_input($_POST["fonction"]);
		}

		/*
		 * Telephone
		 * ---------
		 * ~ Facultatif
		 * ~ Texte simple
		 */
		if (empty($_POST["tel"])) {
			$tel_err = TRUE;
		} else {
			$tel = test_input($_POST["tel"]);
		}

		/*
		 * Email
		 * -----
		 * ~ Facultatif
		 * ~ Texte simple
		 */
		if (empty($_POST["email"])) {
			$email_err = TRUE;
		} else {
			$email = test_input($_POST["email"]);
		}

		/*
		 * Identifiant
		 * -----------
		 * ~ Facultatif
		 * ~ Texte simple
		 */
		if (empty($_POST["id_connex"])) {
			$id_connex_err = TRUE;
		} else {
			$id_connex = test_input($_POST["id_connex"]);
		}

		/*
		 * Mot de passe
		 * ------------
		 * ~ Facultatif
		 * ~ Texte simple
		 */
		if (empty($_POST["mdp_connex"])) {
			$mdp_connex_err = TRUE;
		} else {
			$mdp_connex = test_input($_POST["mdp_connex"]);
		}

		/*
		 * Mot de passe
		 * ------------
		 * ~ Facultatif
		 * ~ Texte simple
		 */
		if ($_POST["mdp_connex"] == ($_POST["mdpv_connex"])) {
			$mdpv_connex = test_input($_POST["mdpv_connex"]);
		} else {
			$mdpv_connex_err = TRUE;
		}

		/*
		 * S'il n'y a pas d'erreur, ajout de l'utilisateur	 dans la base
		 * Sinon, message d'erreur
		 */
		if (!($nom_err && $prenom_err && $id_connex_err && $mdp_connex_err && $mdpv_connex_err)) {

			$mdp_connex = password_hash($mdp_connex, PASSWORD_DEFAULT);

			$sql = "INSERT INTO `UTILISATEUR`(`NOM`, `PRENOM`, `NUM_CLASSE`, `FONCTION`,`TEL`, `MAIL`, `ID_CONNEX`, `PASS_CONNEX`)\n"
				 . "VALUES (\"$nom\", \"$prenom\", $classe, \"$fonction\", \"$tel\", \"$email\", \"$id_connex\", \"$mdp_connex\");";

			try {
				$bdd->exec($sql);
			} catch(PDOException $pdoe) {
				$pdo_erreur = TRUE;
			}

			$snackbar = $pdo_erreur ? "Désolé mais une erreur est apparue, impossible d'ajouté l'utilisateur".'<br><span>'.$pdoe->getMessage().'</span>' : "Le utilisateur \"$nom $prenom\" a correctement été ajouté !";
			$nom = $prenom = $classe = $fonction = $tel = $email = $id_connex = $mdp_connex = $mdpv_connex = NULL;
			// header('location: '.$_SERVER['PHP_SELF']);

		} else {

			$snackbar = "Le utilisateur que vous avez rentré est incorrect.";

		}

	}

	/*
	 * Supprimé un utilisateur
	 */
	if(isset($_POST['sup_utilisateur'])) {

		$pdo_erreur = false;

		$agent = $_POST['agent'];

			$sql = "DELETE FROM `UTILISATEUR`\n"
				 . "WHERE `ID` = $agent;";

			try {
				$bdd->exec($sql);
			} catch(PDOException $pdoe) {
				$pdo_erreur = TRUE;
			}

			if ($pdo_erreur) {
				if ($pdoe->getCode() == 23000)
					$msg = "<span>Vous ne pouvez pas supprimer cet utilisateur car il est utilisé dans un ou des ticket(s) et sa suppression entrainerait un malfonctionnement de l'application.</span>";
				else
					$msg = "Désolé mais une erreur est apparue, impossible de supprimé l'utilisateur".'<br>'.'<br><span>'.$pdoe->getMessage().'</span>';
			} else {
				$msg = "L'utilisateur a correctement été supprimé&nbsp;!";
			}

			$snackbar = $msg;

	}

	/*
	 * Modifier un utilisateur
	 */
	if(isset($_POST['mod_usr'])) {

		print_r($_POST);



		header('location: '.$_SERVER['PHP_SELF']);
	}


}

/**
 * Permet de créer une liste HTML (élément <select/>) contenant les colonnes de la table Lieu
 * @param  int $num Permet de déterminé l'option de la liste pré-selectionné
 * @param  PDO $pdo Instance de l'objet PDO permettant la connexion à la base de données
 * @return string Retourne les éléments HTML
 */
function listerClasse($num, PDO $pdo) {

	$sql = "SELECT `NUM`, `NOM` FROM `CLASSE` ORDER BY `NOM` ASC;";
	$res = $pdo->query($sql);
	$table = $res->fetchAll(PDO::FETCH_ASSOC);

	$liste = '<select name="classe" required>'."\n";
	foreach ($table as $row){

		$selected = $row['NUM'] == $num ? $selected = 'selected' : "";

		$liste .= '<option value="'.$row['NUM'].'" '.$selected.'>'
				. $row['NOM']
				. '</option>'."\n";
		
	}
	$liste .= '</select>';
	return $liste;
}

/**
 * Permet de créer une liste HTML (élément <select/>) contenant les colonnes nom/prénom de la table Utilisateurs
 * @param  int $num Permet de déterminé l'option de la liste pré-selectionné
 * @param  PDO $pdo Instance de l'objet PDO permettant la connexion à la base de données
 * @return string Retourne les éléments HTML
 */
function listerAgent($num, PDO $pdo) {

	$sql = "SELECT `ID`, CONCAT(`NOM`, \" \", `PRENOM`) AS `NOM` FROM `UTILISATEUR` ORDER BY `NUM_CLASSE`, `NOM` ASC ;";
	$res = $pdo->query($sql);
	$table = $res->fetchAll(PDO::FETCH_ASSOC);

	$liste = '<select name="agent">'."\n";
	foreach ($table as $row){

		$selected = "";

		if ($row['ID'] == $num)
			$selected = 'selected';

		$liste .= '<option value="'.$row['ID'].'" '.$selected.'>'
				. $row['NOM']
				. '</option>'."\n";
	}
	$liste .= '</select>';
	return $liste;
}


/* 
 * Liste utilisateurs
 * ------------------
 */

function listerUsr(PDO $pdo) {
	$sql = "SELECT `ID`, `CLASSE`.`NOM` AS `NOM_CLASSE`, CONCAT(`UTILISATEUR`.`NOM`, \" \", `UTILISATEUR`.`PRENOM`) AS `NOM_UTILISATEUR`, `TEL`, `MAIL`, `ID_CONNEX`\n"
	 . "FROM `UTILISATEUR`\n"
	 . "INNER JOIN `CLASSE`\n\t\t"
	 . "ON `UTILISATEUR`.`NUM_CLASSE` = `CLASSE`.`NUM`\n"
	 . "ORDER BY `UTILISATEUR`.`NUM_CLASSE`, `UTILISATEUR`.`NOM` ASC;";

	try {
	$res = $pdo->query($sql);
	$table = $res->fetchAll(PDO::FETCH_ASSOC);
	} catch(PDOException $pdoe) {
		$snackbar = "Impossible de récupérer l'utilisateur ... <br><span>".$pdoe->getMessage()."</span>";
		$table = array(array());
	}

	$liste_utilisateur = '<table>'
				. '<tr>'
				. '<th>Nom</th>'
				. '<th>Type</th>'
				. '<th>Téléphone</th>'
				// . '<th>Mail</th>'
				. '<th>Identifiant</th>'
				. '</tr>';

	foreach ($table as $row) {

		$liste_utilisateur .= '<tr>'
					 . '<td>'.$row['NOM_UTILISATEUR'].'</td>'
					 . '<td>'.$row['NOM_CLASSE'].'</td>'
					 . '<td>'.$row['TEL'].'</td>'
					 // . '<td>'.$row['MAIL'].'</td>'
					 . '<td>'.$row['ID_CONNEX'].'</td>'
					 . '</tr>';

	}

	$liste_utilisateur .= '</table>';

	return $liste_utilisateur;

}

function listerModUsr(PDO $pdo) {
	$sql = "SELECT `ID`, `CLASSE`.`NOM` AS `NOM_CLASSE`, `NUM_CLASSE`, `UTILISATEUR`.`NOM`, `UTILISATEUR`.`PRENOM`, `TEL`, `MAIL`, `FONCTION`, `ID_CONNEX`\n"
	 . "FROM `UTILISATEUR`\n"
	 . "INNER JOIN `CLASSE`\n\t\t"
	 . "ON `UTILISATEUR`.`NUM_CLASSE` = `CLASSE`.`NUM`\n"
	 . "ORDER BY `UTILISATEUR`.`NUM_CLASSE`, `UTILISATEUR`.`NOM` ASC;";

	try {
	$res = $pdo->query($sql);
	$table = $res->fetchAll(PDO::FETCH_ASSOC);
	} catch(PDOException $pdoe) {
		$snackbar = "Impossible de récupérer l'utilisateur ... <br><span>".$pdoe->getMessage()."</span>";
		$table = array(array());
	}

	$liste_utilisateur = '<table>'
				. '<tr>'
				. '<th>Nom</th>'
				. '<th>Prénom</th>'
				. '<th>Type</th>'
				. '<th>Téléphone</th>'
				. '<th>Mail</th>'
				. '<th>Fonction</th>'
				. '<th>Identifiant</th>'
				. '</tr>';

	foreach ($table as $row) {

		$liste_utilisateur .= '<tr>'
					 . '<td>'.'<input type="text" name="nom" value="'.$row['NOM'].'" required>'.'</td>'
					 . '<td>'.'<input type="text" name="prenom" value="'.$row['PRENOM'].'" required>'.'</td>'
					 . '<td>'.listerClasse($row['NUM_CLASSE'], $pdo).'</td>'
					 . '<td>'.'<input type="tel" name="tel" value="'.$row['TEL'].'">'.'</td>'
					 . '<td>'.'<input type="email" name="email" value="'.$row['MAIL'].'">'.'</td>'
					 . '<td>'.'<input type="text" name="fonction" value="'.$row['FONCTION'].'">'.'</td>'
					 . '<td>'.'<input type="text" name="id_connex" value="'.$row['ID_CONNEX'].'">'.'</td>'
					 . '</tr>';

	}

	$liste_utilisateur .= '</table>';

	return $liste_utilisateur;

}

?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
	<?php

	// Inclusion des éléments placé dans la balise <head>
	include_once("../struct/head.php");
	$titre_page = "Gestion des utilisateurs". $title;
	echo '<title>'.$titre_page.'</title>';

	?>
	<link rel="stylesheet" type="text/css" href="../css/admin.css">
	<link rel="stylesheet" href="../css/creer_ticket.css">
</head>
<body>
	<div id="page">
		<?php

		// Inclusion de l'en-tête
		include_once "../struct/header.php";

		?>

		<section>
		<h1>Gestion des utilisateurs</h1>
			<div class="container">
				<div class="sep li">
					<h2>Liste des utilisateurs</h2>
					<?php

					echo listerUsr($bdd);

					?>
				</div>
			</div>
			<div class="container">
				<div class="sep aj">
					<h2>Ajouter un utilisateur</h2>
					<form method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
						<div class="mi_largeur">
							<label for="nom">Nom *</label>
							<input type="text" id="nom" name="nom" value="<?php echo $nom; ?>" required placeholder="Nom de l'utilisateur ...">
						</div><!--
					 --><div class="mi_largeur">
							<label for="prenom">Prénom *</label>
							<input type="text" id="prenom" name="prenom" value="<?php echo $prenom; ?>" required placeholder="Prénom de l'utilisateur ...">
						</div><!--
					 --><div class="mi_largeur">
							<label for="classe">Type d'utilisateur *</label>
							<?php echo listerClasse(0, $bdd); ?>
						</div><!--
					 --><div class="mi_largeur">
							<label for="fonction">Fonction</label>
							<input type="fonction" id="fonction" name="fonction" value="<?php echo $fonction; ?>" placeholder="Fonction de l'utilisateur ...">
						</div><!--
					 --><div class="mi_largeur">
							<label for="tel">Numéro de téléphone</label>
							<input type="tel" id="tel" name="tel" value="<?php echo $tel; ?>" placeholder="Numéro de téléphone ...">
						</div><!--
					 --><div class="mi_largeur">
							<label for="email">Adresse email</label>
							<input type="email" id="email" name="email" value="<?php echo $email; ?>" placeholder="Adresse email...">
						</div><!--
					 --><hr><!--
					 --><div class="pleine_largeur">
							<label for="id_connex">Identifiant *</label>
							<input type="text" id="id_connex" name="id_connex" value="<?php echo $id_connex; ?>" required placeholder="Identifiant de connexion...">
						</div><!--
					 --><div class="mi_largeur">
							<label for="mdp_connex">Mot de passe *</label>
							<input type="password" id="mdp_connex" name="mdp_connex" value="<?php echo $mdp_connex; ?>" required placeholder="Mot de passe ...">
						</div><!--
					 --><div class="mi_largeur">
							<label for="mdpv_connex">Retaper votre mot de passe *</label>
							<input type="password" id="mdpv_connex" name="mdpv_connex" value="<?php echo $mdpv_connex; ?>" required placeholder="Mot de passe de vérification ...">
						</div><!--
					 --><div class="pleine_largeur boutons">
							<input class="button" type="submit" name="nv_utilisateur" value="Ajouter l'utilisateur">
						</div>
					</form>
				</div>
			</div>
			<div class="container">
				<div class="sep sup">
					<h2>Supprimer un utilisateur</h2>
					<p>Supprimez un utilisateur en le selectionnant dans la liste ci-dessous.</p>
					<form method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return confirm(Êtes vous sûr de vouloir supprimer cet utilisateur ?);">
						<div class="pleine_largeur">
							<?php
							echo listerAgent($agent, $bdd);
							?>
						</div><!--
					 --><div class="pleine_largeur boutons">
							<input class="button" type="submit" name="sup_utilisateur" value="Supprimer l'utilisateur">
						</div>
					</form>
				</div>
			</div>
			<div class="container">
				<div class="sep mod">
					<h2>Modifier les utilisateurs</h2>
					<form method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
					<?php

					// echo listerModUsr($bdd);
					
					?>
					<!--<div class="boutons">
							<input class="button" type="submit" name="mod_usr" value="Modifier la/les catégorie(s)">
							<input class="button" type="reset" value="Annuler les modifications">
					</div>-->
						Désolé mais cette fonctionnalité n'est pas encore implémentée.
					</form>
				</div>
			</div>
			<div class="container">
				<div class="sep sup">
					<h2>Enregistrer un nouveau mot de passe</h2>
					<p>Modifier le mot de passe pour un utilisateur.</p>
					<form method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return confirm('Êtes vous sûr de vouloir modifier le mot de passe ?');">
						Désolé mais cette fonctionnalité n'est pas encore implémentée.
					</form>
				</div>
			</div>
		</section>

		<?php

		// Inclusion du pied de page (footer)
		include_once "../struct/footer.php";

		?>

	</div>
</body>
</html>