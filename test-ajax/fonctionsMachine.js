// JavaScript Document
//##############################MOTEUR AJAX 1############################################
function jouer() {   
	 $.ajax({
        type: 'POST',
        url: 'gainAleatoire.php',
        data: "nom="+ $('#nom').val()+"&"+"prenom="+ $('#prenom').val(),
		dataType:'text',
        success: actualiserPage,
        error: function() {alert('Erreur serveur');}
              }); 
	 
  }
  function actualiserPage(reponse) {
	   //recup du résulat > tableau 
	   var nouveauResultat = reponse.split(":");
	   //actualisation du résultat
	   $('#resultat').html(nouveauResultat[1]);
	   $('#gagnant').html(nouveauResultat[0]);
	   //affiche la zone info
	   $('#info').css("visibility", "visible");
  }


$(document).ready(function() {
  $('#button').click(function () {jouer();});
  //gestion du bouton
  $('#button').ajaxStart(function(request, settings) { $(this).attr("disabled",true) });						  $('#button').ajaxStop(function(request, settings){ $(this).attr("disabled",false) });   
  //gestion du chargeur
  $('#charge').ajaxStart(function(request, settings) { $(this).css("visibility", "visible") });
  $('#charge').ajaxStop(function(request, settings){ $(this).css("visibility", "hidden") });
});
