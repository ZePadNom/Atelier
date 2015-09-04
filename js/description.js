$(document).ready(function() {
	console.log("Script description.js ouvert");

	$(this).parent().next('.description')

	$('table td:not(:last-child)').click(function(){
		console.log('Click!');

		var desc = $(this).parent().next('.description');

		if (desc.is(':visible')) {
			desc.hide('fast');
		} else {
			desc.show('fast');
		}
	})


	// $('.icoprint').on('click', function () {
	// 	console.log('.icoprint click!');

	// 	$(this).parents('td').css('background-color', '#FF0000');

	// });

});