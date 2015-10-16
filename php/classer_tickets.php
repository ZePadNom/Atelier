<?php
/**
 * Liste et regroupe des tickets
 * 
 * @author Anthony Lozano (2015)
 * 
 * @version  1.2.1
 */

/**
 * Permet de générer des tableaux contenants des tickets selon un critère de regroupement, cette fonction va générer
 * plusieur requête SQL et va renvoyer le résultat dans des tableaux HTML
 * 
 * @param  string $regroup  Critère de regroupement, doit être un nom de colonne de la table SQL `TICKET`
 * @param  array  $order_by Tableau contenant les nom de colonnes de la table SQL `TICKET` pour trier avec une clause ORDER BY
 * @param  string $ordre    Ordre de tri pour la clause ORDER BY, doit être égal à "ASC" ou "DESC"
 * @param  string $action   Action de la page, si la page où la fonction est appellé est lister_ticket_encours.php, l'action sera "encours", etc
 * @param  PDO    $pdo      Instance de l'objet PDO permettant de dialoguer avec la base de donnée
 * @return string           Renvoie les résultat dans des tableaux HTML 
 */
function grouperTicket($regroup, array $order_by, $ordre, $action, PDO $pdo, $datePurge = null) {

	$regroup = strtoupper($regroup);
	$ordre = strtoupper($ordre);

	/**
	 * Selection des colonnes du tableau de regroupement
	 * -------------------------------------------------
	 * On reconstruit le tableau $colonnes sans la colonne de regroupement
	 *
	 * @var array Colonnes du tableau de regroupement
	 */
		
	$colonnes = array("D_OUVERTURE", "TITRE", "DESCRIPTION", "CATEGORIE", "IMPORTANCE", "LIEU", "ID_RESPONSABLE");

	/*
	 * Détermination des tableaux de regroupement
	 * ------------------------------------------
	 * On doit, en fonction de la valeur de $regroup, déterminer 
	 * les tableaux de regroupement que l'on aura
	 */
		switch ($action) {

			case 'attribuer':
				$where = "WHERE `TICKET`.`NUM_STATUT` = 1";
				$colonnes = array("D_OUVERTURE", "TITRE", "DESCRIPTION", "CATEGORIE", "LIEU");
				break;

			case 'encours':
				$where = "WHERE (`TICKET`.`NUM_STATUT` = 2 OR `TICKET`.`NUM_STATUT` = 3)";
				$colonnes[] = "STATUT";

				if ($_SESSION['usr_connected']['classe'] == 3 )
				 	$where .= ' AND `ID_RESPONSABLE` = '.$_SESSION['usr_connected']['id'].'';
				
				break;

			case 'resolu':
				$where = "WHERE `TICKET`.`NUM_STATUT` = 4";

				if ($_SESSION['usr_connected']['classe'] == 3 ) 
				 	$where .= ' AND `ID_RESPONSABLE` = '.$_SESSION['usr_connected']['id'].'';

				break;

			case 'tous':
				$where = "WHERE `TICKET`.`NUM_STATUT` != 5";
				$colonnes[] = "STATUT";

				break;

			case 'historique':
				$where = "WHERE `TICKET`.`NUM_STATUT` = 5";
				break;
			
			default:
				$where = "";
				break;
		}


	/*
	 * Reconstruction du tableau des colonnes sans le paramètre de regroupement
	 */

	$col = array();

	$j = 0;
		
	for ($i=0; $i < count($colonnes); $i++) { 
		if ($colonnes[$i] != $regroup) {
			$col[$j] = $colonnes[$i];
			$j++;
		}			
	}

	/*
	 * Construction de la requête pour créer les tableaux HTML de regroupement (un tableau par valeur de colonne)
	 */

	switch ($regroup) {
		case 'LIEU':
			$sql = "SELECT DISTINCT `TICKET`.`NUM_LIEU`, `LIEU`.`NOM` AS `NOM_LIEU`\n"
				 . "FROM `TICKET`\n"
				 . "INNER JOIN `LIEU`\n\t\t"
				 . "ON `TICKET`.`NUM_LIEU` = `LIEU`.`NUM`\n"
				 . "$where\n"
				 . "ORDER BY `NUM_LIEU` $ordre;";
			break;

		case 'IMPORTANCE':
			$sql = "SELECT DISTINCT `TICKET`.`NUM_IMPORTANCE`, `IMPORTANCE`.`NOM` AS `NOM_IMPORTANCE`\n"
				 . "FROM `TICKET`\n"
				 . "INNER JOIN `IMPORTANCE`\n\t\t"
				 . "ON `TICKET`.`NUM_IMPORTANCE` = `IMPORTANCE`.`NUM`\n"
				 . "$where\n"
				 . "ORDER BY `NUM_IMPORTANCE` $ordre;";
			break;

		case 'CATEGORIE':
			$sql = "SELECT DISTINCT `TICKET`.`NUM_CATEGORIE`, `CATEGORIE`.`NOM` AS `NOM_CATEGORIE`\n"
				 . "FROM `TICKET`\n"
				 . "INNER JOIN `CATEGORIE`\n\t\t"
				 . "ON `TICKET`.`NUM_CATEGORIE` = `CATEGORIE`.`NUM`\n"
				 . "$where\n"
				 . "ORDER BY `NUM_CATEGORIE` $ordre;";
			break;

		case 'STATUT':
			$sql = "SELECT DISTINCT `TICKET`.`NUM_STATUT`, `STATUT`.`NOM` AS `NOM_STATUT`\n"
				 . "FROM `TICKET`\n"
				 . "INNER JOIN `STATUT`\n\t\t"
				 . "ON `TICKET`.`NUM_STATUT` = `STATUT`.`NUM`\n"
				 . "$where\n"
				 . "ORDER BY `NUM_STATUT` $ordre;";
			break;

		case 'RESPONSABLE':
			$sql = "SELECT DISTINCT `TICKET`.`ID_RESPONSABLE`, CONCAT(`UTILISATEUR`.`NOM`, \" \", `UTILISATEUR`.`PRENOM`) AS `NOM_RESPONSABLE`\n"
				 . "FROM `TICKET`\n"
				 . "LEFT OUTER JOIN `UTILISATEUR`\n\t\t"
				 . "ON `TICKET`.`ID_RESPONSABLE` = `UTILISATEUR`.`ID`\n"
				 . "$where\n"
				 . "ORDER BY `ID_RESPONSABLE` $ordre;";
			break;

		case 'D_OUVERTURE':
			$sql = "SELECT DISTINCT DATE_FORMAT(`D_OUVERTURE`, '%m_%Y') AS `D_OUVERTURE`\n"
				 . "FROM `TICKET`\n"
				 . "$where\n"
				 . "ORDER BY `TICKET`.`D_OUVERTURE` $ordre;";
			break;

		case 'AUCUN':
			$sql = "SELECT DISTINCT `ID`\n"
				 . "FROM `TICKET`\n"
				 . "$where\n"
				 . "ORDER BY `TICKET`.`D_OUVERTURE` $ordre\n"
				 . "LIMIT 1;";
			break;

		default:
			$sql = "SELECT DISTINCT `$regroup`\nFROM `TICKET`;";
			break;
	}

	// echo $sql, "\n";
	 
	try {
		$res = $pdo->query($sql);
		$table_sql = $res->fetchAll(PDO::FETCH_ASSOC);
	} catch(PDOException $pdoe) {
			$_SESSION['msg'] = "Erreur interne&nbsp;:<br><pan>".$pdoe->getMessage()."</span>";
	}

	/*
	 * Création des tableaux de regroupement
	 * -------------------------------------
	 * Pour chaque passage en boucle on va créer la requête SQL permettant de récupérer un tableau
	 * et le créer en tableau HTML 
	 */

	$liste = '';

	$nb_col_sql = $res->rowCount();

	if ($nb_col_sql == 0) {
		$liste = "Désolé, mais il semble qu'il n'y ai aucun résultat pour ces critères de recherche.";
		$summary = '';
	} else {
		$liste = '';
		$summary = '<nav class="summ"><b>Sommaire des résultats</b>'
				 . '<ul>';
	}
	

	foreach ($table_sql as $key => $row) {

		/*
		 * Clause SELECT
		 * et/ou INNER JOIN
		 * ----------------
		 */

		$select = "SELECT  `TICKET`.`ID` AS `ID_TICKET`,\n\t\t";

		$innerjoin = "";

		for ($i=0; $i < count($col); $i++) { 

			switch ($col[$i]) {

				case 'LIEU':
					$select .= "`LIEU`.`NOM` AS `NOM_LIEU`";
					$innerjoin .= " INNER JOIN `".$col[$i]."` \n\t\tON `TICKET`.`NUM_".$col[$i]."` = `".$col[$i]."`.`NUM`\n";
					break;

				case 'STATUT':
					$select .= "`STATUT`.`NOM` AS `NOM_STATUT`";
					$innerjoin .= "INNER JOIN `STATUT`\n\t\t"
								. "ON `TICKET`.`NUM_STATUT` = `STATUT`.`NUM`\n";
					break;

				case 'CATEGORIE':
					$select .= "`CATEGORIE`.`NOM` AS `NOM_CATEGORIE`";
					$innerjoin .= "INNER JOIN `CATEGORIE`\n\t\t"
								. "ON `TICKET`.`NUM_CATEGORIE` = `CATEGORIE`.`NUM`\n";
					break;

				case 'IMPORTANCE':
					$select .= "`IMPORTANCE`.`NOM` AS `NOM_IMPORTANCE`";
					$innerjoin .= "INNER JOIN `IMPORTANCE`\n\t\t"
								. "ON `TICKET`.`NUM_IMPORTANCE` = `IMPORTANCE`.`NUM`\n";
					break;

				case 'ID_RESPONSABLE':
					$select .= "CONCAT(`UTILISATEUR`.`NOM`, \" \", `UTILISATEUR`.`PRENOM`) AS `NOM_RESPONSABLE`";
					$innerjoin .= "LEFT OUTER JOIN `UTILISATEUR`\n\t\t"
								. "ON `TICKET`.`ID_RESPONSABLE` = `UTILISATEUR`.`ID`\n";
					break;

				case 'D_OUVERTURE':
					$select .= "DATE_FORMAT(`D_OUVERTURE`, '%d/%m/%Y') AS `D_OUVERTURE`";
					$innerjoin .= "";
					break;

				default:
					$select .= "`".$col[$i]."`";
					break;

			}

			if ($i != count($col) - 1)
				$select .= ", \n\t\t";
			else 
				$select .= " \n";

		}

		/* 
		 *  Clause ORDER BY
		 * ----------------
		 */

		$orderby_sql = "ORDER BY ";
			
		for ($i=0; $i < count($order_by); $i++) { 

			$orderby_sql .= "`".$order_by[$i]."`";

			if ($i != count($orderby_sql))
				$orderby_sql .= ", ";
			else 
				$orderby_sql .= " ";

		}

		/*
		 * Clause WHERE
		 * ------------
		 */

		switch ($regroup) {

			case 'LIEU':
				$where = "WHERE `TICKET`.`NUM_LIEU` = ".$row['NUM_LIEU'];
				$num_regroup = $row['NUM_LIEU'];
				$nom_regroup = $row['NOM_LIEU'];
				break;

			case 'IMPORTANCE':
				$where = "WHERE `TICKET`.`NUM_IMPORTANCE` = ".$row['NUM_IMPORTANCE'];
				$num_regroup = $row['NUM_IMPORTANCE'];
				$nom_regroup = $row['NOM_IMPORTANCE'];
				break;

			case 'CATEGORIE':
				$where = "WHERE `TICKET`.`NUM_CATEGORIE` = ".$row['NUM_CATEGORIE'];
				$num_regroup = $row['NUM_CATEGORIE'];
				$nom_regroup = $row['NOM_CATEGORIE'];
				break;

			case 'STATUT':
				$where = "WHERE `TICKET`.`NUM_STATUT` = ".$row['NUM_STATUT'];
				$num_regroup = $row['NUM_STATUT'];
				$nom_regroup = $row['NOM_STATUT'];
				break;

			case 'RESPONSABLE':
				$row['ID_RESPONSABLE'] = empty($row['ID_RESPONSABLE']) ? "NULL" : $row['ID_RESPONSABLE'];
				$where = "WHERE `TICKET`.`ID_RESPONSABLE` = ".$row['ID_RESPONSABLE'];
				$num_regroup = $row['ID_RESPONSABLE'];
				$nom_regroup = $row['NOM_RESPONSABLE'];
				break;

			case 'D_OUVERTURE':
				$where = "WHERE DATE_FORMAT(`D_OUVERTURE`, '%m_%Y') = '".$row[$regroup]."'";
				$nom_regroup = $num_regroup = $row['D_OUVERTURE'];
				break;

			case 'AUCUN':
				$where = "";
				$nom_regroup = $num_regroup = 'Aucun regroupement';
				break;
			
			default:
				$where = "WHERE `TICKET`.`$regroup` = \"$row[$regroup]\"";
				$num_regroup = $row['$row[$regroup]'];
				$nom_regroup = $row['$row[$regroup]'];
				break;
		}

		switch ($action) {

			case 'attribuer':
				$where .= " AND `TICKET`.`NUM_STATUT` = 1";
				break;

			case 'encours':
				$where .= " AND (`TICKET`.`NUM_STATUT` = 2 OR `TICKET`.`NUM_STATUT` = 3)";
				if ($_SESSION['usr_connected']['classe'] == 3 )
				 	$where .= ' AND `ID_RESPONSABLE` = '.$_SESSION['usr_connected']['id'].'';
				break;

			case 'resolu':
				$where .= " AND `TICKET`.`NUM_STATUT` = 4";
				if ($_SESSION['usr_connected']['classe'] == 3 )
				 	$where .= ' AND `ID_RESPONSABLE` = '.$_SESSION['usr_connected']['id'].'';
				break;

			case 'historique':
				$where .= " AND `TICKET`.`NUM_STATUT` = 5";
				break;

		}

		/*
		 * Finalement,
		 * Assemblage de la requête
		 * ------------------------
		 */

		$sql = "$select"
			 . "FROM `TICKET`\n"
			 . "$innerjoin"
			 . "$where\n"
			 . "$orderby_sql $ordre;";

		// echo $sql;

		try {
			$res = $pdo->query($sql);
			$table_sql = $res->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $pdoe) {
			$_SESSION['msg'] = "Erreur interne&nbsp;:<br><pan>".$pdoe->getMessage()."</span>";
		}

		/* 
		 * En-tête tableau
		 * ---------------
		 */

		if (isset($row['D_OUVERTURE'])) {

			$mois = explode("_", $row['D_OUVERTURE']);

			$mois_lib = "";

			$i = 0;

			foreach ($mois as $row_mois) {

				switch ($row_mois) {

					case '01':
						$mois_lib = "Janvier";
						break;

					case '02':
						$mois_lib = "Février";
						break;

					case '03':
						$mois_lib = "Mars";
						break;

					case '04':
						$mois_lib = "Avril";
						break;

					case '05':
						$mois_lib = "Mai";
						break;

					case '06':
						$mois_lib = "Juin";
						break;

					case '07':
						$mois_lib = "Juillet";
						break;

					case '08':
						$mois_lib = "Août";
						break;

					case '09':
						$mois_lib = "Septembre";
						break;

					case '10':
						$mois_lib = "Octobre";
						break;

					case '11':
						$mois_lib = "Novembre";
						break;

					case '12':
						$mois_lib = "Décembre";
						break;

				}

				if ($i%2 == 1) {
					$mois_lib .= " " . $row_mois; 
				}

				$i++;
				
			}

			$summary .= '<li><a href="'."#".$num_regroup.'"><img src="../images/iconnav/r.png" alt="&lt;">'.$mois_lib.'</a></li>';

			$table = '<table id="'.$num_regroup.'">'
				   . '<caption>'.$mois_lib.'</caption>';

		} else {

			$summary .= '<li><a href="'."#".$num_regroup.'"><img src="../images/iconnav/r.png" alt="&lt;">'.$nom_regroup.'</a></li>';

			$table = '<table id="'.$num_regroup.'">'
				   . '<caption>'.$nom_regroup.'</caption>';

		}



			  

		$thead = '<thead>'
			   . '<tr>';

		foreach ($col as $cell) {
			if ($cell != 'DESCRIPTION') {

				switch ($cell) {
				
					case 'ID_RESPONSABLE':
						$cell = "Responsable";
						break;
						
					case 'D_OUVERTURE':
						$cell = "Date d'ouverture";
						break;
						
					case 'CATEGORIE':
						$cell = "Catégorie";
						break;
				
					default:
						$cell = ucfirst(strtolower($cell));
						break;
				}

				$thead .= "<th>".$cell."</th>";

			}

		}

		$thead .= '<th class="detail">Détails</th>';

		if ($action == 'encours' || $action == 'tous'|| $action == 'attribuer') {
			$thead .= '<th class="icoprint">'.'<img alt="Imprimer" src="../images/iconnav/print.png">'.'</th>';
		}

		$thead .= '</tr>'
				. '</thead>';


		/*
		 * Corp du tableau
		 * ---------------
		 */

		$tbody = '<tbody>';

		$i = 0;

		foreach ($table_sql as $row) {

			$ticketid = $row['ID_TICKET'];

			$description = $row['DESCRIPTION'];

			$tbody .= '<tr>';

			foreach ($row as $key => $value) {

				if ($key != 'DESCRIPTION' && $key != 'ID_TICKET')
					$tbody .= "<td>".$value."</td>";
				
			}

			$tbody .= '<td>'.'<a href="details_ticket_'.$action.'.php?ticketid='.$ticketid.'">'.'<img alt="Détails" src="../images/iconnav/fiche.svg">'.'</a>'.'</td>';

		if ($action == 'encours' || $action == 'tous') {
				$tbody .= '<td>'.'<input type="checkbox" class="cbox" name="fiche'.$ticketid.'" value="'.$ticketid.'">'.'</td>'; // value="'.$row['ticketid'].
			}
			
			$colspan = $action == 'encours' ? count($row) - 1 : count($row) - 2;

			$tbody .= '</tr>'
					. '<tr class="description">'
					. '<th>Description</th>'
					. '<td colspan="'.$colspan.'">'
					. $description
					. '</td>'
					. '</tr>';

		}

		$tbody .= '</tbody>';

		/*
		 * Assemblage du tableau
		 * ---------------------
		 */

		$table .= $thead . $tbody . '</table>';

		$liste .= $table;

	}

	$summary .= '</ul></nav>';

	$liste = $regroup == 'AUCUN' ? $liste : $summary.$liste;

	return $liste;
}

/**
 * Créé un formulaire pour que l'utilisateur puisse choisir les paramètres passé dans la fonction grouperTicket()
 * @param  string $action	Action de la page, si la page où la fonction est appellé est lister_ticket_encours.php, l'action sera "encours", etc
 * @param  array $get		Superglobale $_GET pour initialiser le formulaire au chargement de la page suivant le critère choisi
 * @return string			Renvoie le formulaire pour fonctionner avec la fonction grouperTicket()
 */
function getFormulaire($action, $get) {
	
	switch ($action) {
		case 'attribuer':
			$criteres = array("categorie", "lieu", "statut", "d_ouverture", "aucun");
			$criteres_label = array("Categorie", "Lieu", "Statut", "Date d'ouverture", "Ne pas regrouper");
			break;
		
		default:
			$criteres = array("responsable", "categorie", "lieu", "importance", "statut", "d_ouverture", "aucun");
			$criteres_label = array("Responsable", "Categorie", "Lieu", "Importance", "Statut", "Date d'ouverture", "Ne pas regrouper");
			break;
	}


	$form = '<form id="tri" method="get" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">'
		  . '<div>'
		  . '<div class="titre_filtre">Regrouper par</div>'
		  . '<!--';

	for ($i = 0; $i < count($criteres); $i ++) {

		$checked = $get['regroup'] == $criteres[$i] ? "checked" : "";

		$form.=	'--><span>'
			  . '<input type="radio" name="regroup" id="'.$criteres[$i].'" value="'.$criteres[$i].'" '.$checked.'>'
			  . '<label for="'.$criteres[$i].'">'.$criteres_label[$i].'</label>'
			  . '</span><!--';
	}

	$form.='-->'
		 . '</div>'
		 . '<input class="button" type="submit" value="Trier">'			
		 . '</form>';

	return $form;
}

?>