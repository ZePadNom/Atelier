<?php 
/**
 * Module évolution
 *
 * @author Emilie Graton (V.1 - 2012)
 * @author Anthony Lozano (V.2 - 2015)
 *
 * @version  2.0.0
 */

/**
 * Renvoie les informations sur un ticket passé en paramètre relatives à l'évolution de celui-ci
 * @param  int $id_ticket	Identifiant de la talbe SQL `TICKET`
 * @param  PDO $pdo			Instance de l'objet PDO permettant de dialoguer avec la base de donnée
 * @return string			Renvoie sur forme d'élément HTML les évolutions du ticket
 */
function newEvo($id_ticket, $id_usr, $action, PDO $pdo) {


		// Selection de l'id pour créer l'identifiant relatif sur evolution
		// ----------------------------------------------------------------

		$sql = "SELECT MAX(ID) + 1 AS `ID_MAX`\n"
			 . "FROM `EVOLUTION`\n"
			 . "WHERE `ID_TICKET` = $id_ticket";

		$res = $pdo->query($sql);
		$table = $res->fetch(PDO::FETCH_ASSOC);

		if ($table['ID_MAX'] == NULL)
			$id_evo = 1;
		else
			$id_evo = $table['ID_MAX'];

		// Ajout de l'évolution
		// --------------------

		$sql = "INSERT INTO `EVOLUTION`(`ID_TICKET`, `ID`, `ID_UTILISATEUR`, `NUM_ACTION`, `H_EVO`, `D_EVO`)\n"
			 . "VALUES ($id_ticket, $id_evo, $id_usr, $action, CURTIME(), CURDATE())";

		try {
			$pdo->exec($sql);
		} catch(PDOException $pdoe) {
			$_SESSION['msg'] = "Erreur interne&nbsp;:<br><pan>".$pdoe->getMessage()."</span>";
		}

}

function newEvoChamp($id_ticket, $id_usr, $champ, $champ_lib, PDO $pdo) {


		// Selection de l'id pour créer l'identifiant relatif sur evolution
		// ----------------------------------------------------------------

		$sql = "SELECT MAX(ID) + 1 AS `ID_MAX`\n"
			 . "FROM `EVOLUTION`\n"
			 . "WHERE `ID_TICKET` = $id_ticket";

		$res = $pdo->query($sql);
		$table = $res->fetch(PDO::FETCH_ASSOC);

		if ($table['ID_MAX'] == NULL)
			$id_evo = 1;
		else
			$id_evo = $table['ID_MAX'];

		// Ajout de l'évolution
		// --------------------

		$sql = "INSERT INTO `EVOLUTION`(`ID_TICKET`, `ID`, `ID_UTILISATEUR`, `NUM_ACTION`, `CHAMP`, `LIB_CHAMP`, `H_EVO`, `D_EVO`)\n"
			 . "VALUES ($id_ticket, $id_evo, $id_usr, 4, \"$champ\", \"$champ_lib\", CURTIME(), CURDATE())";

		try {
			$pdo->exec($sql);
		} catch(PDOException $pdoe) {
			$_SESSION['msg'] = "Erreur interne&nbsp;:<br><pan>".$pdoe->getMessage()."</span>";
		}


}

function getEvolution($id_ticket, $pdo) {

	// Créateur

	$sql = "SELECT  `ID_CREATEUR`,\n\t"
		 . "CONCAT(`UTILISATEUR`.`NOM`, \" \", `UTILISATEUR`.`PRENOM`) AS `NOM_CREATEUR`,\n"
		 . "CONCAT(DATE_FORMAT(`D_OUVERTURE`, '%d/%m/%Y'), \" à \",DATE_FORMAT(`H_OUVERTURE`, '%H:%i')) AS `DH_CREATION`\n"
	     . "FROM `TICKET`\n"
	     . "INNER JOIN `UTILISATEUR`\n\t\t"
	     . "ON `UTILISATEUR`.`ID` = `TICKET`.`ID_CREATEUR`\n"
	     . "WHERE `TICKET`.`ID` = $id_ticket;";

	$res = $pdo->query($sql);
	$table = $res->fetch(PDO::FETCH_ASSOC);

	$nom_createur = $table['NOM_CREATEUR'];
	$dh_creation = $table['DH_CREATION'];


	$evolution = '<div class="histo">'
			   . '<h2>Historique</h2>';

	$evolution .= '<table>'
			    . '<thead><tr>'
			    . '<th>Date et heure</th>'
			    . '<th>Auteur</th>'
			    . '<th>Action</th>'
			    . '<th>Champ</th>'
			    . '<th>Ancienne valeur</th>'
			    . '</tr></thead>'
			    . '<tbody>'
			    . '<tr>'
			    . '<td>'.$dh_creation.'</td>'
			    . '<td>'.$nom_createur.'</td>'
			    . '<td>'."Créer".'</td>'
			    . '<td></td>'
			    . '<td></td>'
			    . '</tr>';

	// autres évolutions

	$sql = "SELECT  `ID_TICKET`, `CHAMP`, `LIB_CHAMP`, `EVOLUTION`.`ID`,\n\t"
		 . "`ACTION`.`NOM` AS `NOM_ACTION`,\n\t"
		 . "CONCAT(`UTILISATEUR`.`NOM`, \" \", `UTILISATEUR`.`PRENOM`) AS `NOM_AUTHEUR`,\n\t"
		 . "CONCAT(DATE_FORMAT(`D_EVO`, '%d/%m/%Y'), \" à \",DATE_FORMAT(`H_EVO`, '%H:%i')) AS `DH_EVO`\n\t"
		 . "FROM `EVOLUTION`\n"
		 . "INNER JOIN `UTILISATEUR`\n\t\t"
		 . "ON `EVOLUTION`.`ID_UTILISATEUR` = `UTILISATEUR`.`ID`\n"
		 . "INNER JOIN `ACTION`\n\t\t"
		 . "ON `EVOLUTION`.`NUM_ACTION` = `ACTION`.`NUM`\n"
		 . "WHERE `ID_TICKET` = $id_ticket\n"
		 . "ORDER BY `EVOLUTION`.`ID` ASC;";

	$res = $pdo->query($sql);
	$table = $res->fetchAll(PDO::FETCH_ASSOC);

	foreach ($table as $row) {

		$action = $row['NOM_ACTION'];
		$nom_autheur = $row['NOM_AUTHEUR'];
		$dh_evo = $row['DH_EVO'];
		$champ = empty($row['CHAMP']) ? "" : $row['CHAMP'];
		$champ_lib = empty($row['LIB_CHAMP']) ? "" : $row['LIB_CHAMP'];


		$evolution .= '<tr>'
				   . '<td>'.$dh_evo.'</td>'
				   . '<td>'.$nom_autheur.'</td>'
				   . '<td>'.$action.'</td>'
				   . '<td>'.$champ.'</td>'
				   . '<td>'.$champ_lib.'</td>';




		$evolution .= '</tr>';
	}

	$evolution .= '</tbody>'
			    . '</table>'
				. '</div>';

	return $evolution;
}

?>