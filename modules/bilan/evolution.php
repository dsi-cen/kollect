<?php
$titre = 'Evolution des données';
$description = '';
$scripthaut = '<script src="dist/js/jquery.js"></script>';
$script = '<script src="dist/js/bootstrap.min.js" defer></script>
<script src="dist/js/highcharts.js" defer></script>
<script src="dist/js/modules/exportingoff.js" defer></script>';
$css = '';

include CHEMIN_MODELE.'prospection.php';

$anneeune = (isset($rjson_site['fiche']['graphdebut'])) ? $rjson_site['fiche']['graphdebut'] : 2000;
$annéeactuelle = date('Y');
for($i=$anneeune;$i <= $annéeactuelle;$i++) 
{ 
	$annee[] = $i;	 
} 
$nbligne = (count($annee) > 25) ? 2 : 1;
$nbobs = nbobservation($anneeune);
foreach($nbobs as $n)
{
	$tabannee[] = $n['annee'];
}
$tabannee = array_flip($tabannee);
foreach($annee as $a)
{
	if(isset($tabannee[$a]))
	{
		foreach($nbobs as $n)
		{
			if($n['annee'] == $a)
			{
				$nb[] = $n['nb'];
			}			
		}
	}
	else
	{
		$nb[] = 0;
	}
}

include CHEMIN_VUE.'evolution.php';