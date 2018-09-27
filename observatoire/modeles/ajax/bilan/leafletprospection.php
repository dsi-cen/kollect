<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function mgrs($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT geo FROM referentiel.mgrs10 WHERE mgrs = :id ");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}

if(isset($_POST['choix']) && isset($_POST['id'])) 
{
	$id = htmlspecialchars($_POST['id']);
	$choix = htmlspecialchars($_POST['choix']);
	
	if ($choix == 'utm')
	{
		$utm = mgrs($id);
		$resultats = array('crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
		$feature = array('type' => 'Feature', 'geometry' => Null);
		$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => $utm['geo']);
		$resultats['features'][] = $feature;
		$tmpcarto = json_encode($resultats, JSON_NUMERIC_CHECK);
		$tmpcarto = str_replace('"[','[',$tmpcarto);
		$tmpcarto = str_replace(']"',']',$tmpcarto);
		$resultats = json_decode($tmpcarto);
		$retour['carto'] = $resultats;
		$retour['statut'] = 'Oui';
	}
	else
	{
		$xg = substr($id, 1, -4)*10000;
		$yb = substr($id, 5)*10000;
		$xd = $xg + 10000;
		$yh = $yb + 10000;
		$resultats = array('crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
		$feature = array('type' => 'Feature', 'geometry' => Null);
		$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
		$resultats['features'][] = $feature;
		$retour['carto'] = $resultats;
		$retour['statut'] = 'Oui';
	}	
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour, JSON_NUMERIC_CHECK);
?>