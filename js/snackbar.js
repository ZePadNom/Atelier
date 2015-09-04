$(document).ready(function() {

	console.log("snackbar.js chargé correctement.")

	$('#snackbar').slideDown('slow');

	$('#snackbar').click(function() {
		$(this).slideUp('fast');
	});

	// $('#snackbar').delay(7000).slideUp('slow');


});