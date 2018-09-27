<?php
$json_emprise = file_get_contents('../emprise/emprise.json');
$rjson_emprise = json_decode($json_emprise, true);

$titre = 'Gestion carto';
$description = 'Gestion carto du site';
$script = '<script type="text/javascript" src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>
<script src="../dist/js/leafletpj4.js" defer></script>
<script src="dist/js/carto.js" defer></script>';
$css = '<link rel="stylesheet" href="../dist/css/leaflet.css" />';

$cleign = (isset($rjson_emprise['cleign'])) ? $rjson_emprise['cleign'] : '';
$couche = (isset($rjson_emprise['couche'])) ? $rjson_emprise['couche'] : 'osm';
$proche = (isset($rjson_emprise['proche'])) ? $rjson_emprise['proche'] : '';
$l935 = (isset($rjson_emprise['nbmaille5'])) ? $rjson_emprise['nbmaille5'] : $rjson_emprise['nbmaille'] * 4;
$lambert5 = $rjson_emprise['lambert5'];
$color = $rjson_emprise['stylecontour']['color'];
$weight = $rjson_emprise['stylecontour']['weight'];
$opacity = $rjson_emprise['stylecontour']['opacity'];
$colorm = $rjson_emprise['stylemaille']['color'];
$weightm = $rjson_emprise['stylemaille']['weight'];
$opacitym = $rjson_emprise['stylemaille']['opacity'];
$color2 = $rjson_emprise['stylecontour2']['color'];
$weight2 = $rjson_emprise['stylecontour2']['weight'];

$filecontour = '../emprise/contour.geojson';
if (file_exists($filecontour))
{
	$taille = filesize($filecontour);
	if ($taille >= 1048576) 
	{
		$taille = round($taille/1048576 * 100)/100 . ' Mo'; 
		$taille1 = round($taille/1048576 * 100)/100;
	}
	elseif ($taille >= 1024) 
	{ 
		$taille = round($taille/1024) . ' Ko'; 
		$taille1 = round($taille/1024);
	}
	if ($taille1 >= 500)
	{
		$infocontour = '<p class="text-danger">Votre fichier <b>contour.geojson</b> fait '.$taille.' Il est fortement conseillé de le réduire.</p>';
	}
	elseif ($taille1 >= 350 and $taille1 < 500)
	{
		$infocontour = '<p class="text-warning">Votre fichier <b>contour.geojson</b> fait '.$taille.' Il est conseillé de le réduire.</p>';
	}
	elseif ($taille1 < 350)
	{
		$infocontour = '<p class="text-success">Votre fichier <b>contour.geojson</b> fait '.$taille.'.</p>';
	}	
}

include CHEMIN_VUE.'carto.php';