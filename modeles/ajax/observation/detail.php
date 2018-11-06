<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function recherche_xy($idcoord)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT lat, lng, geo FROM obs.coordonnee 
						LEFT JOIN obs.coordgeo USING(idcoord)
						WHERE idcoord = :idcoord ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idcoord', $idcoord);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_compoly($codecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT poly, geojson FROM referentiel.commune WHERE codecom = :codecom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':codecom', $codecom);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_deppoly($iddep)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT poly, geojson FROM referentiel.departement WHERE iddep = :iddep ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':iddep', $iddep);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_maille($idcoord)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT codel93 FROM obs.coordonnee WHERE idcoord = :idcoord ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idcoord', $idcoord);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
if(isset($_POST['flou']) && isset($_POST['sel']))
{
	$flou = $_POST['flou'];
	$sel = $_POST['sel'];
	
	if($flou == 'point')
	{
		$coord = recherche_xy($sel);
		$xy = $coord['lat'].','.$coord['lng'];
		$retour['point'] = $xy;
		$retour['contour'] = $coord['geo'];
	}
	elseif($flou == 'commune')
	{
		$coord = recherche_compoly($sel);
		$resultats = array('crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
		$feature = array('type' => 'Feature', 'geometry' => Null);
		$feature['geometry'] = array('type' => $coord['poly'], 'coordinates' => $coord['geojson']);
		$resultats['features'][] = $feature;
		$tmpcarto = json_encode($resultats, JSON_NUMERIC_CHECK);
		$tmpcarto = str_replace('"[','[',$tmpcarto);
		$tmpcarto = str_replace(']"',']',$tmpcarto);
		$resultats = json_decode($tmpcarto);
		$retour['carto'] = $resultats;	
	}
	elseif($flou == 'maille')
	{
		$maille = recherche_maille($sel);
		$xg = substr($maille['codel93'], 1, -4)*10000;
		$yb = substr($maille['codel93'], 5)*10000;
		$xd = $xg + 10000;
		$yh = $yb + 10000;
		$resultats = array('crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
		$feature = array('type' => 'Feature', 'geometry' => Null);
		$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
		$resultats['features'][] = $feature;
		$retour['carto'] = $resultats;		
	}
	elseif($flou == 'dep')
	{
		$coord = recherche_deppoly($sel);
		$resultats = array('crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
		$feature = array('type' => 'Feature', 'geometry' => Null);
		$feature['geometry'] = array('type' => $coord['poly'], 'coordinates' => $coord['geojson']);
		$resultats['features'][] = $feature;
		$tmpcarto = json_encode($resultats, JSON_NUMERIC_CHECK);
		$tmpcarto = str_replace('"[','[',$tmpcarto);
		$tmpcarto = str_replace(']"',']',$tmpcarto);
		$resultats = json_decode($tmpcarto);
		$retour['carto'] = $resultats;	
	}
	
	$retour['statut'] = 'Oui';
	echo json_encode($retour, JSON_NUMERIC_CHECK);
}	