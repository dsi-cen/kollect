<?php
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>
<script src="../dist/js/popup-image.js" defer></script>
<script src="../dist/js/isotope.js" defer></script>';
$css = '<link rel="stylesheet" href="../dist/css/popup.css" type="text/css">';
$titre = $nomd.' Dernières photos';
$description = 'Les dernières photos déposées '.$rjson_obser['titre']; 

include CHEMIN_MODELE.'photo.php';

$liste = quarante($nomvar);

if(count($liste) > 0)
{
	foreach($liste as $n)
	{
		$tabobser[] = ['idobser'=>$n['idobser'], 'nom'=>$n['prenom'].' '.$n['nom']];	
	}
	$tabtmp = array_map( 'serialize' , $tabobser );
	$tabtmp = array_unique($tabtmp);
	$filtre = array_map( 'unserialize' , $tabtmp );
}

include CHEMIN_VUE.'dernierephoto.php';