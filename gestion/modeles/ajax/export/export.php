<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
session_start();
function obs($cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT x, y, site FROM obs.coordonnee
						INNER JOIN obs.fiche USING(idcoord)
						INNER JOIN obs.obs USING(idfiche)
						LEFT JOIN obs.site ON site.idsite = fiche.idsite
						WHERE cdref = :cdnom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdnom, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
if (isset($_POST['cdnom']))
//if (isset($_GET['cdnom']))
{
	$cdnom = $_POST['cdnom'];
	//$cdnom = $_GET['cdnom'];
	$liste = obs($cdnom);
	$nb = count($liste);
	if($nb > 0)
	{
		$crs = array('name'=>'urn:ogc:def:crs:EPSG::2154');
		$resultats['type'] = 'FeatureCollection';
		$resultats['crs'] = array('type'=>'name','properties'=>$crs);
		foreach($liste as $n)
		{
			$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
			$feature['properties']['site'] = $n['site'];
			$feature['geometry'] = array('type' => 'Point', 'coordinates' => array(intval($n['x']), intval($n['y'])));
			$resultats['features'][] = $feature;			
		}
	}
	$retour['statut'] = 'Oui';
	$retour['nb'] = $nb;
	$_SESSION['export'] = json_encode($resultats, JSON_NUMERIC_CHECK);	
}
else
{
	$retour['statut'] = 'Non';	
}
echo json_encode($retour, JSON_NUMERIC_CHECK);