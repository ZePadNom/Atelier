<?php

function getBack($message, $lien) {

	$back = '<nav class="retour">'
		  . '<a href="'.$lien.'" title="Retour">'
		  . '<img src="../images/iconnav/back.png" alt="">'
		  . '<p>'.$message.'</p>'
		  . '</a>'
		  . '</nav>';

	return $back;
}

?>