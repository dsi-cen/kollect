<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function nbespece($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT COUNT(DISTINCT obs.cdref) AS nb FROM obs.obs
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						INNER JOIN obs.aves ON aves.idobs = obs.idobs
						WHERE rang = 'ES' AND code > 10 ") or die(print_r($bdd->errorInfo()));
	$nbobs = $req->fetchColumn();
	$req->closeCursor();
	return $nbobs;
}
function cartoutm($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT utm, COUNT(DISTINCT obs.cdref) AS nb, geo FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
						INNER JOIN referentiel.mgrs10 ON mgrs10.mgrs = coordonnee.utm
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						INNER JOIN obs.aves ON aves.idobs = obs.idobs
						WHERE rang = 'ES' AND code > 10
						GROUP BY utm, geo ") or die(print_r($bdd->errorInfo()));
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;
}	
function mgrs()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT mgrs, geo FROM referentiel.mgrs10 ") or die(print_r($bdd->errorInfo()));
	$utm = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $utm;
}
function cartol93($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT codel93, COUNT(DISTINCT obs.cdref) AS nb FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						INNER JOIN obs.aves ON aves.idobs = obs.idobs
						WHERE rang = 'ES' AND code > 10
						GROUP BY codel93 ") or die(print_r($bdd->errorInfo()));
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;
}
function maillel93()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT codel93 FROM referentiel.maillel93 ") or die(print_r($bdd->errorInfo()));
	$l93 = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $l93;
}	


if(isset($_POST['utm']) && isset($_POST['nomvar'])) 
{
	$nomvar = $_POST['nomvar'];
	$utm = $_POST['utm'];
	
	$nbsp = nbespece($nomvar);
	
	if($nbsp > 0)
	{
		if ($utm == 'oui')
		{
			$cartoutm = cartoutm($nomvar);
			$utm = mgrs();
			foreach ($cartoutm as $n)
			{
				$codeutm[] = $n['utm'];
				$info = 'Nombre d\'espèces : '.$n['nb'];
				$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
				$feature['properties']['id'] = $n['utm'];
				$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => $n['geo']);
				$resultats['features'][] = $feature;
				$carte[] = array("nom"=>$n['utm'], "id"=>$n['utm'], "value"=>$n['nb'], "info"=>$info);
			}
			unset($cartoutm);
			foreach ($utm as $n)
			{
				$couleur = '#fff';
				$info = 'Aucun nicheur';
				if (!in_array($n['mgrs'], $codeutm))
				{
					$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
					$feature['properties']['id'] = $n['mgrs'];
					$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => $n['geo']);
					$resultats['features'][] = $feature;
					$carte[] = array("nom"=>$n['mgrs'], "id"=>$n['mgrs'], "color"=>$couleur, "info"=>$info);
				}
			}
			unset($utm);
			$tmpcarto = json_encode($resultats, JSON_NUMERIC_CHECK);
			$tmpcarto = str_replace('"[','[',$tmpcarto);
			$tmpcarto = str_replace(']"',']',$tmpcarto);
			$resultats = json_decode($tmpcarto);
			$retour['carto'] = $resultats;
			$retour['data'] = $carte;
			$retour['nbsp'] = $nbsp;
			$retour['maille'] = 'oui';
			$retour['statut'] = 'Oui';
		}
		else
		{
			$cartol93 = cartol93($nomvar);
			$l93 = maillel93();
			foreach ($cartol93 as $n)
			{
				$codel93[] = $n['codel93'];
				$info = 'Nombre d\'espèces : '.$n['nb'];
				$xg = substr($n['codel93'], 1, -4)*10000;
				$yb = substr($n['codel93'], 5)*10000;
				$xd = $xg + 10000;
				$yh = $yb + 10000;
				$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
				$feature['properties']['id'] = $n['codel93'];
				$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
				$resultats['features'][] = $feature;
				$carte[] = array("nom"=>$n['codel93'], "id"=>$n['codel93'], "value"=>$n['nb'], "info"=>$info);				
			}
			unset($cartol93);
			foreach ($l93 as $n)
			{
				$couleur = '#fff';
				$info = 'Aucun nicheur';
				if (!in_array($n['codel93'], $codel93))
				{
					$xg = substr($n['codel93'], 1, -4)*10000;
					$yb = substr($n['codel93'], 5)*10000;
					$xd = $xg + 10000;
					$yh = $yb + 10000;
					$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
					$feature['properties']['id'] = $n['codel93'];
					$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
					$resultats['features'][] = $feature;
					$carte[] = array("nom"=>$n['codel93'], "id"=>$n['codel93'], "color"=>$couleur, "info"=>$info);
				}
			}
			unset($l93);
			$retour['carto'] = $resultats;
			$retour['data'] = $carte;
			$retour['nbsp'] = $nbsp;
			$retour['maille'] = 'oui';
			$retour['statut'] = 'Oui';
		}
		$retour['nicheur'] = 'oui';
	}
	else
	{
		$retour['statut'] = 'Oui';
		$retour['nicheur'] = 'non';
	}
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour, JSON_NUMERIC_CHECK);
?>