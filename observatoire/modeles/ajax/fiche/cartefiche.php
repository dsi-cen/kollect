<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function determinateur($iddet)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT observateur FROM referentiel.observateur WHERE idobser = :iddet");
	$req->bindValue(':iddet', $iddet);
	$req->execute();
	$obs = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	$det = '<br /><b>Déterminateur : </b>'.$obs['observateur'];
	return $det;
}
function listeobservateur($idfiche,$observateur)
{
	$obs2[] = $observateur;
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT observateur FROM obs.plusobser						
						INNER JOIN referentiel.observateur ON observateur.idobser = plusobser.idobser
						WHERE idfiche = :idfiche ");
	$req->bindValue(':idfiche', $idfiche);
	$req->execute();
	$obs3 = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	foreach($obs3 as $n)
	{
		$obs2[] = $n['observateur'];
	}	
	$obs = implode(", ", $obs2);
	return $obs;
}
function cartocommune($cdnom,$rang,$nomvar,$droit)
{
	$strQuery = "WITH sel AS (SELECT DISTINCT EXTRACT(YEAR FROM MAX(date1)) AS annee, to_char(MAX(date1), 'TMmonth') AS mois, MAX(date1) AS d, fiche.codecom, commune, poly, geojson FROM obs.fiche
								INNER JOIN obs.obs USING(idfiche)
								INNER JOIN referentiel.commune ON commune.codecom = fiche.codecom";
	if($rang == 'oui') { $strQuery .= " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref"; }
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND statutobs != 'No' AND (validation = 1 OR validation = 2)" : " WHERE obs.cdref = :cdref AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= " AND floutage <= 1"; }
	$strQuery .= " WHERE geojson IS NOT NULL GROUP BY fiche.codecom, commune, poly, geojson)";
	$strQuery .= " SELECT annee, mois, sel.codecom AS id, fiche.idfiche, commune AS emp, fiche.idobser, iddet, plusobser, observateur, poly, geojson FROM sel 
							INNER JOIN obs.fiche ON	sel.codecom = fiche.codecom AND obs.fiche.date1 = sel.d
							INNER JOIN obs.obs USING(idfiche)";
	if($rang == 'oui') { $strQuery .= " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref"; }
	$strQuery .= " INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser";
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND statutobs != 'No' AND (validation = 1 OR validation = 2)" : " WHERE obs.cdref = :cdref AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= " AND floutage <= 1"; }
	$strQuery .= " GROUP BY sel.annee, sel.mois, sel.codecom, sel.commune, sel.poly, sel.geojson, fiche.idobser, iddet, plusobser, fiche.idfiche, observateur";
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;
}
function cartodep($cdnom,$rang,$nomvar)
{
	$strQuery = "WITH sel AS (SELECT DISTINCT EXTRACT(YEAR FROM MAX(date1)) AS annee, to_char(MAX(date1), 'TMmonth') AS mois, MAX(date1) AS d, fiche.iddep, departement, poly, geojson FROM obs.fiche
								INNER JOIN obs.obs USING(idfiche)
								INNER JOIN referentiel.departement USING(iddep)";
	if($rang == 'oui') { $strQuery .= " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref"; }
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND statutobs != 'No' AND (validation = 1 OR validation = 2)" : " WHERE obs.cdref = :cdref AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	$strQuery .= " WHERE geojson IS NOT NULL GROUP BY fiche.iddep, departement, poly, geojson)";
	$strQuery .= " SELECT annee, mois, sel.iddep AS id, fiche.idfiche, departement AS emp, fiche.idobser, iddet, plusobser, observateur, poly, geojson FROM sel 
							INNER JOIN obs.fiche ON	sel.iddep = fiche.iddep AND obs.fiche.date1 = sel.d
							INNER JOIN obs.obs USING(idfiche)";
	if($rang == 'oui') { $strQuery .= " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref"; }
	$strQuery .= " INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser";
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND statutobs != 'No' AND (validation = 1 OR validation = 2)" : " WHERE obs.cdref = :cdref AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	$strQuery .= " GROUP BY sel.annee, sel.mois, sel.iddep, sel.departement, sel.poly, sel.geojson, fiche.idobser, iddet, plusobser, fiche.idfiche, observateur";
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
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
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT codecom AS id, commune AS emp, poly, geojson FROM referentiel.commune WHERE geojson IS NOT NULL ");
	$commune = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $commune;
}
function departement()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT iddep AS id, departement AS emp, poly, geojson FROM referentiel.departement WHERE geojson IS NOT NULL ");
	$commune = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $commune;
}
function cartoutm($cdnom,$rang,$nomvar,$droit)
{
	$strQuery = "WITH sel AS (SELECT DISTINCT EXTRACT(YEAR FROM MAX(date1)) as annee, to_char(MAX(date1), 'TMmonth') as mois, MAX(date1) as d, utm FROM obs.fiche
								INNER JOIN obs.obs USING(idfiche)
								INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord";
	if($rang == 'oui') { $strQuery .= " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref"; }
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND statutobs != 'No' AND utm != '' AND (validation = 1 OR validation = 2)" : " WHERE obs.cdref = :cdref AND statutobs != 'No' AND utm != '' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= " AND floutage <= 2"; }
	$strQuery .= " GROUP BY utm)";
	$strQuery .= " SELECT annee, mois, sel.utm AS id, fiche.idobser, iddet, plusobser, observateur, fiche.idfiche, geo FROM sel 
							INNER JOIN obs.fiche ON obs.fiche.date1 = sel.d
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN obs.coordonnee ON obs.coordonnee.utm = sel.utm AND fiche.idcoord = coordonnee.idcoord
							INNER JOIN referentiel.mgrs10 ON mgrs10.mgrs = sel.utm";							
	if($rang == 'oui') { $strQuery .= " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref"; }
	$strQuery .= " INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser";
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND statutobs != 'No' AND localisation < 2 AND (validation = 1 OR validation = 2)" : " WHERE obs.cdref = :cdref AND statutobs != 'No'  AND localisation < 2 AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= " AND floutage <= 2"; }
	$strQuery .= " GROUP BY sel.annee, sel.mois, sel.d, sel.utm, fiche.idobser, iddet, plusobser, fiche.idfiche, observateur, geo";
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;		
}
function mgrs()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT mgrs AS id, geo FROM referentiel.mgrs10 ");
	$utm = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $utm;
}
function cartol93($cdnom,$rang,$nomvar,$droit)
{
	$strQuery = "WITH sel AS (SELECT DISTINCT EXTRACT(YEAR FROM MAX(date1)) as annee, to_char(MAX(date1), 'TMmonth') as mois, MAX(date1) as d, codel93 FROM obs.fiche
								INNER JOIN obs.obs USING(idfiche)
								INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord";
	if($rang == 'oui') { $strQuery .= " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref"; }
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND statutobs != 'No' AND codel93 != '' AND (validation = 1 OR validation = 2)" : " WHERE obs.cdref = :cdref AND statutobs != 'No' AND codel93 != '' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= " AND floutage <= 2"; }
	$strQuery .= " GROUP BY codel93)";
	$strQuery .= " SELECT annee, mois, sel.codel93 AS id, fiche.idobser, iddet, plusobser, observateur, fiche.idfiche FROM sel 
							INNER JOIN obs.fiche ON obs.fiche.date1 = sel.d
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN obs.coordonnee ON obs.coordonnee.codel93 = sel.codel93 AND fiche.idcoord = coordonnee.idcoord";
	if($rang == 'oui') { $strQuery .= " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref"; }
	$strQuery .= " INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser";
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND statutobs != 'No' AND localisation < 2 AND (validation = 1 OR validation = 2)" : " WHERE obs.cdref = :cdref AND statutobs != 'No'  AND localisation < 2 AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= " AND floutage <= 2"; }
	$strQuery .= " GROUP BY sel.annee, sel.mois, sel.d, sel.codel93, fiche.idobser, iddet, plusobser, fiche.idfiche, observateur";
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;		
}
function maillel93()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT codel93 AS id FROM referentiel.maillel93 ");
	$l93 = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $l93;
}	
function carto5l93($cdnom,$rang,$nomvar,$droit)
{
	$strQuery = "WITH sel AS (SELECT DISTINCT EXTRACT(YEAR FROM MAX(date1)) as annee, to_char(MAX(date1), 'TMmonth') as mois, MAX(date1) as d, codel935 FROM obs.fiche
								INNER JOIN obs.obs USING(idfiche)
								INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord";
	if($rang == 'oui') { $strQuery .= " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref"; }
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND statutobs != 'No' AND codel935 != '' AND (validation = 1 OR validation = 2)" : " WHERE obs.cdref = :cdref AND statutobs != 'No' AND codel935 != '' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= " AND floutage = 0"; }
	$strQuery .= " GROUP BY codel935)";
	$strQuery .= " SELECT annee, mois, sel.codel935 AS id, fiche.idobser, iddet, plusobser, observateur, fiche.idfiche FROM sel 
							INNER JOIN obs.fiche ON obs.fiche.date1 = sel.d
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN obs.coordonnee ON obs.coordonnee.codel935 = sel.codel935 AND fiche.idcoord = coordonnee.idcoord";
	if($rang == 'oui') { $strQuery .= " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref"; }
	$strQuery .= " INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser";
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND statutobs != 'No' AND localisation < 2 AND (validation = 1 OR validation = 2)" : " WHERE obs.cdref = :cdref AND statutobs != 'No' AND localisation < 2 AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= " AND floutage = 0"; }
	$strQuery .= " GROUP BY sel.annee, sel.mois, sel.d, sel.codel935, fiche.idobser, iddet, plusobser, fiche.idfiche, observateur";
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;	
}
function cartocomnouv($cdnom,$rang,$nomvar,$anneeencours,$droit)
{
	$strQuery = "WITH sel AS (SELECT EXTRACT(YEAR FROM MIN(date1)) AS annee, fiche.codecom FROM obs.fiche INNER JOIN obs.obs USING(idfiche)";
	if($rang == 'oui') { $strQuery .= " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref"; }
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND statutobs != 'No' AND (validation = 1 OR validation = 2)" : " WHERE obs.cdref = :cdref AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= " AND floutage <= 1"; }
	$strQuery .= " GROUP BY codecom ) SELECT sel.codecom AS id FROM sel WHERE annee = :annee";
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdref', $cdnom);
	$req->bindValue(':annee', $anneeencours);
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;	
}
function cartodepnouv($cdnom,$rang,$nomvar,$anneeencours)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($rang == 'oui')
	{
		$req = $bdd->prepare("WITH sel AS (SELECT EXTRACT(YEAR FROM MIN(date1)) AS annee, fiche.iddep FROM obs.fiche
								INNER JOIN obs.obs USING(idfiche)
								INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
								WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND floutage <= 3 AND statutobs != 'No' 
								GROUP BY iddep
							)
							SELECT sel.iddep AS id FROM sel
							WHERE annee = :annee ");
	}
	if($rang == 'non')
	{
		$req = $bdd->prepare("WITH sel AS (SELECT EXTRACT(YEAR FROM MIN(date1)) AS annee, fiche.iddep FROM obs.fiche
								INNER JOIN obs.obs USING(idfiche)
								WHERE obs.cdref = :cdref AND floutage <= 3 AND statutobs != 'No' 
								GROUP BY iddep
							)
							SELECT sel.iddep AS id FROM sel
							WHERE annee = :annee ");
	}
	$req->bindValue(':cdref', $cdnom);
	$req->bindValue(':annee', $anneeencours);
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;
}
function cartol93nouv($cdnom,$rang,$nomvar,$anneeencours,$droit)
{
	$strQuery = "WITH sel AS (SELECT EXTRACT(YEAR FROM MIN(date1)) AS annee, codel93 FROM obs.fiche INNER JOIN obs.obs USING(idfiche) INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord";
	if($rang == 'oui') { $strQuery .= " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref"; }
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND statutobs != 'No' AND (validation = 1 OR validation = 2)" : " WHERE obs.cdref = :cdref AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= " AND floutage <= 2"; }
	$strQuery .= " GROUP BY codel93 ) SELECT sel.codel93 AS id FROM sel WHERE annee = :annee";
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdref', $cdnom);
	$req->bindValue(':annee', $anneeencours);
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;	
}
function cartol935nouv($cdnom,$rang,$nomvar,$anneeencours,$droit)
{
	$strQuery = "WITH sel AS (SELECT EXTRACT(YEAR FROM MIN(date1)) AS annee, codel935 FROM obs.fiche INNER JOIN obs.obs USING(idfiche) INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord";
	if($rang == 'oui') { $strQuery .= " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref"; }
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND statutobs != 'No' AND (validation = 1 OR validation = 2)" : " WHERE obs.cdref = :cdref AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= " AND floutage = 0"; }
	$strQuery .= " GROUP BY codel935 ) SELECT sel.codel935 AS id FROM sel WHERE annee = :annee";
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdref', $cdnom);
	$req->bindValue(':annee', $anneeencours);
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;	
}
function cartoutmnouv($cdnom,$rang,$nomvar,$anneeencours,$droit)
{
	$strQuery = "WITH sel AS (SELECT EXTRACT(YEAR FROM MIN(date1)) AS annee, utm FROM obs.fiche INNER JOIN obs.obs USING(idfiche) INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord";
	if($rang == 'oui') { $strQuery .= " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref"; }
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND statutobs != 'No' AND (validation = 1 OR validation = 2)" : " WHERE obs.cdref = :cdref AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= " AND floutage <= 2"; }
	$strQuery .= " GROUP BY utm ) SELECT sel.utm AS id FROM sel WHERE annee = :annee";
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdref', $cdnom);
	$req->bindValue(':annee', $anneeencours);
	$req->execute();
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;
}

if(isset($_POST['emprise']) && isset($_POST['choixcarte']) && isset($_POST['cdnom'])) 
{
	$emprise = $_POST['emprise'];
	$choix = $_POST['choixcarte'];
	$utm = $_POST['utm'];
	$cdnom = $_POST['cdnom'];
	$rang = $_POST['rang'];
	$nomvar = $_POST['nomvar'];
	$anneeencours = date('Y');
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
		$tabobs = ($emprise != 'fr') ? cartocommune($cdnom,$rang,$nomvar,$droit) : cartodep($cdnom,$rang,$nomvar);
		if(isset($rjson['fiche']['legendenouv']))
		{
			$tabnouv = ($emprise != 'fr') ? cartocomnouv($cdnom,$rang,$nomvar,$anneeencours,$droit) : cartodepnouv($cdnom,$rang,$nomvar,$anneeencours);			
		}		
	}
	elseif($choix == 'maille')
	{
		$tabobs = ($utm == 'oui') ? cartoutm($cdnom,$rang,$nomvar,$droit) : cartol93($cdnom,$rang,$nomvar,$droit);
		if(isset($rjson['fiche']['legendenouv']))
		{
			$tabnouv = ($utm == 'oui') ? cartoutmnouv($cdnom,$rang,$nomvar,$anneeencours,$droit) : cartol93nouv($cdnom,$rang,$nomvar,$anneeencours,$droit);			
		}
	}
	elseif($choix == 'maille5')
	{
		$tabobs = carto5l93($cdnom,$rang,$nomvar,$droit);
		if(isset($rjson['fiche']['legendenouv']))
		{
			$tabnouv = cartol935nouv($cdnom,$rang,$nomvar,$anneeencours,$droit);			
		}
	}
	if(isset($tabnouv) && count($tabnouv) > 0)
	{
		$nb = 0;
		foreach ($tabnouv as $n)
		{
			$nb++;
			$codenouv[] = $n['id'];			
		}
		if($choix == 'commune')
		{
			if($emprise != 'fr')
			{
				$libnouv = ($nb > 1) ? $nb.' nouvelles communes en '.$anneeencours : $nb.' nouvelle commune en '.$anneeencours;				
			}
			else
			{
				$libnouv = ($nb > 1) ? $nb.' nouveaux départements en '.$anneeencours : $nb.' nouveau département en '.$anneeencours;
			}
		}
		else
		{
			$libnouv = ($nb > 1) ? $nb.' nouvelles mailles '.$anneeencours : $nb.' nouvelle maille en '.$anneeencours;	
		}
		$retour['nbnouv'] = $libnouv;
		$retour['cnouv'] = $cnouv;
		$codenouv = array_flip($codenouv);
	}
	if(count($tabobs) > 0)
	{
		foreach ($tabobs as $n)
		{
			$det = (($n['iddet'] != $n['idobser']) && ($n['iddet'] != '')) ? determinateur($n['iddet']) : '';
			$obs = ($n['plusobser'] == 'oui') ? listeobservateur($n['idfiche'],$n['observateur']) : $n['observateur'];
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
				if($n['annee'] == $anneeencours) 
				{
					if(isset($codenouv)) { $couleur = (isset($codenouv[$n['id']])) ? $cnouv : $cou1; }
					else { $couleur = $cou1; }
				}
				elseif ($n['annee'] >= $an2) {$couleur = $cou2;}
				elseif ($n['annee'] < $an2 && $n['annee'] >= $an3) {$couleur = $cou3;}
				elseif ($n['annee'] < $an3 && $n['annee'] >= $an4) {$couleur = $cou4;}
				elseif ($n['annee'] < $an5) {$couleur = $cou5;}					
			}
			elseif($nbclasse == 6)
			{
				if($n['annee'] == $anneeencours) 
				{
					if(isset($codenouv)) { $couleur = (isset($codenouv[$n['id']])) ? $cnouv : $cou1; }
					else { $couleur = $cou1; }
				}
				elseif ($n['annee'] >= $an2) {$couleur = $cou2;}
				elseif ($n['annee'] < $an2 && $n['annee'] >= $an3) {$couleur = $cou3;}
				elseif ($n['annee'] < $an3 && $n['annee'] >= $an4) {$couleur = $cou4;}
				elseif ($n['annee'] < $an4 && $n['annee'] >= $an5) {$couleur = $cou5;}
				elseif ($n['annee'] < $an6) {$couleur = $cou6;}					
			}
			$info = 'Dernière donnée en <b>'.$n['mois'].' '.$n['annee'].'</b><br /><b>Observateur(s)</b> : '.$obs.''.$det;
			if(/*$emprise != 'fr' && */$choix != 'maille5')
			{
				$code[] = $n['id'];
			}
			if($choix == 'commune')
			{
				$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
				$feature['properties']['id'] = $n['id'];
				$feature['geometry'] = array('type' => $n['poly'], 'coordinates' => $n['geojson']);
				$resultats['features'][] = $feature;
				$carte[] = array("nom"=>$n['emp'], "id"=>$n['id'], "color"=>$couleur, "info"=>$info);			
			}
			elseif($choix == 'maille' && $utm == 'oui')
			{
				$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
				$feature['properties']['id'] = $n['id'];
				$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => $n['geo']);
				$resultats['features'][] = $feature;
				$carte[] = array("nom"=>$n['id'], "id"=>$n['id'], "color"=>$couleur, "info"=>$info);
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
				$carte[] = array("nom"=>$n['id'], "id"=>$n['id'], "color"=>$couleur, "info"=>$info);
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
				$carte[] = array('nom'=>$n['id'], 'id'=>$n['id'], 'color'=>$couleur, 'info'=>$info);
				$retour['maille'] = 'oui';
			}
		}
		unset($tabobs);
	}
	else
	{
		$code[] = '';
	}
	if($choix != 'maille5')
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
		foreach($tabref as $n)
		{
			if(!isset($code[$n['id']]))
			{
				if($choix == 'commune')
				{
					$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
					$feature['properties']['id'] = $n['id'];
					$feature['geometry'] = array('type' => $n['poly'], 'coordinates' => $n['geojson']);
					$resultats['features'][] = $feature;
					$carte[] = array("nom"=>$n['emp'], "id"=>$n['id'], "color"=>$couleur, "info"=>$info);
					$retour['maille'] = 'non';
				}
				elseif($choix == 'maille' && $utm == 'oui')
				{
					$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
					$feature['properties']['id'] = $n['id'];
					$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => $n['geo']);
					$resultats['features'][] = $feature;
					$carte[] = array("nom"=>$n['id'], "id"=>$n['id'], "color"=>$couleur, "info"=>$info);
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
					$carte[] = array("nom"=>$n['id'], "id"=>$n['id'], "color"=>$couleur, "info"=>$info);
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
