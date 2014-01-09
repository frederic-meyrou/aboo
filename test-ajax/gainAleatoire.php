<?php
//indique que le type de la réponse renvoyée au client sera du Texte
header("Content-Type: text/plain ; charset=utf-8");
//anti Cache pour HTTP/1.1
header("Cache-Control: no-cache , private");
//anti Cache pour HTTP/1.0
header("Pragma: no-cache");
//simulation du  temps d'attente du serveur (2 secondes)
sleep(2);
//recuperation du parametre nom
if(isset($_REQUEST['nom'])) $nomJoueur=$_REQUEST['nom'];
else $nomJoueur="inconnu";
//recuperation du parametre nom
if(isset($_REQUEST['prenom'])) $prenomJoueur=$_REQUEST['prenom'];
else $prenomJoueur="inconnu";
//calcul du nouveau gain entre 0 et 100 Euros
$gain =  rand(0,100);
//concaténation du nom
$nom=$prenomJoueur." ".$nomJoueur;
//mise en forme le résultat avec le nom 
$resultat=$nom.':'.$gain;
//envoi de la réponse à la page HTML
echo $resultat ;
?>