<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	
function mgrs()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT mgrs, geo FROM referentiel.mgrs10 ") or die(print_r($bdd->errorInfo()));
	$cartomgrs = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $cartomgrs;
}
$cartomgrs = mgrs();
$geo = array('type'=> 'FeatureCollection','crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
foreach ($cartomgrs as $n)
{
	$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
	$feature['properties']['id'] = $n['mgrs'];
	$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => $n['geo']);
	$geo['features'][] = $feature;
}
$tmpjson = json_encode($geo, JSON_NUMERIC_CHECK);
$tmpjson = str_replace('"[','[',$tmpjson);
$tmpjson = str_replace(']"',']',$tmpjson);
$geo = json_decode($tmpjson);

$retour['statut'] = 'Oui';
$retour['carto'] = $geo;
echo json_encode($retour, JSON_NUMERIC_CHECK);