<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function phenologie($cdnom,$nomvar,$rang)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT COUNT(ligneobs.idobs) AS nb, mois FROM referentiel.decade
							INNER JOIN obs.fiche ON fiche.decade = decade.decade
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							INNER JOIN obs.ligneobs ON ligneobs.idobs = obs.idobs
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							WHERE (obs.cdref = :cdnom OR cdsup = :cdnom) And idetatbio = 2 AND (validation = 1 OR validation = 2)
							GROUP BY mois");		
	}
	elseif($rang == 'non')
	{
		$req = $bdd->prepare("SELECT COUNT(ligneobs.idobs) AS nb, mois FROM referentiel.decade
							INNER JOIN obs.fiche ON fiche.decade = decade.decade
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							INNER JOIN obs.ligneobs ON ligneobs.idobs = obs.idobs
							WHERE cdref = :cdnom And idetatbio = 2 AND (validation = 1 OR validation = 2)
							GROUP BY mois");
	}
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$tabdec = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $tabdec;					
}

function cartocommune($cdnom,$rang,$nomvar,$droit)
{
	$strQuery = "WITH sel AS (SELECT DISTINCT EXTRACT(YEAR FROM MAX(date1)) as annee, MAX(date1) as d, fiche.codecom FROM obs.fiche INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche";
	if($rang == 'oui') { $strQuery .= " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref"; }
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND statutobs != 'No' AND (validation = 1 OR validation = 2)" : " WHERE obs.cdref = :cdref AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= " AND floutage <= 1"; }
	$strQuery .= " GROUP BY fiche.codecom)";
	$strQuery .= " SELECT annee, sel.codecom AS id  FROM sel 
							INNER JOIN obs.fiche ON	sel.codecom = fiche.codecom AND obs.fiche.date1 = sel.d
							GROUP BY sel.annee, sel.d, sel.codecom";
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;
}
function cartodep($cdnom,$rang,$nomvar)
{
	$strQuery = "WITH sel AS (SELECT DISTINCT EXTRACT(YEAR FROM MAX(date1)) as annee, MAX(date1) as d, fiche.iddep FROM obs.fiche INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche";
	if($rang == 'oui') { $strQuery .= " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref"; }
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND statutobs != 'No' AND (validation = 1 OR validation = 2)" : " WHERE obs.cdref = :cdref AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	$strQuery .= " GROUP BY fiche.iddep)";
	$strQuery .= " SELECT annee, sel.iddep AS id  FROM sel 
					INNER JOIN obs.fiche ON	sel.iddep = fiche.iddep AND obs.fiche.date1 = sel.d
					GROUP BY sel.annee, sel.d, sel.iddep";
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;
}		
function cartoutm($cdnom,$rang,$nomvar,$droit)
{
	$strQuery = "WITH sel AS (SELECT DISTINCT EXTRACT(YEAR FROM MAX(date1)) as annee, MAX(date1) as d, utm FROM obs.fiche
								INNER JOIN obs.obs USING(idfiche)
								INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord";
	if($rang == 'oui') { $strQuery .= " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref"; }
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND statutobs != 'No' AND utm != '' AND (validation = 1 OR validation = 2)" : " WHERE obs.cdref = :cdref AND statutobs != 'No' AND utm != '' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= " AND floutage <= 2"; }
	$strQuery .= " GROUP BY utm)";
	$strQuery .= " SELECT annee, sel.utm, geo FROM sel 
					INNER JOIN obs.fiche ON obs.fiche.date1 = sel.d
					INNER JOIN obs.obs USING(idfiche)
					INNER JOIN obs.coordonnee ON obs.coordonnee.utm = sel.utm AND fiche.idcoord = coordonnee.idcoord
					INNER JOIN referentiel.mgrs10 ON mgrs10.mgrs = sel.utm
					GROUP BY sel.annee, sel.utm, geo ";
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;	
}	
function cartol93($cdnom,$rang,$nomvar,$droit)
{
	$strQuery = "WITH sel AS (SELECT DISTINCT EXTRACT(YEAR FROM MAX(date1)) as annee, MAX(date1) as d, codel93 FROM obs.fiche
								INNER JOIN obs.obs USING(idfiche)
								INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord";
	if($rang == 'oui') { $strQuery .= " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref"; }
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND statutobs != 'No' AND codel93 != '' AND (validation = 1 OR validation = 2)" : " WHERE obs.cdref = :cdref AND statutobs != 'No' AND codel93 != '' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= " AND floutage <= 2"; }
	$strQuery .= " GROUP BY codel93)";
	$strQuery .= " SELECT annee, sel.codel93 FROM sel 
							INNER JOIN obs.fiche ON obs.fiche.date1 = sel.d
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN obs.coordonnee ON obs.coordonnee.codel93 = sel.codel93 AND fiche.idcoord = coordonnee.idcoord
							GROUP BY sel.annee, sel.codel93";
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;	
}
function commune()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT codecom AS id, commune AS emp, poly, geojson FROM referentiel.commune");
	$commune = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $commune;
}
function departement()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT iddep AS id, departement AS emp, poly, geojson FROM referentiel.departement");
	$commune = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $commune;
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
function maillel93()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT codel93 FROM referentiel.maillel93 ");
	$l93 = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $l93;
}
if(isset($_POST['emprise']) && isset($_POST['choixcarte']) && isset($_POST['cdnom'])) 
{
	$emprise = $_POST['emprise'];
	$choix = $_POST['choixcarte'];
	$cdnom = $_POST['cdnom'];
	$rang = $_POST['rang'];
	$nomvar = $_POST['nomvar'];
	$sensible = $_POST['sensible'];
	$anneeencours = date('Y');
	$droit = (isset($_SESSION['droits']) && $_SESSION['droits'] >= 2) ? 'oui' : 'non';
	
	$json_site = file_get_contents('../../../../json/site.json');
	$rjson = json_decode($json_site, true);
	if(isset($rjson['fiche']['classefiche'])) 
	{
		$nbclasse = count($rjson['fiche']['classefiche']);
		foreach($rjson['fiche']['classefiche'] as $n)
		{		
			if($n['classe'] == 'classe1') {$cou1 = $n['couleur'];}
			elseif($n['classe'] == 'classe2') {$cou2 = $n['couleur']; $an2 = $n['annee'];}
			elseif($n['classe'] == 'classe3') {$cou3 = $n['couleur']; $an3 = $n['annee'];}
			elseif($n['classe'] == 'classe4') {$cou4 = $n['couleur']; $an4 = $n['annee'];}
			elseif($n['classe'] == 'classe5') {$cou5 = $n['couleur']; $an5 = $n['annee'];}
			elseif($n['classe'] == 'classe6') {$cou6 = $n['couleur']; $an6 = $n['annee'];}
		}		
	}
	if($choix == 'commune')
	{
		if($emprise != 'fr')
		{
			$real = ($sensible == '' || $sensible == 1 || $droit == 'oui') ? 'oui' : 'non';
			if($real == 'oui')
			{
				$cartocom = cartocommune($cdnom,$rang,$nomvar,$droit);
				$commune = commune();
			}
		}
		else
		{
			$cartocom = cartodep($cdnom,$rang,$nomvar);
			$commune = departement();
			$real = 'oui';
		}
		if($real == 'oui')
		{
			if(count($cartocom) > 0)
			{
				foreach($cartocom as $n)
				{
					$codecom[] = $n['id'];
				}
				$code = array_flip($codecom);
			
				foreach ($commune as $n)
				{
					if(isset($code[$n['id']]))
					{
						$cle = $code[$n['id']];				
						if($nbclasse == 3)
						{
							if ($cartocom[$cle]['annee'] == $anneeencours) {$couleur = $cou1;}
							elseif ($cartocom[$cle]['annee'] >= $an2) {$couleur = $cou2;}
							elseif ($cartocom[$cle]['annee'] < $an3) {$couleur = $cou3;}					
						}
						elseif($nbclasse == 4)
						{
							if ($cartocom[$cle]['annee'] == $anneeencours) {$couleur = $cou1;}
							elseif ($cartocom[$cle]['annee'] >= $an2) {$couleur = $cou2;}
							elseif ($cartocom[$cle]['annee'] < $an2 && $cartocom[$cle]['annee'] >= $an3) {$couleur = $cou3;}
							elseif ($cartocom[$cle]['annee'] < $an4) {$couleur = $cou4;}					
						}
						elseif($nbclasse == 5)
						{
							if ($cartocom[$cle]['annee'] == $anneeencours) {$couleur = $cou1;}
							elseif ($cartocom[$cle]['annee'] >= $an2) {$couleur = $cou2;}
							elseif ($cartocom[$cle]['annee'] < $an2 && $cartocom[$cle]['annee'] >= $an3) {$couleur = $cou3;}
							elseif ($cartocom[$cle]['annee'] < $an3 && $cartocom[$cle]['annee'] >= $an4) {$couleur = $cou4;}
							elseif ($cartocom[$cle]['annee'] < $an5) {$couleur = $cou5;}					
						}
						elseif($nbclasse == 6)
						{
							if ($cartocom[$cle]['annee'] == $anneeencours) {$couleur = $cou1;}
							elseif ($cartocom[$cle]['annee'] >= $an2) {$couleur = $cou2;}
							elseif ($cartocom[$cle]['annee'] < $an2 && $cartocom[$cle]['annee'] >= $an3) {$couleur = $cou3;}
							elseif ($cartocom[$cle]['annee'] < $an3 && $cartocom[$cle]['annee'] >= $an4) {$couleur = $cou4;}
							elseif ($cartocom[$cle]['annee'] < $an4 && $cartocom[$cle]['annee'] >= $an5) {$couleur = $cou5;}
							elseif ($cartocom[$cle]['annee'] < $an6) {$couleur = $cou6;}					
						}
						$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
						$feature['properties']['id'] = $n['id'];
						$feature['geometry'] = array('type' => $n['poly'], 'coordinates' => $n['geojson']);
						$resultats['features'][] = $feature;
						$carte[] = array("nom"=>$n['emp'], "id"=>$n['id'], "color"=>$couleur);								
					}
					else
					{
						$couleur = '#fff';
						$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
						$feature['properties']['id'] = $n['id'];
						$feature['geometry'] = array('type' => $n['poly'], 'coordinates' => $n['geojson']);
						$resultats['features'][] = $feature;
						$carte[] = array("nom"=>$n['emp'], "id"=>$n['id'], "color"=>$couleur);
					}
				}
				unset($commune);
				unset($cartocom);
				$tmpcarto = json_encode($resultats, JSON_NUMERIC_CHECK);
				$tmpcarto = str_replace('"[','[',$tmpcarto);
				$tmpcarto = str_replace(']"',']',$tmpcarto);
				$resultats = json_decode($tmpcarto);
				$retour['carto'] = $resultats;
				$retour['data'] = $carte;
				$retour['maille'] = 'non';
				$retour['sensible'] = 'non';
				$retour['statut'] = 'Oui';
			}
			else
			{
				$ras = 'oui';
				$retour['sensible'] = 'non';
				$retour['ras'] = '<p class="p-2">Aucune donnée validée actuellement pour cette espèce.<br />Sur les fiches, ne figure pas les photos et données d\'observations non validées.<br />Néanmoins, il est possible de consulter la liste des observations en allant sur l\'onglet <i class="fa fa-info fa-lg"></i> </p>';
				$retour['statut'] = 'Oui';
			}
		}
		else
		{
			$retour['statut'] = 'Oui';
			$retour['sensible'] = 'oui';
		}
	}
	elseif($choix == 'maille')
	{
		$real = ($sensible == '' || $sensible <= 2) ? 'oui' : 'non';
		if($real == 'oui')
		{
			$utm = $_POST['utm'];
			if($utm == 'oui')
			{
				$cartoutm = cartoutm($cdnom,$rang,$nomvar,$droit);
				if(count($cartoutm) > 0)
				{
					foreach($cartoutm as $n)
					{
						$codeutm[] = $n['utm'];
						if($nbclasse == 3)
						{
							if($n['annee'] == $anneeencours) {$couleur = $cou1;}
							elseif ($n['annee'] >= $an2) {$couleur = $cou2;}
							elseif ($n['annee'] < $an3) {$couleur = $cou3;}					
						}
						elseif($nbclasse == 4)
						{
							if($n['annee'] == $anneeencours) {$couleur = $cou1;}
							elseif ($n['annee'] >= $an2) {$couleur = $cou2;}
							elseif ($n['annee'] < $an2 && $n['annee'] >= $an3) {$couleur = $cou3;}
							elseif ($n['annee'] < $an4) {$couleur = $cou4;}					
						}
						elseif($nbclasse == 5)
						{
							if ($n['annee'] == $anneeencours) {$couleur = $cou1;}
							elseif ($n['annee'] >= $an2) {$couleur = $cou2;}
							elseif ($n['annee'] < $an2 && $n['annee'] >= $an3) {$couleur = $cou3;}
							elseif ($n['annee'] < $an3 && $n['annee'] >= $an4) {$couleur = $cou4;}
							elseif ($n['annee'] < $an5) {$couleur = $cou5;}					
						}
						elseif($nbclasse == 6)
						{
							if($n['annee'] == $anneeencours) {$couleur = $cou1;}
							elseif ($n['annee'] >= $an2) {$couleur = $cou2;}
							elseif ($n['annee'] < $an2 && $n['annee'] >= $an3) {$couleur = $cou3;}
							elseif ($n['annee'] < $an3 && $n['annee'] >= $an4) {$couleur = $cou4;}
							elseif ($n['annee'] < $an4 && $n['annee'] >= $an5) {$couleur = $cou5;}
							elseif ($n['annee'] < $an6) {$couleur = $cou6;}					
						}
						$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
						$feature['properties']['id'] = $n['utm'];
						$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => $n['geo']);
						$resultats['features'][] = $feature;
						$carte[] = array("nom"=>$n['utm'], "id"=>$n['utm'], "color"=>$couleur);
					}
					unset($cartoutm);
				}
				else
				{
					$codeutm[] = '';
				}
				$code = array_flip($codeutm);
				if($emprise != 'fr')
				{
					$utm = mgrs();
					foreach ($utm as $n)
					{
						$couleur = '#fff';
						if(!isset($code[$n['mgrs']]))
						{
							$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
							$feature['properties']['id'] = $n['mgrs'];
							$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => $n['geo']);
							$resultats['features'][] = $feature;
							$carte[] = array("nom"=>$n['mgrs'], "id"=>$n['mgrs'], "color"=>$couleur);
						}
					}
				}
				$tmpcarto = json_encode($resultats, JSON_NUMERIC_CHECK);
				$tmpcarto = str_replace('"[','[',$tmpcarto);
				$tmpcarto = str_replace(']"',']',$tmpcarto);
				$resultats = json_decode($tmpcarto);				
			}
			else
			{
				$cartol93 = cartol93($cdnom,$rang,$nomvar,$droit);
				if(count($cartol93) > 0)
				{
					foreach($cartol93 as $n)
					{
						$codel93[] = $n['codel93'];
						if($nbclasse == 3)
						{
							if ($n['annee'] == $anneeencours) {$couleur = $cou1;}
							elseif ($n['annee'] >= $an2) {$couleur = $cou2;}
							elseif ($n['annee'] < $an3) {$couleur = $cou3;}					
						}
						elseif($nbclasse == 4)
						{
							if ($n['annee'] == $anneeencours) {$couleur = $cou1;}
							elseif ($n['annee'] >= $an2) {$couleur = $cou2;}
							elseif ($n['annee'] < $an2 && $n['annee'] >= $an3) {$couleur = $cou3;}
							elseif ($n['annee'] < $an4) {$couleur = $cou4;}					
						}
						elseif($nbclasse == 5)
						{
							if ($n['annee'] == $anneeencours) {$couleur = $cou1;}
							elseif ($n['annee'] >= $an2) {$couleur = $cou2;}
							elseif ($n['annee'] < $an2 && $n['annee'] >= $an3) {$couleur = $cou3;}
							elseif ($n['annee'] < $an3 && $n['annee'] >= $an4) {$couleur = $cou4;}
							elseif ($n['annee'] < $an5) {$couleur = $cou5;}					
						}
						elseif($nbclasse == 6)
						{
							if ($n['annee'] == $anneeencours) {$couleur = $cou1;}
							elseif ($n['annee'] >= $an2) {$couleur = $cou2;}
							elseif ($n['annee'] < $an2 && $n['annee'] >= $an3) {$couleur = $cou3;}
							elseif ($n['annee'] < $an3 && $n['annee'] >= $an4) {$couleur = $cou4;}
							elseif ($n['annee'] < $an4 && $n['annee'] >= $an5) {$couleur = $cou5;}
							elseif ($n['annee'] < $an6) {$couleur = $cou6;}					
						}
						$xg = substr($n['codel93'], 1, -4)*10000;
						$yb = substr($n['codel93'], 5)*10000;
						$xd = $xg + 10000;
						$yh = $yb + 10000;
						$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
						$feature['properties']['id'] = $n['codel93'];
						$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
						$resultats['features'][] = $feature;
						$carte[] = array("nom"=>$n['codel93'], "id"=>$n['codel93'], "color"=>$couleur);
					}
					unset($cartol93);
				}
				else
				{
					$codel93[] = '';
				}
				$code = array_flip($codel93);
				if($emprise != 'fr')
				{
					$l93 = maillel93();
					foreach ($l93 as $n)
					{
						$couleur = '#fff';
						if(!isset($code[$n['codel93']]))
						{
							$xg = substr($n['codel93'], 1, -4)*10000;
							$yb = substr($n['codel93'], 5)*10000;
							$xd = $xg + 10000;
							$yh = $yb + 10000;
							$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
							$feature['properties']['id'] = $n['codel93'];
							$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
							$resultats['features'][] = $feature;
							$carte[] = array("nom"=>$n['codel93'], "id"=>$n['codel93'], "color"=>$couleur);
						}
					}
					unset($l93);
				}
			}
			$retour['carto'] = $resultats;
			$retour['data'] = $carte;
			$retour['maille'] = 'oui';
			$retour['statut'] = 'Oui';
			$retour['sensible'] = 'non';
		}
		else
		{
			$retour['statut'] = 'Oui';
			$retour['sensible'] = 'oui';
		}
	}
	//phéno
	if(!isset($ras))
	{
		$tabiddecade = [1,2,3,4,5,6,7,8,9,10,11,12];
		$tabdec = phenologie($cdnom,$nomvar,$rang);
		
		foreach($tabiddecade as $n)
		{
			$tab2[$n-1] = 0;		
		}
		
		foreach($tabdec as $n)
		{
			$tab2[$n['mois']-1] = $n['nb'];
		}
		
		$retour['tab'] = $tab2;
	}
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour, JSON_NUMERIC_CHECK);
?>