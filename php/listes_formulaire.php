<?php
/**
 * En cours, permet de lister n'importe quel table simple de la base (importance, catégorie, lieu, ...)
 * @param  array
 * @param  string
 * @param  string
 * @param  string
 * @param  PDO
 * @return [type]
 */
function lister(array $colonnes, string $table, string $colonne_aselect, string $elt_aselect, PDO $pdo) {

	/*
	 * Création de la requête (colonnes)
	 * ---------------------------------
	 */

	$col_req = "";

	for ($i=0; $i < count($colonnes); $i++) {

		$col_req .= "`".$colonnes[$i];

		if ($i != count($colonnes))
			$col_req .= "`, ";
		else
			$col_req .= "` ";
	}

	/*
	 * Assemblage de la requête
	 * ------------------------
	 */

	$sql = "SELECT " . $cols . "\n"
		 . "FROM " . $table . "\n"
		 . "ORDER BY " . $colonne_aselect . "ASC"
		 . ";";

	$res = $pdo->query($sql);
	$table = $res->fetchAll(PDO::FETCH_ASSOC);

	$list = '<select name="'.$colonne_aselect.'">'."\n";
	foreach ($table as $row){

		$selected = "";

		if ($table_row["$colonne_aselect"] == $elt)
			$selected = 'selected';

		$liste .= '<option value="'.$table_row['ID_ETU'].'" '.$selected.">".$table_row['PRENOM_ETU'].' '.$table_row['NOM_ETU'].'</option>'."\n";
	}
	$liste_etu = $liste_etu.'</select>';
	return $liste_etu;
}

/**
 * Permet de créer une liste HTML (élément <select/>) contenant les colonnes de la table Importance
 * @param  int $num Permet de déterminé l'option de la liste pré-selectionné
 * @param  PDO $pdo Instance de l'objet PDO permettant la connexion à la base de données
 * @return string Retourne les éléments HTML
 */
function listerImportance($num, PDO $pdo) {

	$sql = "SELECT `NUM`, `NOM` FROM `IMPORTANCE` ORDER BY `NUM` ASC;";
	$res = $pdo->query($sql);
	$table = $res->fetchAll(PDO::FETCH_ASSOC);

	$liste = '<select name="importance">'."\n";
	foreach ($table as $row){

		$selected = "";

		if ($row['NUM'] == $num)
			$selected = 'selected';

		$liste .= '<option value="'.$row['NUM'].'" '.$selected.'>'
				. $row['NOM']
				. '</option>'."\n";
	}
	$liste .= '</select>';
	return $liste;
}

/**
 * Permet de créer une liste HTML (élément <select/>) contenant les colonnes de la table Lieu
 * @param  int $num Permet de déterminé l'option de la liste pré-selectionné
 * @param  PDO $pdo Instance de l'objet PDO permettant la connexion à la base de données
 * @return string Retourne les éléments HTML
 */
function listerLieu($num, PDO $pdo) {

	$sql = "SELECT `NUM`, `NOM` FROM `LIEU` ORDER BY `NOM` ASC;";
	$res = $pdo->query($sql);
	$table = $res->fetchAll(PDO::FETCH_ASSOC);

	$liste = '<select name="lieu">'."\n";
	foreach ($table as $row){

		$selected = "";

		if ($row['NUM'] == $num)
			$selected = 'selected';

		$liste .= '<option value="'.$row['NUM'].'" '.$selected.'>'
				. $row['NOM']
				. '</option>'."\n";
	}
	$liste .= '</select>';
	return $liste;
}

/**
 * Permet de créer une liste HTML (élément <select/>) contenant les colonnes de la table Categorie
 * @param  int $num Permet de déterminé l'option de la liste pré-selectionné
 * @param  PDO $pdo Instance de l'objet PDO permettant la connexion à la base de données
 * @return string Retourne les éléments HTML
 */
function listerCategorie($num, PDO $pdo) {

	$sql = "SELECT `NUM`, `NOM` FROM `CATEGORIE` ORDER BY `NOM` ASC;";
	$res = $pdo->query($sql);
	$table = $res->fetchAll(PDO::FETCH_ASSOC);

	$liste = '<select name="categorie">'."\n";
	foreach ($table as $row){

		$selected = "";

		if ($row['NUM'] == $num)
			$selected = 'selected';

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

	$sql = "SELECT `ID`, CONCAT(`NOM`, \" \", `PRENOM`) AS `NOM` FROM `UTILISATEUR` WHERE `NUM_CLASSE` = 2 OR `NUM_CLASSE` = 3 ORDER BY `NUM_CLASSE`, `NOM` ASC ;";
	$res = $pdo->query($sql);
	$table = $res->fetchAll(PDO::FETCH_ASSOC);

	$liste = '<select name="agent">'."\n";
	$liste .= '<option value="0">'."Non attribué".'</option>';
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
?>