<?php 
/**
 * Get ticket
 *
 * @author Emilie Graton (V.1 - 2012)
 * @author Anthony Lozano (V.2 - 2015)
 *
 * @version  2.0.0
 */

function getTicket($id_ticket, PDO $pdo) {

	$sql = "SELECT  `TICKET`.`ID` AS `ID_TICKET`,\n\t"
	 . "`TITRE`,\n\t"
	 . "`DESCRIPTION`,\n\t"
	 . "`NUM_CATEGORIE`, `NUM_IMPORTANCE`, `NUM_LIEU`, `NUM_STATUT`,`ID_RESPONSABLE`,\n\t"
	 . "`CATEGORIE`.`NOM` AS `NOM_CATEGORIE`,\n\t"
	 . "`IMPORTANCE`.`NOM` AS `NOM_IMPORTANCE`,\n\t"
	 . "`LIEU`.`NOM` AS `NOM_LIEU`,\n\t"
	 . "`STATUT`.`NOM` AS `NOM_STATUT`,\n\t"
	 . "DATE_FORMAT(`D_OUVERTURE`, '%d/%m/%Y') AS `D_OUVERTURE`,\n\t"
	 . "CONCAT(`UTILISATEUR`.`NOM`, \" \", `UTILISATEUR`.`PRENOM`) AS `NOM_RESPONSABLE`\n"
	 . "FROM `TICKET`\n"
	 . "INNER JOIN `CATEGORIE`\n\t\t"
	 . "ON `TICKET`.`NUM_CATEGORIE` = `CATEGORIE`.`NUM`\n"
	 . "INNER JOIN `IMPORTANCE`\n\t\t"
	 . "ON `TICKET`.`NUM_IMPORTANCE` = `IMPORTANCE`.`NUM`\n"
	 . " INNER JOIN `LIEU`\n\t\t"
	 . "ON `TICKET`.`NUM_LIEU` = `LIEU`.`NUM`\n"
	 . "LEFT OUTER JOIN `UTILISATEUR`\n\t\t"
	 . "ON `TICKET`.`ID_RESPONSABLE` = `UTILISATEUR`.`ID`\n"
	 . "INNER JOIN `STATUT`\n"
	 . "ON `TICKET`.`NUM_STATUT` = `STATUT`.`NUM`\n"
	 . "WHERE `TICKET`.`ID` = ".$id_ticket.";";
	 
	$res = $pdo->query($sql);
	$table = $res->fetchAll(PDO::FETCH_ASSOC);

	return $table;

}