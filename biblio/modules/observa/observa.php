<?php
$titre = 'Recherche par observatoire';
$description = 'Recherche de référence bibliographique par observatoire';
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

include CHEMIN_MODELE.'recherche.php';

$observa = recherche_observa();

foreach($observa as $n)
{
	$tabnb[$n['observa']] = $n['nb'];
	$tabobserva[$n['observa']] = $n['observa'];
}

foreach($rjson_site['observatoire'] as $n)
{
	if(isset($tabobserva[$n['nomvar']]))
	{
		$nbref = $tabnb[$n['nomvar']];
	}
	else
	{
		$nbref = 0;
	}
	$tab[] = ['observa'=>$n['nom'],'nb'=>$nbref,'icon'=>$n['icon'],'var'=>$n['nomvar'],'couleur'=>$n['couleur']];
}

include CHEMIN_VUE.'observa.php';