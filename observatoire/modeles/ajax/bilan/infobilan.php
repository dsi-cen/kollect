<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function nbobs($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(idobs) AS nb FROM obs.obs
						WHERE observa = :observa ");
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function nbobscom($id,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(idobs) AS nb FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						WHERE observa = :observa AND codecom = :id ");
	$req->bindValue(':observa', $nomvar);
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function nbobsdep($id,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(idobs) AS nb FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						WHERE observa = :observa AND iddep = :id ");
	$req->bindValue(':observa', $nomvar);
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function nbobsl93($id,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(idobs) AS nb FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
						WHERE observa = :observa AND codel93 = :id ");
	$req->bindValue(':observa', $nomvar);
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function nbobsl935($id,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(idobs) AS nb FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
						WHERE observa = :observa AND codel935 = :id ");
	$req->bindValue(':observa', $nomvar);
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function nbobsutm($id,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(idobs) AS nb FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
						WHERE observa = :observa AND utm = :id ");
	$req->bindValue(':observa', $nomvar);
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function recherche_compoly($codecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT poly, geojson FROM referentiel.commune WHERE codecom = :codecom ");
	$req->bindValue(':codecom', $codecom);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_mgrs($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT geo FROM referentiel.mgrs10 WHERE mgrs = :id ");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}

if(isset($_POST['choix']) && isset($_POST['id'])) 
{
	$id = $_POST['id'];
	$choix = $_POST['choix'];
	$nomvar = $_POST['nomvar'];
	
	if($choix == 'aucun')
	{
		$nbobs = nbobs($nomvar);
	}
	elseif($choix == 'com')
	{
		$nbobs = (strlen($id) == 5) ? nbobscom($id,$nomvar) : nbobscom('0'.$id,$nomvar);
		$coord = recherche_compoly($id);
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
	elseif($choix == 'l93')
	{
		$nbobs = nbobsl93($id,$nomvar);
		$xg = substr($id, 1, -4) * 10000;
		$yb = substr($id, 5) * 10000;
		$xd = $xg + 10000;
		$yh = $yb + 10000;
		$resultats = array('crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
		$feature = array('type' => 'Feature', 'geometry' => Null);
		$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
		$resultats['features'][] = $feature;
		$retour['carto'] = $resultats;
	}
	elseif($choix == 'maille5')
	{
		$nbobs = nbobsl935($id,$nomvar);
		$xg = substr($id, 1, -5) * 1000;
		$yb = substr($id, 6) * 1000;
		$xd = $xg + 5000;
		$yh = $yb + 5000;		
		$resultats = array('crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
		$feature = array('type' => 'Feature', 'geometry' => Null);
		$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
		$resultats['features'][] = $feature;
		$retour['carto'] = $resultats;		
	}	
	elseif($choix == 'utm')
	{
		$nbobs = nbobsutm($id,$nomvar);
		$coord = recherche_mgrs($id);
		$resultats = array('crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
		$feature = array('type' => 'Feature', 'geometry' => Null);
		$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => $coord['geo']);
		$resultats['features'][] = $feature;
		$tmpcarto = json_encode($resultats, JSON_NUMERIC_CHECK);
		$tmpcarto = str_replace('"[','[',$tmpcarto);
		$tmpcarto = str_replace(']"',']',$tmpcarto);
		$resultats = json_decode($tmpcarto);
		$retour['carto'] = $resultats;	
	}
	elseif($choix == 'dep')
	{
		if($id == 1 || $id == 2 || $id == 3 || $id == 4 || $id == 5 || $id == 6 || $id == 7 || $id == 8 || $id == 9)
		{
			$nbobs = nbobsdep('0'.$id,$nomvar);
		}
		else
		{
			$nbobs = nbobsdep($id,$nomvar);
		}		
	}
	$nbtotal = $_POST['nbtotal'];
	if($nbtotal != 0)
	{
		$pourcent = round(($nbobs/$nbtotal)*100, 2);
		$retour['pourcent'] = $pourcent;
	}
	$retour['statut'] = 'Oui';
	$retour['nb'] = $nbobs;
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour, JSON_NUMERIC_CHECK);
?>