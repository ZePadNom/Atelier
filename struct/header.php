<?php 
/**
 * En-tête
 *
 * @author Emilie Graton (V. 1 - 2012)
 * @author Anthony Lozano (2015)
 *
 * @version  2.0.4
 */



/**
 * Créé une icône de navigation (iône + label)
 * @param string $label Label placé sous l'icône
 * @param string $icone Nom de l'icône se trouvant dans /atelier_v2/images/iconnav/
 * @param string $action Chemin (lien) vers la page désirée
 * @return string Eléments HTML permettant d'afficher l'icône de navigation (à appeler dans l'en-tête)
 */
function createIconnav($label, $icone, $action) {
	$iconnav = '<li class="iconnav">'."\n"
			 . '<a href="'.$action.'">'."\n"
			 . '<img src="/atelier_v2/images/iconnav/'.$icone.'" alt="'.$label.'">'."\n"
			 . '<span>'.$label.'</span>'."\n"
			 . '</a>'."\n"
			 . '</li>';
	return $iconnav;
}

$nom_header = $_SESSION['usr_connected']['nom'];
$prenom_header = $_SESSION['usr_connected']['prenom'];
$tdc = $_SESSION['usr_connected']['tdc'];



/**
 * @var int $classe Définit la classe de l'utilisateur, afin de déterminer les éléments du menu à afficher
 * $classe == 1 -> Administrateur
 * $classe == 2 -> Agent chef
 * $classe == 3 -> Agent
 */
if (isset($_SESSION['usr_connected']['classe'])) {
	$classe = $_SESSION['usr_connected']['classe'];
} else {
	$classe = 0;
}

// Création des icones
// -------------------

$menu = "";

if($classe == 1) {

	// Tickets
	$menu = '<div class="vr">'
		  . '<span class="h">'."Tickets".'</span>'
		  . createIconnav("Créer", "add.png", "/atelier_v2/ticket/creer_ticket.php")
		  . createIconnav("Attribuer", "help.png", "/atelier_v2/ticket/lister_ticket_attribuer.php")
		  . createIconnav("En cours", "curr.svg", "/atelier_v2/ticket/lister_ticket_encours.php")
		  . createIconnav("Résolu", "check.png", "/atelier_v2/ticket/lister_ticket_resolu.php")
		  . createIconnav("Tous", "all.png", "/atelier_v2/ticket/lister_ticket_tous.php")
		  . '</div>';

	// Historique
	$menu.= '<div class="vr">'
		  . '<span class="h">'."&nbsp".'</span>'
		  . createIconnav("Historique", "histo.svg", "/atelier_v2/historique/lister_ticket_historique.php")
		  . '</div>';
		  
	// Admin
	$menu.= '<div class="vr">'
		  . '<span class="h">'."Administration".'</span>'
		  . createIconnav("Utilisateurs", "pers.svg", "/atelier_v2/administration/utilisateurs.php")
		  . createIconnav("Lieux", "place.png", "/atelier_v2/administration/lieu.php")
		  . createIconnav("Catégories", "cat.png", "/atelier_v2/administration/categorie.php")
		  . createIconnav("Purger données", "stor.png", "/atelier_v2/administration/purger_bdd.php")
		  . '</div>';

} elseif($classe == 2){
					
	// Tickets	
	$menu = '<div class="vr">'
		  . '<span class="h">'."Tickets".'</span>'
		  . createIconnav("Créer", "add.png", "/atelier_v2/ticket/creer_ticket.php")
		  . createIconnav("Attribuer", "help.png", "/atelier_v2/ticket/lister_ticket_attribuer.php")
		  . createIconnav("En cours", "curr.svg", "/atelier_v2/ticket/lister_ticket_encours.php")
		  . createIconnav("Résolu", "check.png", "/atelier_v2/ticket/lister_ticket_resolu.php")
		  . createIconnav("Tous", "all.png", "/atelier_v2/ticket/lister_ticket_tous.php")
		  . '</div>';

	// Historique
	$menu.= '<div class="vr">'
		  . '<span class="h">'."&nbsp".'</span>'
		  . createIconnav("Historique", "histo.svg", "/atelier_v2/historique/lister_ticket_historique.php")
		  . '</div>';

} elseif ($classe == 3) {

	// Tickets	
	$menu = '<div class="vr">'
		  . '<span class="h">'."Tickets".'</span>'
		  . createIconnav("En cours", "curr.svg", "/atelier_v2/ticket/lister_ticket_encours.php")
		  . createIconnav("Résolu", "check.png", "/atelier_v2/ticket/lister_ticket_resolu.php")
		  . createIconnav("Tous", "all.png", "/atelier_v2/ticket/lister_ticket_tous.php")
		  . '</div>';

}




// Message de bienvenue
// --------------------

$welcome_usr = "Bienvenue ".$prenom_header." ".$nom_header;
$welcome_type = "Vous êtes connecté en tant qu'".$tdc;

?>
<header>
	<div id="entete">
		<div class="logo"></div><!--
	 --><div class="welcome_msg">
			<span>
				<?php echo $welcome_usr; ?>
			</span>
			<span>
				<?php echo $welcome_type; ?>
			</span>
		</div><!--
	 --><nav class="welcome_nav">
			<ul>
				<li class="iconnav_top"><a href="/atelier_v2/accueil.php" title="Retour à l'accueil"><img src="/atelier_v2/images/iconnav/home.png" alt="Accueil"><span>Accueil</span></a></li><!--
			 --><li class="iconnav_top"><a href="/atelier_v2/php/t_deconnexion.php" title="Fin de la session"><img src="/atelier_v2/images/iconnav/exit.png" alt="Déconnexion"><span>Déconnexion</span></a></li>
			</ul>
		</nav>
	</div>
	<hr>

	<nav id="menu">
		<ul>
			<?php echo $menu; ?>
		</ul>
	</nav>
</header>