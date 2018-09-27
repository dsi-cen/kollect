<?php
$titre = 'Gestion des fiches';
$description = 'Gestion des fiches';
$scripthaut = '<script type="text/javascript" src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

$val4 = 'non'; $val5 = 'non'; $val6 = 'non';
$nban = 3;
$ca1 = null; $ca2 = null; $ca3 = null; $ca4 = null; $ca5 = null; $ca6 = null;
$la2 = null; $la3 = null; $la4 = null; $la5 = null; $la6 = null;
$a2 = null; $a3 = null; $a4 = null; $a5 = null; $a6 = null;

$valcarte = (isset($rjson_site['fiche']['cartefiche'])) ? $rjson_site['fiche']['cartefiche'] : '';
$bilan = (isset($rjson_site['fiche']['cartebilancouleur'])) ? $rjson_site['fiche']['cartebilancouleur'] : '';
$graphdebut = (isset($rjson_site['fiche']['graphdebut'])) ? $rjson_site['fiche']['graphdebut'] : 2000;
$canew = (isset($rjson_site['fiche']['legendenouv'])) ? $rjson_site['fiche']['legendenouv'] : '';
$alt = (isset($rjson_site['fiche']['alt'])) ? $rjson_site['fiche']['alt'] : '';

if (isset($rjson_site['fiche']['classefiche'])) 
{
	$nban = count($rjson_site['fiche']['classefiche']);
	foreach($rjson_site['fiche']['classefiche'] as $n)
	{
		if($n['classe'] == 'classe1')
		{
			$ca1 = $n['couleur'];
		}
		elseif($n['classe'] == 'classe2')
		{
			$ca2 = $n['couleur'];
			$la2 = $n['label'];
			$a2 = $n['annee'];
		}
		elseif($n['classe'] == 'classe3')
		{
			$ca3 = $n['couleur'];
			$la3 = $n['label'];
			$a3 = $n['annee'];;
		}
		elseif($n['classe'] == 'classe4')
		{
			$val4 = 'oui';
			$ca4 = $n['couleur'];
			$la4 = $n['label'];
			$a4 = $n['annee'];
		}
		elseif($n['classe'] == 'classe5')
		{
			$val5 = 'oui';
			$ca5 = $n['couleur'];
			$la5 = $n['label'];
			$a5 = $n['annee'];
		}
		elseif($n['classe'] == 'classe6')
		{
			$val6 = 'oui';
			$ca6 = $n['couleur'];
			$la6 = $n['label'];
			$a6 = $n['annee'];
		}
	}
}

include CHEMIN_VUE.'fiche.php';