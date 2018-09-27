<?php 
include '../../../global/configbase.php';
include '../../lib/pdo2.php';

function cartol93($cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT MAX(code) AS code, codel93 FROM obs.aves
						INNER JOIN obs.obs USING(idobs)
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
						WHERE cdref = :cdref
						GROUP BY codel93 ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;
}
function maillel93()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT codel93 FROM referentiel.maillel93 ") or die(print_r($bdd->errorInfo()));
	$l93 = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $l93;
}	
if(isset($_POST['cdnom'])) 
{
	$cdnom = $_POST['cdnom'];
	
	$cartol93 = cartol93($cdnom);
	$l93 = maillel93();
	foreach ($cartol93 as $n)
	{
		$codel93[] = $n['codel93'];
		if($n['code'] <= 3) 
		{
			$nidif = 'Nidification possible';
		}
		elseif($n['code'] > 3 && $n['code'] <= 10) 
		{
			$nidif = 'Nidification probable';
		}
		elseif($n['code'] > 10) 
		{
			$nidif = 'Nidification certaine';
		}
		$info = 'Maille '.$n['codel93'].' '.$nidif; 
		$couleur = '#3A9D23';
		$xg = substr($n['codel93'], 1, -4)*10000;
		$yb = substr($n['codel93'], 5)*10000;
		$x = $xg + 5000;
		$y = $yb + 5000;			
		$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
		$feature['properties']['id'] = $n['codel93'];
		$feature['geometry'] = array('type' => 'Point', 'coordinates' => array(floatval($x), floatval($y)));
		$resultats['features'][] = $feature;
		
		/*$xg = substr($n['codel93'], 1, -4)*10000;
		$yb = substr($n['codel93'], 5)*10000;
		$xd = $xg + 10000;
		$yh = $yb + 10000;
		$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
		$feature['properties']['id'] = $n['codel93'];
		$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
		$resultats['features'][] = $feature;*/
		$carte[] = array("id"=>$n['codel93'], "z"=> 5);
	}
	unset($cartol93);
	foreach ($l93 as $n)
	{
		$couleur = '#fff';
		$info = 'Aucune donnée';
		
			$xg = substr($n['codel93'], 1, -4)*10000;
			$yb = substr($n['codel93'], 5)*10000;
			$xd = $xg + 10000;
			$yh = $yb + 10000;
			$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
			$feature['properties']['id'] = $n['codel93'];
			$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
			$resultats['features'][] = $feature;
			//$carte[] = array("id"=>$n['codel93'], "z"=> 5);		
							
	}	
	unset($l93);
	$retour['carto'] = $resultats;
	$retour['data'] = $carte;
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour, JSON_NUMERIC_CHECK);
?>