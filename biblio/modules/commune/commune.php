<?php
$titre = 'Recherche par commune';
$description = 'Recherche de référence bibliographique par commune';
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>
<script type="text/javascript" src="../dist/js/highmaps.js" defer></script>';
$css = '';

include CHEMIN_MODELE.'recherche.php';

$lettre = recherche_commune();

$com = cartocommune();

foreach($com as $n)
{
	if($n['nb'] > 0)
	{
		$info = ($n['nb'] == 1) ? $n['nb']. ' référence' : $n['nb'].' références.';
		$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
		$feature['properties']['id'] = $n['id'];
		$feature['geometry'] = ['type' => $n['poly'], 'coordinates' => $n['geojson']];
		$resultats['features'][] = $feature;
		$carte[] = ['nom'=>$n['emp'], 'id'=>$n['id'], 'value'=>$n['nb'], 'info'=>$info];
		$tabnb[] = $n['nb'];
	}
	else
	{
		$info = 'Aucune référence';
		$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
		$feature['properties']['id'] = $n['id'];
		$feature['geometry'] = ['type' => $n['poly'], 'coordinates' => $n['geojson']];
		$resultats['features'][] = $feature;
		$carte[] = ['nom'=>$n['emp'], 'id'=>$n['id'], 'value'=>$n['nb'], 'info'=>$info];
	}
	unset($com);
	$tmpcarto = json_encode($resultats, JSON_NUMERIC_CHECK);
	$tmpcarto = str_replace('"[','[',$tmpcarto);
	$tmpcarto = str_replace(']"',']',$tmpcarto);
	$carto = json_decode($tmpcarto);
}
$max = max($tabnb);


include CHEMIN_VUE.'commune.php';