<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function recherche_compoly($codecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT poly, geojson FROM referentiel.commune WHERE codecom = :codecom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':codecom', $codecom);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['codecom']))
{
	$codecom = $_POST['codecom'];

	$coord = recherche_compoly($codecom);
	$resultats = array('crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
	$feature = array('type' => 'Feature', 'geometry' => Null);
	//$feature['geometry'] = array('type' => $coord['poly'], 'coordinates' => $coord['geojson']);
	$feature['geometry'] = array('type' => 'MultiLineString', 'coordinates' => $coord['geojson']);
	$resultats['features'][] = $feature;
	$tmpcarto = json_encode($resultats, JSON_NUMERIC_CHECK);
	$tmpcarto = str_replace('"[','[',$tmpcarto);
	$tmpcarto = str_replace(']"',']',$tmpcarto);
	$resultats = json_decode($tmpcarto);
	$retour['carto'] = $resultats;

	$retour['statut'] = 'Oui';
	
	echo json_encode($retour, JSON_NUMERIC_CHECK);
}
?>