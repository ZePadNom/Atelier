<?php
/**
 * Description
 * @param type $importance 
 * @return type
 */
function getIconImportance($importance) {

	$importance = intval($importance);

	switch ($importance) {

		case 1:
			$nom_icone = "basse";
			break;

		case 2:
			$nom_icone = "moyenne";
			break;

		case 3:
			$nom_icone = "haute";
			break;

		case 4:
			$nom_icone = "urgente";
			break;

		default:
			$nom_icone = "nulle";
			break;
	}

	$icone = '<img src="../images/iconnav/'.$nom_icone.'.png" class="icon_imp">';

	return $icone;
}

?>