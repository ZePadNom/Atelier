<?php 
/**
 * Cette page contient les éléments HTML qui seront placé dans l'élement <head> des pages.
 *
 * @author Anthony Lozano (2015)
 * @version  1.0.1
 */

$title = " - Atelier";

?>
<meta charset="UTF-8">
<link rel="icon" href="/atelier_v2/favicon.png" type="image/png" sizes="32x32">
<link rel="stylesheet" media="screen" type="text/css"  href="<?php echo $_SESSION['HTML_PATH']; ?>css/header.css">
<link rel="stylesheet" media="screen" type="text/css" href="<?php echo $_SESSION['HTML_PATH']; ?>css/style.css">
<link rel="stylesheet" media="print" type="text/css" href="<?php echo $_SESSION['HTML_PATH']; ?>css/print.css">
<script type="text/javascript" src="<?php echo $_SESSION['HTML_PATH']; ?>js/jquery-1.11.2.js"></script>