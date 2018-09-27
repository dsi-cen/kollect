<?php
$titre = 'Recherche sur carte';
$description = 'Recherche sur carte sur le site '.$rjson_site['titre'];
$script = '<script src="dist/js/jquery.js" defer></script>
<script src="dist/js/bootstrap.min.js" defer></script>
<script src="dist/js/leafletpj4.js" defer></script>
<script src="dist/js/leafletMarkerCluster.js" defer></script>
<script src="dist/js/consultcarto.js" defer></script>';
$css = '<link rel="stylesheet" href="dist/css/leaflet.css" />
<link rel="stylesheet" href="dist/css/leafletMarkerCluster.css" />';

if(isset($_SESSION['idmembre']))
{
	$obser = (!empty($_SESSION['obser'])) ? $_SESSION['obser'] : 'aucun';
	
	include CHEMIN_VUE.'consultcarto.php';
}