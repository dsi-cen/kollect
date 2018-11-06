<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	
function carto93()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT codel93 FROM referentiel.maillel93 ") or die(print_r($bdd->errorInfo()));
	$carto93 = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto93;
}
$carto93 = carto93();
$geo = array('type'=> 'FeatureCollection','crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
foreach ($carto93 as $n)
{
	$xg = substr($n['codel93'], 1, -4)*10000;
	$yb = substr($n['codel93'], 5)*10000;
	$xd = $xg + 10000;
	$yh = $yb + 10000;
		
	$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
	$feature['properties']['id'] = $n['codel93'];
	$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
	$geo['features'][] = $feature;
}
$retour['statut'] = 'Oui';
$retour['carto'] = $geo;
echo json_encode($retour, JSON_NUMERIC_CHECK);