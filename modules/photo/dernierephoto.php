<?php
$scripthaut = '<script src="dist/js/jquery.js"></script>';
$script = '<script src="dist/js/bootstrap.min.js" defer></script>
<script src="dist/js/popup-image.js" defer></script>
<script src="dist/js/isotope.js" defer></script>';
$css = '<link rel="stylesheet" href="dist/css/popup.css" type="text/css">';
$titre = 'Dernières photos';
$description = 'Les dernières photos déposées '.$rjson_site['titre']; 

include CHEMIN_MODELE.'photo.php';

$liste = quarante();

if(count($liste) > 0)
{
	foreach($liste as $n)
	{
		$tabobserva[] = $n['observatoire'];	
	}
	$filtre = array_unique($tabobserva);
	$filtre = array_flip($filtre);
	foreach($rjson_site['observatoire'] as $n)
	{
		if(isset($filtre[$n['nomvar']]))
		{
			$tabfiltre[] = ['observa'=>$n['nomvar'],'nom'=>$n['nom']];
		}	
	}
}

include CHEMIN_VUE.'dernierephoto.php';