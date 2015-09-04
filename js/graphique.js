  console.log('graphique.js ouvert')

/**
 * Permet de dessiner un diagramme circulaire
 * @param  {object} ctx     Conexte du canvas, nécéssaire pour dessiner
 * @param  {int} largeur    Largeur du canvas
 * @param  {int} hauteur    Hauteur du canvas
 * @param  {array} donnees  Tableau des données pour dessiner les secteurs
 */
function graph(ctx, largeur, hauteur, donnees) {
  console.log('graph() appelé')

  /**
   * @var {int} rayon Rayon du cercle, -5 pour ne pas qu'il dépasse du canvas
   * @var {int} posx_centre Valeur de la position du centre du canvas sur l'axe horizontal (x)
   * @var {int} posy_centre Valeur de la position du centre du canvas sur l'axe vertical (y)
   * @var {array} couleurs Tableau des couleurs pour les différents secteurs
   */
  var rayon = hauteur / 2 - 5;
  var posx_centre = largeur / 2;
  var posy_centre = hauteur / 2;
  var total = 0;
  var couleurs = ['#2196F3', '#FFEB3B', '#607D8B', '#4CAF50'];

  /*
   * Détermination du total de la somme des valeurs
   */
  for (i = 0; i < donnees.length; i++) {
    total += donnees[i];
  };

  var derniere_part = 0;

  // On déplace le départ à midi
  var quart_cercle = Math.PI / 2;

  /*
   * Si aucune données, on affiche un message
   * Sinon, on affiche le diagramme
   */
  if (total == 0) {
    console.log("Aucune données !");

    ctx.fillText('Aucune données !', posx_centre - 15, posy_centre + 10);
    ctx.font = "30px Consolas";

  } else {
    console.log("il y a des données, on fait une tarte ... Hmmm !")

    for (i = 0; i < donnees.length; i++) {

      ctx.beginPath();

      ctx.fillStyle = couleurs[i];

      ctx.moveTo(posx_centre, posy_centre);

      var part = Math.PI * (2 * donnees[i] / total);
      ctx.arc(posx_centre, posy_centre, rayon, derniere_part - quart_cercle, derniere_part + part - quart_cercle, false);

      ctx.lineTo(posx_centre, posy_centre);

      ctx.fill();
      ctx.closePath();
      derniere_part += part;

    };
  };
};

/**
 * context.arc(x, y, r, sAngle, eAngle, counterclockwise);
 * -------------------------------------------------------
 * @param {int} x Coordonné horizontale du centre de l'arc de cercle (pixels)
 * @param {int} y Coordonné vertical du centre de l'arc de cercle (pixels)
 * @param {int} r Rayon du cercle (pixels)
 * @param {radians} sAngle Angle de départ de l'arc de cercle
 * @param {radians} eAngle Angle d'arrivé de l'arc de cercle
 * @param {boolean} counterclockwise Sens de rotation (si vrai -> sens anti-horraire, sinon sens horraire)
 */