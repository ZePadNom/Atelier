$(document).ready(function() {
	
	function cl(message) {	console.log(message);	};
	
	cl('Script trier.js ouvert');


	function DESC(a, b) {
	
	a = a[1];
	b = b[1];
	
	if(a > b)
	      return -1;
	
	if(a < b)
	      return 1;
	
	return 0;

	} 
	 
	function ASC(a, b) {
	a = a[1];
	b = b[1];
	if(a > b)
	      return 1;

	if(a < b)
	      return -1;

	   return 0;
	 }
	 

	/**
	 * Tri du contenu d'un tableau en fonction d'une colonne et d'un ordre croissant/décroissant choisi
	 * 
	 * @param id_tableau string Identifiant DOM du tableau où l'on va effecuter le tri
	 * @param colonne int Index de la colonne à trier (0, 1, 2 ...)
	 * @param ordre function Ordre de tri, croissant ou décroissant (ordre = ASC || ordre = DESC)
	 */
	function trierTableau(id_tableau, colonne, ordre) {
		cl('trierTableau(){' + id_tableau + ', ' + colonne + ', ' + ordre + '} appelé !')

		var tableau = $('#' + id_tableau);
		cl(tableau)


		var lignes = tableau.children('tbody tr')/*.not('.description')*/;
		cl(lignes);

		$.each(lignes, function(index, val) {
		    console.log(val.category);
		});

		lignes.css('background-color', '#FF0000')


		var trieur = new Array();

		trieur.length = 0;

		for (var i = lignes.length - 1; i >= 0; i--) {
			trieur.add([lignes[i], $(lignes[i] + 'td')[colonne].html()]);
			cl('Woaw ! ' + i)
		};
			

		trieur.sort(ordre);

		var j = -1;
		while(++j) {
			tableau.appendChild(sorter[j][0]);
		}

	};

	// trierTableau('01_2015', 2, ASC);

	var toggler = true;

	$('th').click(function() {
		var id_tableau = $(this).parents('table').attr('id');
		var colonne = $('th').index(this);

		var ordre = (toggler ? "ASC" : "DESC");
		
		trierTableau(id_tableau, colonne, ordre);

		toggler = !toggler;
	});

});