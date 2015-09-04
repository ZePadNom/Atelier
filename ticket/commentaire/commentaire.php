<?php
/**
 * Module commentaire
 *
 * @author Emilie Graton (V.1 - 2012)
 * @author Anthony Lozano (V.2 - 2015)
 *
 * @version  2.0.1
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (isset($_POST['nv_com'])) {

		include_once "../php/test_input.php";
		
		$commentaire = test_input($_POST['commentaire']);
		$session_id = $_SESSION['usr_connected']['id'];
		$id_ticket = $_GET['ticketid'];

		newCom($commentaire, $id_ticket, $session_id, $bdd);

		unset($_POST);

	}

}

/**
 * Permet d'ajout un commentaire pour un ticket donné, sans avoir à gérer l'identifiant relatif
 * @param  string $commentaire	Valeur du champs "commentaire"
 * @param  int $id_ticket		Identifiant du ticket où on ajoute le commentaire
 * @param  int $id_usr			Identifiant de l'auteur du commentaire
 * @param  PDO $pdo				Instance de l'objet PDO permettant de dialoguer avec la base de donnée
 */
function newCom($commentaire, $id_ticket, $id_usr, PDO $pdo) {

		// Selection de `ID` pour créer l'identifiant relatif sur commentaire
		// ------------------------------------------------------------------

		$sql = "SELECT MAX(ID) + 1 AS `ID_MAX`\n"
			 . "FROM `COMMENTAIRE`\n"
			 . "WHERE `ID_TICKET` = $id_ticket";

		$res = $pdo->query($sql);
		$table = $res->fetch(PDO::FETCH_ASSOC);

		if ($table['ID_MAX'] == NULL)
			$id_com = 1;
		else
			$id_com = $table['ID_MAX'];


		// Insertion de l'evolution
		// -----------------------

		$sql = "INSERT INTO `COMMENTAIRE`(`ID_TICKET`, `ID`, `ID_UTILISATEUR`, `CONTENU`, `H_COM`, `D_COM`)\n"
			 . "VALUES ($id_ticket, $id_com, $id_usr, \"$commentaire\", CURTIME(), CURDATE())";

		try {
			$pdo->exec($sql);
			$pdo_erreur = FALSE;
		} catch(PDOException $pdoe) {
			$pdo_erreur = TRUE;
		}

		$_SESSION['msg'] = $pdo_erreur ? "Erreur interne&nbsp;:<br>".'<span>'.$pdoe->getMessage().'</span>'
									   : "Commentaire ajouté.";

}

/**
 * Permet d'afficher le module commentaire, comprenant la liste des commentaires pour un ticket
 * et le formulaire pour que l'utilisateur en rédige un nouveau.
 * @param  int $id_ticket	Identifiant du ticket sur lequel on affiche les commentaires
 * @param  PDO $pdo			Instance de l'objet PDO permettant de dialoguer avec la base de donnée
 * @return string			Elements HTML pour afficher les commentaires et le formulaire pour en rédiger un
 */
function getCommentaire($id_ticket, PDO $pdo) {

	$sql = "SELECT  `ID_TICKET`, `COMMENTAIRE`.`ID`, `ID_UTILISATEUR`, `CONTENU`,\n\t"
		 . "CONCAT(`UTILISATEUR`.`NOM`, \" \", `UTILISATEUR`.`PRENOM`) AS `NOM_AUTEUR`,"
		 . "CONCAT(DATE_FORMAT(`D_COM`, '%d/%m/%Y'), \" à \",DATE_FORMAT(`H_COM`, '%H:%i')) AS `DH_COM`\n\t"
		 . "FROM `COMMENTAIRE`\n"
		 . "INNER JOIN `UTILISATEUR`\n\t\tON `UTILISATEUR`.`ID` = `COMMENTAIRE`.`ID_UTILISATEUR`\n"
		 . "WHERE `ID_TICKET` = $id_ticket\n"
		 . "ORDER BY `D_COM`, `H_COM` ASC;";

	$res = $pdo->query($sql);
	$table = $res->fetchAll(PDO::FETCH_ASSOC);

	$coms = '<div class="coms">'
		  . '<h2>Commentaire(s)</h2>';

	if ($res->rowCount() == 0) {
		$coms .= "Aucun commentaire disponible pour ce ticket.";
	} else {
		
		foreach ($table as $row) {

			$nom_auteur = $row['NOM_AUTEUR'];
			$dh_com = $row['DH_COM'];
			$contenu = $row['CONTENU'];

			$coms .='<div class="com">'
				  . '<span>'."Par <b>$nom_auteur</b> le <b>$dh_com</b>".'</span>'
				  . '<p>'
				  . $contenu
				  . '</p>';

			$coms .= '</div>';
		}

	}


	$coms .= '<form method="post">'
		   . '<label for="commentaireta">'."Rédiger votre commentaire :".'</label>'
		   . '<textarea id="commentaireta" name="commentaire" required placeholder="Rédiger ici votre propre commentaire ...">'
		   . '</textarea>'
		   . '<input type="submit" name="nv_com" value="Envoyer le commentaire" class="button">';

	$coms .= '</form>';
	$coms .= '</div>';

	return $coms;
}

function getComForm($ticket_id, $bdd) {

	$form = "";

	return $form;
}

?>