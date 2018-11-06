<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function nbespece()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT COUNT(DISTINCT cdref) AS nb, COUNT(idobs) AS nbo, observa FROM obs.obs 
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE (rang = 'ES' OR rang = 'SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY observa ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function nbespececom($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(DISTINCT cdref) AS nb, COUNT(idobs) AS nbo, observa FROM obs.obs 
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE codecom = :id AND (rang = 'ES' OR rang = 'SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY observa ");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function nbespecedep($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(DISTINCT cdref) AS nb, COUNT(idobs) AS nbo, observa FROM obs.obs 
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE iddep = :id AND (rang = 'ES' OR rang = 'SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY observa ");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function nbespecel93($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(DISTINCT cdref) AS nb, COUNT(idobs) AS nbo, observa FROM obs.obs 
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE codel93 = :id AND (rang = 'ES' OR rang = 'SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY observa ");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function nbespeceutm($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(DISTINCT cdref) AS nb, COUNT(idobs) AS nbo, observa FROM obs.obs 
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE utm = :id AND (rang = 'ES' OR rang = 'SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY observa ");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
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
function nbespecel935($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(DISTINCT cdref) AS nb, COUNT(idobs) AS nbo, observa FROM obs.obs 
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE codel935 = :id AND (rang = 'ES' OR rang = 'SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY observa ");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}

if(isset($_POST['choix']) && isset($_POST['id'])) 
{
	$id = $_POST['id'];
	$choix = $_POST['choix'];
	
	if($choix == 'aucun')
	{
		$nbespece = nbespece();
	}
	elseif($choix == 'com')
	{
		$nbespece = (strlen($id) == 5) ? nbespececom($id) : nbespececom('0'.$id);
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
		$nbespece = nbespecel93($id);
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
		$nbespece = nbespecel935($id);
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
		$nbespece = nbespeceutm($id);
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
			$nbespece = nbespecedep('0'.$id);
		}
		else
		{
			$nbespece = nbespecedep($id);
		}		
	}		
	$nb = count($nbespece);
	
	if($nb > 1)
	{
		$json = file_get_contents('../../../json/site.json');
		$rjson = json_decode($json, true);
		foreach($rjson['observatoire'] as $o)
		{
			$observa = $o['nomvar'];
			$nomaff = $o['nom'];
			foreach($nbespece as $e)	
			{
				if($e['observa'] == $observa)
				{
					$tabespece['name'] = $nomaff;
					$tabeobs['name'] = $nomaff;
					$tabespece['y'] = $e['nb'];
					$tabeobs['y'] = $e['nbo'];
					$tabespece['color'] = $o['couleur'];
					$tabeobs['color'] = $o['couleur'];
					$data[] = $tabespece;
					$datao[] = $tabeobs;
				}			
			}
		}	
		$retour['graph'] = 'Oui';
		$retour['data'] = $data;
		$retour['datao'] = $datao;
	}
	elseif($nb == 1)
	{
		$json = file_get_contents('../../../json/site.json');
		$rjson = json_decode($json, true);
		foreach($rjson['observatoire'] as $o)
		{
			$observa = $o['nomvar'];
			$nomaff = $o['nom'];
			foreach($nbespece as $e)	
			{
				if($e['observa'] == $observa)
				{
					$data = $e['nb'].' espèce(s), '.$e['nbo'].' observation(s)  ('.$nomaff.')';													
				}			
			}
		}		
		$retour['data'] = $data;
	}
	elseif($nb == 0)
	{
		$data = 'Aucune espèce';
		$retour['data'] = $data;
		$retour['lien'] = 'non';
	}
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';
}	
//echo json_encode(array_values($data), JSON_NUMERIC_CHECK);
echo json_encode($retour, JSON_NUMERIC_CHECK);
?>