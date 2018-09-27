<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function cartoutm($cdnom,$rang,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT DISTINCT utm, geo FROM obs.fiche
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
							INNER JOIN referentiel.mgrs10 ON mgrs10.mgrs = coordonnee.utm
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND floutage <= 2 AND statutobs != 'No' ") or die(print_r($bdd->errorInfo()));
	}
	elseif($rang == 'non')
	{
		$req = $bdd->prepare("SELECT DISTINCT utm, geo FROM obs.fiche
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
							INNER JOIN referentiel.mgrs10 ON mgrs10.mgrs = coordonnee.utm
							WHERE obs.cdref = :cdref AND floutage <= 2 AND statutobs != 'No' ") or die(print_r($bdd->errorInfo()));
	}
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;
}	
function cartol93($cdnom,$rang,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT DISTINCT codel93 FROM obs.fiche
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND floutage <= 2 AND statutobs != 'No' AND (validation = 1 OR validation = 2) ") or die(print_r($bdd->errorInfo()));
	}
	elseif($rang == 'non')
	{
		$req = $bdd->prepare("SELECT DISTINCT codel93 FROM obs.fiche
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
							WHERE obs.cdref = :cdref AND floutage <= 2 AND statutobs != 'No' AND (validation = 1 OR validation = 2) ") or die(print_r($bdd->errorInfo()));
	}
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;
}
function nbobs($nomvar,$rang,$cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT idbiogeo, COUNT(idobs) AS nb FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							INNER JOIN obs.biogeo ON biogeo.idcoord = fiche.idcoord
							WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND statutobs != 'No' AND (validation = 1 OR validation = 2)
							GROUP BY idbiogeo ") or die(print_r($bdd->errorInfo()));
	}
	elseif($rang == 'non')
	{
		$req = $bdd->prepare("SELECT idbiogeo, COUNT(idobs) AS nb FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							INNER JOIN obs.biogeo ON biogeo.idcoord = fiche.idcoord
							WHERE obs.cdref = :cdref AND statutobs != 'No' AND (validation = 1 OR validation = 2)
							GROUP BY idbiogeo ") or die(print_r($bdd->errorInfo()));
	}
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}

if(isset($_POST['cdnom']) && isset($_POST['utm'])) 
{
	$cdnom = $_POST['cdnom'];
	$rang = $_POST['rang'];
	$nomvar = $_POST['nomvar'];
	$utm = $_POST['utm'];	
		
	$nbobs = nbobs($nomvar,$rang,$cdnom);
	if($nbobs != false)
	{
		$tabbio[] = array('id'=>1,'nom'=>'Atlantique','color'=>'#94BEDE');
		$tabbio[] = array('id'=>2,'nom'=>'Continental','color'=>'#CEE77B');
		$tabbio[] = array('id'=>3,'nom'=>'Alpin','color'=>'#A575B5');
		$tabbio[] = array('id'=>4,'nom'=>'Méditerranéen','color'=>'#FFCB5A');
		$retour['bio'] = $tabbio;
		foreach($tabbio as $b)
		{
			foreach($nbobs as $n)
			{
				if($n['idbiogeo'] == $b['id'])
				{
					$tabespece[0] = $b['nom'];
					$tabespece[1] = $n['nb'];
					$data[] = $tabespece;
					$color[] = $b['color'];
				}			
			}
		}
		$retour['data'] = $data;
		$retour['color'] = $color;
	}
	
	if ($utm == 'oui')
	{
		$cartoutm = cartoutm($cdnom,$rang,$nomvar);
		foreach($cartoutm as $n)
		{
			$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
			$feature['properties']['id'] = $n['utm'];
			$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => $n['geo']);
			$resultats['features'][] = $feature;
		}
		unset($cartoutm);
		$tmpcarto = json_encode($resultats, JSON_NUMERIC_CHECK);
		$tmpcarto = str_replace('"[','[',$tmpcarto);
		$tmpcarto = str_replace(']"',']',$tmpcarto);
		$resultats = json_decode($tmpcarto);
		$retour['carto'] = $resultats;
		$retour['statut'] = 'Oui';
	}
	else
	{
		$cartol93 = cartol93($cdnom,$rang,$nomvar);
		if($cartol93 != false)
		{
			foreach($cartol93 as $n)
			{
				$xg = substr($n['codel93'], 1, -4)*10000;
				$yb = substr($n['codel93'], 5)*10000;
				$xd = $xg + 10000;
				$yh = $yb + 10000;
				$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
				$feature['properties']['id'] = $n['codel93'];
				$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
				$resultats['features'][] = $feature;			
			}
			unset($cartol93);
			$retour['carto'] = $resultats;
		}
		$retour['statut'] = 'Oui';		
	}		
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour, JSON_NUMERIC_CHECK);
?>