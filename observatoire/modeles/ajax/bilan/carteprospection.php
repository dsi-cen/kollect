<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function nbespece($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT COUNT(DISTINCT obs.cdref) AS Nb FROM obs.obs
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						WHERE rang = 'ES' ");
	$nbobs = $req->fetchColumn();
	$req->closeCursor();
	return $nbobs;
}
function cartoutm($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("WITH sel AS (SELECT DISTINCT(obs.cdref), utm, geo FROM obs.obs
									INNER JOIN obs.fiche USING(idfiche)
									INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
									INNER JOIN referentiel.mgrs10 ON mgrs10.mgrs = coordonnee.utm
									INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
									WHERE (rang = 'ES' OR rang = 'SSES') AND codel93 != ''
						)
                        SELECT sel.utm, COUNT(DISTINCT cdref) AS nb, sel.geo FROM sel
                        GROUP BY sel.utm, sel.geo ");
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;
}	
function mgrs()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT mgrs, geo FROM referentiel.mgrs10 ");
	$utm = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $utm;
}
function cartol93($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("WITH sel AS (SELECT DISTINCT(obs.cdref), codel93 FROM obs.obs
									INNER JOIN obs.fiche USING(idfiche)
									INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
									INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
									WHERE (rang = 'ES' OR rang = 'SSES') AND codel93 != ''
						)
                        SELECT sel.codel93, COUNT(DISTINCT cdref) AS nb FROM sel
                        GROUP BY sel.codel93 ");
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;
}
function maillel93()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT codel93 FROM referentiel.maillel93 ");
	$l93 = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $l93;
}	

if(isset($_POST['choixcarte'])) 
{
	$choix = htmlspecialchars($_POST['choixcarte']);
	$nomvar = htmlspecialchars($_POST['nomvar']);
	$emp = htmlspecialchars($_POST['emp']);
	
	$nbsp = nbespece($nomvar);
	if($choix == 'utm')
	{
		$cartoutm = cartoutm($nomvar);
		foreach ($cartoutm as $n)
		{
			$codeutm[] = $n['utm'];
			$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
			$feature['properties']['id'] = $n['utm'];
			$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => $n['geo']);
			$resultats['features'][] = $feature;
			$carte[] = ['id'=>$n['utm'], 'value'=>$n['nb']];
			$nb[] = $n['nb'];
		}
		unset($cartoutm);
		if($emp != 'fr')
		{
			$utm = mgrs();
			foreach($utm as $n)
			{
				if(!in_array($n['mgrs'], $codeutm))
				{
					$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
					$feature['properties']['id'] = $n['mgrs'];
					$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => $n['geo']);
					$resultats['features'][] = $feature;
					$carte[] = ['id'=>$n['mgrs'], 'value'=>0];
				}
			}
			unset($utm);
		}
		$tmpcarto = json_encode($resultats, JSON_NUMERIC_CHECK);
		$tmpcarto = str_replace('"[','[',$tmpcarto);
		$tmpcarto = str_replace(']"',']',$tmpcarto);
		$resultats = json_decode($tmpcarto);
		$nbmax = max($nb);
		$retour['carto'] = $resultats;
		$retour['data'] = $carte;
		$retour['nbmax'] = $nbmax;
		$retour['nbsp'] = $nbsp;
		$retour['statut'] = 'Oui';
	}
	else
	{
		$cartol93 = cartol93($nomvar);
		foreach ($cartol93 as $n)
		{
			$codel93[] = $n['codel93'];
			$xg = substr($n['codel93'], 1, -4)*10000;
			$yb = substr($n['codel93'], 5)*10000;
			$xd = $xg + 10000;
			$yh = $yb + 10000;
			$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
			$feature['properties']['id'] = $n['codel93'];
			$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
			$resultats['features'][] = $feature;
			$carte[] = ['id'=>$n['codel93'], 'value'=>$n['nb']];
			$nb[] = $n['nb'];
		}
		unset($cartol93);
		if($emp != 'fr')
		{
			$l93 = maillel93();
			foreach($l93 as $n)
			{
				if(!in_array($n['codel93'], $codel93))
				{
					$xg = substr($n['codel93'], 1, -4)*10000;
					$yb = substr($n['codel93'], 5)*10000;
					$xd = $xg + 10000;
					$yh = $yb + 10000;
					$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
					$feature['properties']['id'] = $n['codel93'];
					$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
					$resultats['features'][] = $feature;
					$carte[] = ['id'=>$n['codel93'], 'value'=>0];
				}
			}
			unset($l93);
		}
		$nbmax = max($nb);
		$retour['carto'] = $resultats;
		$retour['data'] = $carte;
		$retour['nbmax'] = $nbmax;
		$retour['nbsp'] = $nbsp;
		$retour['statut'] = 'Oui';
	}	
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour, JSON_NUMERIC_CHECK);
?>