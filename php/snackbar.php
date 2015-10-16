<?php
/**
 * Page permettant d'afficher un message d'information "snack bar"
 *
 * @author Anthony Lozano (2015)
 *
 * @version  0.1.0
 */

/**
 * Permet d'afficher une snackbar comportand un message
 * @param  string $message Message à placer dans la snackbar
 * @return string Renvoie les éléments HTML permettant d'afficher la snackbar	
 */

function snackBar($message){

	$snackbar = '<div id="snackbar">'."\n\t"
			  . '<div id="snack">'."\n\t\t"
			  . '<p>'." Message d'information".'</p>'."\n\t"
			  . "</div>"."\n\t"
			  . '<div id="bar">'."\n\t\t"
			  . '<p>'.$message.'</p>'."\n\t\t"
			  . "</div>"."\n\t"
			  . "</div>"."\n";

	$snackbar .= '<script type="text/javascript" src="/atelier_v2/js/snackbar.js"></script>';
	return $snackbar;
}

?>