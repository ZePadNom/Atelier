<?php
if (isset($snackbar)) {
	$snackbar = $snackbar;
	echo snackbar($snackbar);
	unset($snackbar);
}
if (isset($_SESSION['msg'])) {
	$snackbar = $_SESSION['msg'];
	echo snackbar($snackbar);
	unset($_SESSION['msg']);
}
?>
<footer>
	<div id="innerFooter">
		<p>Lycée Merleau-Ponty - 2015</p>
	</div>
</footer>
