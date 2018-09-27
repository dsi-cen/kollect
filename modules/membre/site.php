<?php
if(isset($_SESSION['prenom']))
{
	$titre = 'Gestion site de '.$_SESSION['prenom'].'';
	$description = 'Gestion des sites de '.$_SESSION['prenom'].' '.$_SESSION['nom'].'.';
	$script = '<script src="dist/js/jquery.js" defer></script>
	<script src="dist/js/bootstrap.min.js" defer></script>
	<script src="dist/js/leaflet.js" defer></script>
	<script src="dist/js/jquery-auto.js" defer></script>
	<script src="dist/js/sitem.js" defer></script>';
	$css = '<link rel="stylesheet" href="dist/css/leaflet.css" />';
		
	include CHEMIN_MODELE.'saisie.php';
	
	$cherchereobseridm = rechercheobservateurid($_SESSION['idmembre']);
	$idobser = ($cherchereobseridm['idobser'] != '') ? $cherchereobseridm['idobser'] : 'non';
	
	include CHEMIN_VUE.'site.php';
}
else
{
	header('location:index.php');
}