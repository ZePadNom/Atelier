<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (isset($_POST['suppr'])) {
		
		$id_ticket = $_GET['ticketid'];

		supprimer($id_ticket, $bdd);

	}
}
/**
 * Permet d'obtennir le formulaire de suppression du ticket 
 * @param  int $id_usr	Identifiant de l'utilisateur, permet de déterminer s'il a accès à ce formulaire ou non
 * @return string		Retourne mes éléments HTML permettant d'afficher le formulaire de suppression d'un ticket
 */
function getSupprimer($id_usr) {

	$sup = "";

	if ($id_usr == 1 /*|| $id_usr == 2*/) {

	$sup = '<div class="sup">'
		 . '<h2>'."Supprimer un ticket".'</h2>'
		 . '<p>'."En cliquant sur ce bouton vous supprimerez définitivement ce ticket. <b>Attention</b>, cet action est irréversible et le ticket ne pourra pas être récupérer.".'</p>'
		 . '<form method="post" onsubmit="return confirm(\'Voulez vous réellement supprimer ce ticket ?\');">'
	 	 . '<input type="submit" class="button details" name="suppr" value="Supprimer le ticket">'
		 . '</form>'
		 . '</div>';
	}

	return $sup;
}

/**
 * Permet de supprimer un ticket
 * @param  int $id_ticket	Identifiant du ticket à supprimer
 * @param  PDO $pdo			Instance de l'objet PDO permettant de dialoguer avec la base de donnée
 */
function supprimer($id_ticket, PDO $pdo) {

	$sql = "DELETE FROM `COMMENTAIRE` WHERE `ID_TICKET` = $id_ticket;";
	$pdo->exec($sql);

	$sql = "DELETE FROM `EVOLUTION` WHERE `ID_TICKET` = $id_ticket;";
	$pdo->exec($sql);

	$sql = "DELETE FROM `TICKET` WHERE `ID` = $id_ticket;";
	$pdo->exec($sql);

	$_SESSION['msg'] = "Le ticket à correctement été supprimer";

	unset($_GET);

	header('location: /Atelier/accueil.php');

}

?>