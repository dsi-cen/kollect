<?php
$titre = 'Dernières observations';
$description = 'Les dernières observations sur le site '.$rjson_site['titre'];
$script = '<script src="dist/js/jquery.js" defer></script>
<script src="dist/js/bootstrap.min.js" defer></script>
<script src="dist/js/leaflet.js" defer></script>
<script src="dist/js/popup-image.js" defer></script>
<script src="dist/js/observation.js?'.filemtime('dist/js/observation.js').'" defer></script>';
$css = '<link rel="stylesheet" href="dist/css/leaflet.css" />
<link rel="stylesheet" href="dist/css/popup.css" type="text/css">';
//<script src="dist/js/observation.js?'.filemtime('dist/js/observation.js').'" defer></script><script src="src/js/observation.js" defer></script>

if(isset($_GET['d']))
{
	$obser = $_GET['d'];
}
else
{
	$obser = (!empty($_SESSION['obser'])) ? $_SESSION['obser'] : 'aucun';
}

$json_emprise = file_get_contents('emprise/emprise.json');
$rjson_emprise = json_decode($json_emprise, true);

$dep = ($rjson_emprise['emprise'] == 'fr' || $rjson_emprise['contour2'] == 'oui' ) ? 'oui' : 'non';
//$mt5 = (isset($rjson_emprise['nbmaille5'])) ? $rjson_emprise['nbmaille5'] : 'non';

include CHEMIN_VUE.'observation.php';