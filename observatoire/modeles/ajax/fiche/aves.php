<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function cartol93($cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT EXTRACT(YEAR FROM date1) as annee, MAX(code) AS code, codel93 FROM obs.aves
						INNER JOIN obs.obs USING(idobs)
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
						WHERE cdref = :cdref
						GROUP BY codel93, annee 
						ORDER BY codel93, code") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;
}
/*function cartol93($cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
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
}*/
function maillel93()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT codel93 FROM referentiel.maillel93 ") or die(print_r($bdd->errorInfo()));
	$l93 = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $l93;
}	
if(isset($_POST['cdnom'])) 
{
	$cdnom = $_POST['cdnom'];
	
	$cartol93 = cartol93($cdnom);
	if($cartol93 != false)
	{
		$l93 = maillel93();
		
		foreach($cartol93 as $n)
		{
			$annee[] = $n['annee'];
			$xg = substr($n['codel93'], 1, -4)*10000;
			$yb = substr($n['codel93'], 5)*10000;
			$x = $xg + 5000;
			$y = $yb + 5000;
			$point = array('coordinates' => array(floatval($x), floatval($y)), 'type' => 'Point');
			$tabor[] = ['codel93'=>$n['codel93'], 'code'=>$n['code'], 'point'=>$point,'annee'=>$n['annee']];			
		}
		unset($cartol93);
		$retour['min'] = min($annee);
		$retour['max'] = max($annee);
		$retour['data'] = $tabor;
		
		foreach($l93 as $n)
		{
			$xg = substr($n['codel93'], 1, -4)*10000;
			$yb = substr($n['codel93'], 5)*10000;
			$xd = $xg + 10000;
			$yh = $yb + 10000;
			$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
			$feature['properties']['cd'] = $n['codel93'];
			$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
			$resultats['features'][] = $feature;
		}	
		unset($l93);
		$retour['nicheur'] = 'oui';
		$retour['carto'] = $resultats;
	}
	else
	{
		$retour['nicheur'] = 'non';
	}
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour, JSON_NUMERIC_CHECK);
?>