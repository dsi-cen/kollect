<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();
/*
SELECT x, y, nom, nomvern AS nomfr, site, to_char(date1, 'DD/MM/YYYY') AS date1 FROM obs.fiche
INNER JOIN obs.coordonnee USING(idcoord)
INNER JOIN obs.obs USING(idfiche)
LEFT JOIN obs.site ON site.idsite = fiche.idsite
INNER JOIN referentiel.liste ON obs.cdref = liste.cdnom
WHERE observa = 'mam'
*/
function listeobs($idobser,$observa,$cdnom,$codecom,$idsite,$site,$date1,$date2,$typedate,$vali,$photo,$son,$decade,$poly,$dist,$lat,$lng,$etude)
{
	$where = 'non';
	$strQuery = "SELECT x, y, nom, nomvern AS nomfr, site, to_char(date1, 'DD/MM/YYYY') AS date1 FROM obs.fiche";
	$strQuery .= " INNER JOIN obs.coordonnee USING(idcoord) INNER JOIN obs.obs USING(idfiche) LEFT JOIN obs.site ON site.idsite = fiche.idsite INNER JOIN referentiel.liste ON obs.cdref = liste.cdnom";
	if(!empty($idobser)) { $strQuery .= " LEFT JOIN obs.plusobser ON plusobser.idfiche = fiche.idfiche"; }
	if($photo == 'oui') { $strQuery .= " INNER JOIN site.photo USING(idobs)"; }
	if($son == 'oui') { $strQuery .= " INNER JOIN site.son USING(idobs)"; }
	if(!empty($poly)) { $strQuery .= " WHERE polygon(path'$poly') @> (lng::text || ',' || lat::text)::point"; $where = 'oui'; }
	if(!empty($dist)) { $strQuery .= " WHERE (6366*acos(cos(radians(:lat))*cos(radians(lat))*cos(radians(lng)-radians(:lng))+sin(radians(:lat))*sin(radians(lat)))) < :dist"; $where = 'oui'; }
	if(!empty($observa)) { $strQuery .= ($where == 'non') ? " WHERE observa IN($observa)" : " AND observa IN($observa)" ; $where = 'oui'; }
	if(!empty($cdnom)) { $strQuery .= ($where == 'non') ? " WHERE cdref IN($cdnom)" : " AND (cdref IN($cdnom))"; $where = 'oui'; }
	if(!empty($idobser)) { $strQuery .= ($where == 'non') ? " WHERE (fiche.idobser = :idobser OR plusobser.idobser = :idobser)" : " AND (fiche.idobser = :idobser OR plusobser.idobser = :idobser)"; $where = 'oui'; }
	if(!empty($codecom)) { $strQuery .= ($where == 'non') ? " WHERE fiche.codecom IN($codecom)" : " AND (fiche.codecom IN($codecom))"; $where = 'oui'; }
	if(!empty($idsite)) { $strQuery .= ($where == 'non') ? " WHERE fiche.idsite IN($idsite)" : " AND (fiche.idsite IN($idsite))"; $where = 'oui'; }
	if(!empty($site)) { $strQuery .= ($where == 'non') ? " WHERE site ILIKE :site" : " AND (site ILIKE :site)"; $where = 'oui'; }
	if(!empty($typedate) && $typedate == 'obs') { $strQuery .= ($where == 'non') ? " WHERE (date1 >= :date1 AND date1 <= :date2)" : " AND (date1 >= :date1 AND date1 <= :date2)"; $where = 'oui'; }
	if(!empty($typedate) && $typedate == 'saisie') { $strQuery .= ($where == 'non') ? " WHERE (datesaisie >= :date1 AND datesaisie <= :date2)" : " AND (datesaisie >= :date1 AND datesaisie <= :date2)"; $where = 'oui'; }
	if(!empty($decade)) { $strQuery .= ($where == 'non') ? " WHERE decade = :decade" : " AND (decade = :decade)"; $where = 'oui'; }
	if(!empty($vali)) { $strQuery .= ($where == 'non') ? " WHERE validation = :vali" : " AND (validation = :vali)"; $where = 'oui'; }
	if($etude != 0) { $strQuery .= ($where == 'non') ? " WHERE idetude = :etude" : " AND (idetude = :etude)"; $where = 'oui'; }
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	if(!empty($idobser)) { $req->bindValue(':idobser', $idobser); }	
	if(!empty($site)) { $req->bindValue(':site', '%'.$site.'%'); }
	if(!empty($dist)) { $req->bindValue(':lat', $lat); $req->bindValue(':lng', $lng); $req->bindValue(':dist', $dist); }
	if(!empty($date1)) { $req->bindValue(':date1', $date1); }
	if(!empty($date2)) { $req->bindValue(':date2', $date2); }
	if($etude != 0) { $req->bindValue(':etude', $etude); }
	if(!empty($decade)) { $req->bindValue(':decade', $decade); }
	if(!empty($vali)) { $req->bindValue(':vali', $vali); }
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['choixtax']) && isset($_POST['choixloca']))
{
	$idobser = $_POST['idobser'];
	$choixtax = $_POST['choixtax'];
	$choixloca = $_POST['choixloca'];
	$photo = $_POST['photo'];
	$son = $_POST['son'];
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	$etude = $_POST['etude'];
	
	$observa = null; $cdnom = null; $loca = null; $codecom = null; $idsite = null; $site = null; $poly = null; $dist = null;
	if(!empty($choixtax))
	{
		$observa = ($choixtax == 'observa') ? $_POST['observa'] : null;
		$cdnom = (!empty($_POST['cdnom'])) ? $_POST['cdnom'] : null;		
	}
	if(!empty($choixloca))
	{
		$codecom = ($choixloca == 'commune') ? $_POST['loca'] : null;
		$idsite = ($choixloca == 'site') ? $_POST['loca'] : null;
		$site = ($choixloca == 'sitee') ? $_POST['loca'] : null;
		$poly = ($choixloca == 'poly') ? $_POST['loca'] : null;
		$dist = ($choixloca == 'cercle') ? $_POST['loca'] : null;
	}
	$date1 = null; $date2 = null; $typedate = null;
	if(!empty($_POST['date']))
	{
		$typedate = 'obs';
		$date1 = DateTime::createFromFormat('d/m/Y', $_POST['date']);
		$date1 = $date1->format('Y-m-d');
		$date2 = DateTime::createFromFormat('d/m/Y', $_POST['date2']);
		$date2 = $date2->format('Y-m-d');
	}
	if(!empty($_POST['dates']))
	{
		$typedate = 'saisie';
		$date1 = DateTime::createFromFormat('d/m/Y', $_POST['dates']);
		$date1 = $date1->format('Y-m-d');
		$date2 = DateTime::createFromFormat('d/m/Y', $_POST['dates2']);
		$date2 = $date2->format('Y-m-d');
	}
	$vali = ($_POST['vali'] != 'NR') ? $_POST['vali'] : null;
	$decade = ($_POST['decade'] != 'NR') ? $_POST['decade'] : null;
	
	$liste = listeobs($idobser,$observa,$cdnom,$codecom,$idsite,$site,$date1,$date2,$typedate,$vali,$photo,$son,$decade,$poly,$dist,$lat,$lng,$etude);
	
	if($liste != false)
	{
		$crs = array('name'=>'urn:ogc:def:crs:EPSG::2154');
		$resultats['type'] = 'FeatureCollection';
		$resultats['crs'] = array('type'=>'name','properties'=>$crs);
		foreach($liste as $n)
		{
			$feature = array('type' => 'Feature', 'properties' => Null, 'geometry' => Null);
			$feature['properties']['site'] = $n['site'];
			$feature['properties']['nom'] = $n['nom'];
			$feature['properties']['nomfr'] = $n['nomfr'];
			$feature['properties']['date'] = $n['date1'];
			$feature['geometry'] = array('type' => 'Point', 'coordinates' => array(intval($n['x']), intval($n['y'])));
			$resultats['features'][] = $feature;			
		}
		$retour['tbl'] = json_encode($resultats, JSON_NUMERIC_CHECK);
		$retour['statut'] = 'Oui';		
	}
	else
	{
		$retour['statut'] = 'Non';
	}
		
	echo json_encode($retour, JSON_NUMERIC_CHECK);
}	