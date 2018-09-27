<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();
/*
SELECT DISTINCT date1, EXTRACT(YEAR FROM date1) AS annee, to_char(date1, 'TMmonth') AS mois, genre, com, t1.codecom AS id, commune AS emp, poly, geojson FROM obs.fiche AS t1
							INNER JOIN (SELECT MAX(date1) AS d, genre, com, fiche.codecom, commune, poly, geojson FROM obs.fiche
								INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
								INNER JOIN referentiel.commune ON commune.codecom = fiche.codecom
								INNER JOIN lepido.liste ON liste.cdnom = obs.cdref
								LEFT JOIN referentiel.similaire ON similaire.com = obs.cdref
								WHERE (obs.cdref = 211146 OR genre = 'Acronicta') AND floutage <= 1 AND statutobs != 'No'
								GROUP BY genre, com, fiche.codecom, commune, poly, geojson) AS t2
							ON t2.codecom = t1.codecom AND t2.d = t1.date1
							order by date1
*/
function cartocommune($cdnom,$nomvar,$nbgenresp,$genre,$droit)
{
	$strQuery = "SELECT DISTINCT date1, EXTRACT(YEAR FROM date1) AS annee, to_char(date1, 'TMmonth') AS mois, genre, com, t1.codecom AS id, commune AS emp, poly, geojson FROM obs.fiche AS t1
					INNER JOIN (SELECT MAX(date1) AS d, genre, com, fiche.codecom, commune, poly, geojson FROM obs.fiche
						INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
						INNER JOIN referentiel.commune ON commune.codecom = fiche.codecom
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						LEFT JOIN referentiel.similaire ON similaire.com = obs.cdref";
	$strQuery .= ($nbgenresp > 0) ? " WHERE (obs.cdref = :cdref OR genre = :genre) AND statutobs != 'No' AND (validation = 1 OR validation = 2)" : " WHERE genre = :genre AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= " AND floutage <= 1"; }
	$strQuery .= " GROUP BY genre, com, fiche.codecom, commune, poly, geojson) AS t2 ON t2.codecom = t1.codecom AND t2.d = t1.date1";
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':genre', $genre);
	if($nbgenresp > 0) { $req->bindValue(':cdref', $cdnom); }
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;
}
function cartodep($cdnom,$nomvar,$nbgenresp,$genre)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($nbgenresp > 0)
	{
		$req = $bdd->prepare("SELECT DISTINCT date1, EXTRACT(YEAR FROM date1) AS annee, to_char(date1, 'TMmonth') AS mois, genre, t1.iddep AS id, departement AS emp, poly, geojson FROM obs.fiche AS t1
							INNER JOIN (SELECT MAX(date1) AS d, genre, fiche.iddep, departement, poly, geojson FROM obs.fiche
								INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
								INNER JOIN referentiel.departement ON departement.iddep = fiche.iddep
								INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
								WHERE (obs.cdref = :cdref OR genre = :genre) AND floutage <= 3 AND statutobs != 'No'
								GROUP BY genre, fiche.iddep, departement, poly, geojson) AS t2
							ON t2.iddep = t1.iddep AND t2.d = t1.date1
							ORDER BY date1") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':cdref', $cdnom);
	}
	else
	{
		$req = $bdd->prepare("SELECT DISTINCT EXTRACT(YEAR FROM date1) AS annee, to_char(date1, 'TMmonth') AS mois, t1.iddep AS id, departement AS emp, poly, geojson FROM obs.fiche AS t1
							INNER JOIN (SELECT MAX(date1) AS d, fiche.iddep, departement, poly, geojson FROM obs.fiche
								INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
								INNER JOIN referentiel.departement ON departement.iddep = fiche.iddep
								INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
								WHERE genre = :genre AND floutage <= 3 AND statutobs != 'No'
								GROUP BY fiche.iddep, departement, poly, geojson) AS t2
							ON t2.iddep = t1.iddep AND t2.d = t1.date1") or die(print_r($bdd->errorInfo()));		
	}	
	$req->bindValue(':genre', $genre);
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
function cartoutm($cdnom,$nomvar,$nbgenresp,$genre)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($nbgenresp > 0)
	{
		$req = $bdd->prepare("SELECT DISTINCT date1, EXTRACT(YEAR FROM date1) AS annee, to_char(date1, 'TMmonth') AS mois, genre, utm AS id, geo FROM obs.fiche AS t1
							INNER JOIN (SELECT MAX(date1) as d, genre, utm, geo FROM obs.fiche 
								INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
								INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
								INNER JOIN referentiel.mgrs10 ON mgrs10.mgrs = coordonnee.utm
								INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
								WHERE (obs.cdref = :cdref OR genre = :genre) AND floutage <= 2 AND statutobs != 'No'
								GROUP BY genre, utm, geo) as t2
							ON t2.utm = utm AND t2.d = t1.date1
							ORDER BY date1 ");
		$req->bindValue(':cdref', $cdnom);
	}
	else
	{
		$req = $bdd->prepare("SELECT DISTINCT EXTRACT(YEAR FROM date1) AS annee, to_char(date1, 'TMmonth') AS mois, utm AS id, geo FROM obs.fiche AS t1
							INNER JOIN (SELECT MAX(date1) as d, utm, geo FROM obs.fiche 
								INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
								INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
								INNER JOIN referentiel.mgrs10 ON mgrs10.mgrs = coordonnee.utm
								INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
								WHERE genre = :genre AND floutage <= 2 AND statutobs != 'No'
								GROUP BY utm, geo) as t2
							ON t2.utm = utm AND t2.d = t1.date1 ");
	}
	$req->bindValue(':genre', $genre);
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;
}	
function mgrs()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT mgrs AS id, geo FROM referentiel.mgrs10 ");
	$utm = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $utm;
}
function cartol93($cdnom,$nomvar,$nbgenresp,$genre,$droit)
{
	$strQuery = "SELECT DISTINCT date1, EXTRACT(YEAR FROM date1) AS annee, to_char(date1, 'TMmonth') AS mois, genre, com, codel93 AS id FROM obs.fiche AS t1
					INNER JOIN (SELECT MAX(date1) as d, genre, com, codel93 FROM obs.fiche 
						INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
						INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						LEFT JOIN referentiel.similaire ON similaire.com = obs.cdref";
	$strQuery .= ($nbgenresp > 0) ? " WHERE (obs.cdref = :cdref OR genre = :genre) AND statutobs != 'No' AND (validation = 1 OR validation = 2)" : " WHERE genre = :genre AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= " AND floutage <= 2"; }
	$strQuery .= " GROUP BY genre, com, codel93) AS t2 ON t2.codel93 = codel93 AND t2.d = t1.date1";
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':genre', $genre);
	if($nbgenresp > 0) { $req->bindValue(':cdref', $cdnom); }
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;
}
function maillel93()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT codel93 AS id FROM referentiel.maillel93 ");
	$l93 = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $l93;
}	
function carto5l93($cdnom,$nomvar,$nbgenresp,$genre,$droit)
{
	$strQuery = "SELECT DISTINCT date1, EXTRACT(YEAR FROM date1) AS annee, to_char(date1, 'TMmonth') AS mois, genre, com, codel935 AS id FROM obs.fiche AS t1
					INNER JOIN (SELECT MAX(date1) as d, genre, com, codel935 FROM obs.fiche 
						INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
						INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						LEFT JOIN referentiel.similaire ON similaire.com = obs.cdref";
	$strQuery .= ($nbgenresp > 0) ? " WHERE (obs.cdref = :cdref OR genre = :genre) AND statutobs != 'No' AND (validation = 1 OR validation = 2)" : " WHERE genre = :genre AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= " AND floutage = 0"; }
	$strQuery .= " GROUP BY genre, com, codel935) AS t2 ON t2.codel935 = codel935 AND t2.d = t1.date1";
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':genre', $genre);
	if($nbgenresp > 0) { $req->bindValue(':cdref', $cdnom); }
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;	
}

if(isset($_POST['emprise']) && isset($_POST['choixcarte']) && isset($_POST['cdnom'])) 
{
	$emprise = $_POST['emprise'];
	$choix = $_POST['choixcarte'];
	$cdnom = $_POST['cdnom'];
	$nomvar = $_POST['nomvar'];
	$genre = $_POST['nom'];
	$nbgenresp = $_POST['nbgenresp'];	
	$anneeencours = date('Y');
	$utm = $_POST['utm'];
	$droit = (isset($_SESSION['droits']) && $_SESSION['droits'] >= 2) ? 'oui' : 'non';
	
	$json_site = file_get_contents('../../../../json/site.json');
	$rjson = json_decode($json_site, true);
	if(isset($rjson['fiche']['classefiche'])) 
	{
		$nbclasse = count($rjson['fiche']['classefiche']);
		foreach($rjson['fiche']['classefiche'] as $n)
		{		
			if($n['classe'] == 'classe1') {	$cou1 = $n['couleur']; }
			elseif($n['classe'] == 'classe2') { $cou2 = $n['couleur']; $an2 = $n['annee']; }
			elseif($n['classe'] == 'classe3') { $cou3 = $n['couleur']; $an3 = $n['annee']; }
			elseif($n['classe'] == 'classe4') { $cou4 = $n['couleur']; $an4 = $n['annee']; }
			elseif($n['classe'] == 'classe5') { $cou5 = $n['couleur']; $an5 = $n['annee']; }
			elseif($n['classe'] == 'classe6') { $cou6 = $n['couleur']; $an6 = $n['annee']; }
		}		
	}
	if(isset($rjson['fiche']['legendenouv'])) 
	{
		$cnouv = $rjson['fiche']['legendenouv'];
	}
	if($choix == 'commune')
	{
		$tabobs = ($emprise != 'fr') ? cartocommune($cdnom,$nomvar,$nbgenresp,$genre,$droit) : cartodep($cdnom,$nomvar,$nbgenresp,$genre);
	}
	elseif($choix == 'maille')
	{
		$tabobs = ($utm == 'oui') ? cartoutm($cdnom,$nomvar,$nbgenresp,$genre) : cartol93($cdnom,$nomvar,$nbgenresp,$genre,$droit);
	}
	elseif($choix == 'maille5')
	{
		$tabobs = carto5l93($cdnom,$nomvar,$nbgenresp,$genre,$droit);
	}
	if(count($tabobs) > 0)
	{
		foreach($tabobs as $n)
		{
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
				if($n['annee'] == $anneeencours) { $couleur = $cou1; }
				elseif ($n['annee'] >= $an2) {$couleur = $cou2;}
				elseif ($n['annee'] < $an2 && $n['annee'] >= $an3) {$couleur = $cou3;}
				elseif ($n['annee'] < $an3 && $n['annee'] >= $an4) {$couleur = $cou4;}
				elseif ($n['annee'] < $an5) {$couleur = $cou5;}					
			}
			elseif($nbclasse == 6)
			{
				if($n['annee'] == $anneeencours) { $couleur = $cou1; }
				elseif ($n['annee'] >= $an2) {$couleur = $cou2;}
				elseif ($n['annee'] < $an2 && $n['annee'] >= $an3) {$couleur = $cou3;}
				elseif ($n['annee'] < $an3 && $n['annee'] >= $an4) {$couleur = $cou4;}
				elseif ($n['annee'] < $an4 && $n['annee'] >= $an5) {$couleur = $cou5;}
				elseif ($n['annee'] < $an6) {$couleur = $cou6;}					
			}
			$info = 'Dernière donnée en <b>'.$n['mois'].' '.$n['annee'].'</b>';
			if($emprise != 'fr' && $choix != 'maille5')
			{
				$code[] = $n['id'];
			}
			if($choix == 'commune')
			{
				$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
				$feature['properties']['id'] = $n['id'];
				$feature['geometry'] = array('type' => $n['poly'], 'coordinates' => $n['geojson']);
				$resultats['features'][] = $feature;
				$carte['tous'][] = ['nom'=>$n['emp'], 'id'=>$n['id'], 'color'=>$couleur, 'info'=>$info];
				if(empty($n['genre'])) { $carte['sp'][] = ['nom'=>$n['emp'], 'id'=>$n['id'], 'color'=>$couleur, 'info'=>$info]; }
				if(!empty($n['genre']) && empty($n['com'])) { $carte['deter'][] = ['nom'=>$n['emp'], 'id'=>$n['id'], 'color'=>$couleur, 'info'=>$info]; }
				if(!empty($n['genre']) && !empty($n['com'])) { $carte['c']['c'.$n['com']][] = ['nom'=>$n['emp'], 'id'=>$n['id'], 'color'=>$couleur, 'info'=>$info]; }
			}
			elseif($choix == 'maille' && $utm == 'oui')
			{
				$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
				$feature['properties']['id'] = $n['id'];
				$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => $n['geo']);
				$resultats['features'][] = $feature;
				$carte['tous'][] = array("nom"=>$n['id'], "id"=>$n['id'], "color"=>$couleur, "info"=>$info);
				if($nbgenresp > 0)
				{
					if($n['genre'] == '')
					{
						$carte['sp'][] = array("nom"=>$n['id'], "id"=>$n['id'], "color"=>$couleur, "info"=>$info);
					}
					else
					{
						$carte['deter'][] = array("nom"=>$n['id'], "id"=>$n['id'], "color"=>$couleur, "info"=>$info);
					}
				}
				$retour['maille'] = 'oui';
			}
			elseif($choix == 'maille' && $utm != 'oui')
			{
				$xg = substr($n['id'], 1, -4)*10000;
				$yb = substr($n['id'], 5)*10000;
				$xd = $xg + 10000;
				$yh = $yb + 10000;
				$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
				$feature['properties']['id'] = $n['id'];
				$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
				$resultats['features'][] = $feature;
				$carte['tous'][] = ['nom'=>$n['id'], 'id'=>$n['id'], 'color'=>$couleur, 'info'=>$info];
				if(empty($n['genre'])) { $carte['sp'][] = ['nom'=>$n['id'], 'id'=>$n['id'], 'color'=>$couleur, 'info'=>$info]; }
				if(!empty($n['genre']) && empty($n['com'])) { $carte['deter'][] = ['nom'=>$n['id'], 'id'=>$n['id'], 'color'=>$couleur, 'info'=>$info]; }
				if(!empty($n['genre']) && !empty($n['com'])) { $carte['c']['c'.$n['com']][] = ['nom'=>$n['id'], 'id'=>$n['id'], 'color'=>$couleur, 'info'=>$info]; }
				$retour['maille'] = 'oui';
			}
			elseif($choix == 'maille5')
			{
				$xg = substr($n['id'], 1, -5) * 1000;
				$yb = substr($n['id'], 6) * 1000;
				$xd = $xg + 5000;
				$yh = $yb + 5000;
				$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
				$feature['properties']['id'] = $n['id'];
				$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
				$resultats['features'][] = $feature;
				$carte['tous'][] = ['nom'=>$n['id'], 'id'=>$n['id'], 'color'=>$couleur, 'info'=>$info];
				if(empty($n['genre'])) { $carte['sp'][] = ['nom'=>$n['id'], 'id'=>$n['id'], 'color'=>$couleur, 'info'=>$info]; }
				if(!empty($n['genre']) && empty($n['com'])) { $carte['deter'][] = ['nom'=>$n['id'], 'id'=>$n['id'], 'color'=>$couleur, 'info'=>$info]; }
				if(!empty($n['genre']) && !empty($n['com'])) { $carte['c']['c'.$n['com']][] = ['nom'=>$n['id'], 'id'=>$n['id'], 'color'=>$couleur, 'info'=>$info]; }
				$retour['maille'] = 'oui';
			}
		}
		unset($tabobs);
	}
	else
	{
		$code[] = '';
	}
	if($emprise != 'fr' && $choix != 'maille5')
	{
		$code = array_flip($code);
	}
	if($choix == 'commune')
	{
		$tabref = ($emprise != 'fr') ? commune() : departement();
	}
	elseif($choix == 'maille' && $emprise != 'fr')
	{
		$tabref = ($utm == 'oui') ? mgrs() : maillel93();
	}
	elseif($choix == 'maille5')
	{
		$tabref = maillel93();
	}
	if($choix == 'commune' || ($choix == 'maille' && $emprise != 'fr' && $choix != 'maille5'))
	{
		$couleur = '#fff';
		$info = 'Aucune donnée ou information non accessible';
		foreach ($tabref as $n)
		{
			if(!isset($code[$n['id']]))
			{
				if($choix == 'commune')
				{
					$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
					$feature['properties']['id'] = $n['id'];
					$feature['geometry'] = array('type' => $n['poly'], 'coordinates' => $n['geojson']);
					$resultats['features'][] = $feature;
					$carte['tous'][] = array("nom"=>$n['emp'], "id"=>$n['id'], "color"=>$couleur, "info"=>$info);
					$retour['maille'] = 'non';
				}
				elseif($choix == 'maille' && $utm == 'oui')
				{
					$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
					$feature['properties']['id'] = $n['id'];
					$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => $n['geo']);
					$resultats['features'][] = $feature;
					$carte['tous'][] = array("nom"=>$n['id'], "id"=>$n['id'], "color"=>$couleur, "info"=>$info);
					$retour['maille'] = 'oui';
				}
				elseif($choix == 'maille' && $utm != 'oui')
				{
					$xg = substr($n['id'], 1, -4)*10000;
					$yb = substr($n['id'], 5)*10000;
					$xd = $xg + 10000;
					$yh = $yb + 10000;
					$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
					$feature['properties']['id'] = $n['id'];
					$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
					$resultats['features'][] = $feature;
					$carte['tous'][] = array("nom"=>$n['id'], "id"=>$n['id'], "color"=>$couleur, "info"=>$info);
					$retour['maille'] = 'oui';
				}
			}			
		}
		if($choix == 'commune' || ($choix == 'maille' && $utm == 'oui'))
		{
			$tmpcarto = json_encode($resultats, JSON_NUMERIC_CHECK);
			$tmpcarto = str_replace('"[','[',$tmpcarto);
			$tmpcarto = str_replace(']"',']',$tmpcarto);
			$resultats = json_decode($tmpcarto);
		}
	}
	elseif($choix == 'maille5')
	{
		foreach($tabref as $n)
		{
			$xg = substr($n['id'], 1, -4)*10000;
			$yb = substr($n['id'], 5)*10000;
			$xd = $xg + 10000;
			$yh = $yb + 10000;
			$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
			$feature['properties']['cd'] = $n['id'];
			$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
			$maille10['features'][] = $feature;		
		}		
		$retour['maille10'] = $maille10;
		$retour['maille5'] = 'oui';
		$retour['maille'] = 'oui';		
	}
	unset($tabref);
	
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