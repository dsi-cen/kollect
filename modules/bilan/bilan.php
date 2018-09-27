<?php
$titre = 'Bilan prospection';
$description = '';
$script = '<script src="dist/js/jquery.js" defer></script>
<script src="dist/js/bootstrap.min.js" defer></script>
<script src="dist/js/highcharts.js" defer></script>
<script src="dist/js/modules/map.js" defer></script>
<script src="dist/js/modules/exportingoff.js" defer></script>
<script src="dist/js/leafletpj4.js" defer></script>
<script src="dist/js/bilan.js?'.filemtime('dist/js/bilan.js').'" defer></script>';
$css = '<link rel="stylesheet" href="dist/css/leaflet.css" />';
//<script src="dist/js/bilan.js?'.filemtime('dist/js/bilan.js').'" defer></script><script src="src/js/bilan.js" defer></script>
$json_emprise = file_get_contents('emprise/emprise.json');
$emprise = json_decode($json_emprise, true);

$maxcolor = (isset($rjson_site['fiche']['cartebilancouleur'])) ? $rjson_site['fiche']['cartebilancouleur'] : '#3A9D23';
$choixcarte = (isset($rjson_site['fiche']['cartefiche'])) ? $rjson_site['fiche']['cartefiche'] : 'commune';
$cartemaille = ($emprise['utm'] == 'oui') ? 'Carte maille UTM 10x10 km' : 'Carte Lambert93 10x10 km';
if($emprise['emprise'] != 'fr')
{
	if($choixcarte == 'commune') 
	{
		$titrecarte = 'Nombre d\'espèces par commune';
	}	
	else
	{
		$titrecarte = ($emprise['utm'] == 'oui') ? 'Nombre d\'espèces par maille UTM' : 'Nombre d\'espèces par maille 10 x 10';
	}	
	$cartecom = 'Carte communale';
	$value = 'commune';
	$cartemaille5 = ($emprise['lambert5'] == 'oui') ? 'Carte Lambert93 5x5 km' : 'non';
}
else
{
	if($choixcarte == 'commune') 
	{
		$titrecarte = 'Nombre d\'espèces par département';
		$choixcarte = 'dep';
	}	
	else
	{
		$titrecarte = ($emprise['utm'] == 'oui') ? 'Nombre d\'espèces par maille UTM' : 'Nombre d\'espèces par maille 10 x 10';
	}	
	$cartecom = 'Carte départementale';
	$value = 'dep';
	$cartemaille5 = 'non';
}		
$titregraph = 'Nombre d\'espèces et d\'observations par observatoire';

include CHEMIN_VUE.'bilan.php';