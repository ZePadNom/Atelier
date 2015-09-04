<?php
/**
 * 
 * @author Anthony Lozano (2015)
 *
 * @version  1.0.0
 */

if (!isset($_SESSION['usr_connected'])) {
	header('location: ' . $_SESSION['HTML_PATH'] . 'utilisateur_inconnu.php');
	exit;
}

/**
 * Met fin à la session et redirige l'utilisateur sur la page de connexion
 */
function deconnexion() {
	session_unset();
	session_destroy();
	header('Location: ' . $_SESSION['HTML_PATH'] . 'index.php');
}

?>