<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
//session_start();
/*
WITH sel AS (SELECT DISTINCT fiche.idfiche, fiche.codecom, commune, site, fiche.iddep, date1, date2, observateur, c.x, c.y, c.lng, c.lat, c.codel93 FROM obs.fiche
INNER JOIN obs.obs USING(idfiche)
INNER JOIN referentiel.commune USING(codecom)
INNER JOIN obs.site USING(idsite)
INNER JOIN obs.coordonnee AS c ON c.idcoord = fiche.idcoord
INNER JOIN referentiel.observateur USING(idobser)
--LEFT JOIN obs.plusobser ON plusobser.idfiche = fiche.idfiche
--where observa = 'lepido' AND date1 > '2017-04-30' --and (observateur.idobser = 120 or plusobser.idobser = 120)
WHERE observa = 'mam'
), sel1 AS 
(SELECT sel.idfiche, idobs, liste.nom, nomvern AS nomfr, sel.codecom, commune, site, iddep, to_char(date1, 'DD/MM/YYYY') AS date1, to_char(date2, 'DD/MM/YYYY') AS date2, CONCAT(sel.observateur, ', ', string_agg(DISTINCT observateur.observateur, ', ')) AS observateur, x, y, lng, lat, codel93, iddet FROM sel
--(SELECT sel.*, idobs, liste.cdnom, liste.nom, nomvern AS nomfr, CONCAT(sel.observateur, ', ', string_agg(DISTINCT observateur.observateur, ', ')) AS observateur, iddet FROM sel
INNER JOIN obs.obs USING(idfiche)
INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
LEFT JOIN obs.plusobser ON plusobser.idfiche = sel.idfiche
LEFT JOIN referentiel.observateur ON observateur.idobser = plusobser.idobser
WHERE observa = 'mam'
GROUP BY sel.idfiche, idobs, sel.codecom, commune, site, iddep, date1, date2, liste.cdnom, liste.nom, nomvern, sel.observateur, x, y, lng, lat, codel93
)
SELECT idobs, sel1.nom, sel1.nomfr, commune, site, date1, date2, sel1.observateur, observateur.observateur AS det, stade.stade, ndiff, male, femelle, denom, nbmin, nbmax, etatbio,
methode, prospection, statutbio, idfiche, codecom, iddep, x, y, lng, lat, codel93 FROM sel1
LEFT JOIN referentiel.observateur ON observateur.idobser = sel1.iddet
INNER JOIN obs.ligneobs AS l USING(idobs)
INNER JOIN referentiel.stade ON stade.idstade = l.stade
INNER JOIN referentiel.methode USING(idmethode)
INNER JOIN referentiel.prospection USING(idpros)
INNER JOIN referentiel.occetatbio USING(idetatbio)
INNER JOIN referentiel.occstatutbio USING(idstbio)

*/
function listeobs($idobser,$observa,$cdnom,$codecom,$idsite,$site,$date1,$date2,$typedate,$vali,$photo,$son,$decade,$poly,$dist,$lat,$lng,$etude)
{
	$where = 'non'; $where1 = 'non';
	$strQuery = "WITH sel AS (SELECT DISTINCT fiche.idfiche, fiche.codecom, commune, site, fiche.iddep, date1, date2, observateur, c.x, c.y, c.lng, c.lat, c.codel93 FROM obs.fiche INNER JOIN obs.obs USING(idfiche) INNER JOIN referentiel.commune USING(codecom) LEFT JOIN obs.site USING(idsite) INNER JOIN obs.coordonnee AS c ON c.idcoord = fiche.idcoord INNER JOIN referentiel.observateur USING(idobser)";
	if(!empty($idobser)) { $strQuery .= " LEFT JOIN obs.plusobser ON plusobser.idfiche = fiche.idfiche"; }
	if($photo == 'oui') { $strQuery .= " INNER JOIN site.photo USING(idobs)"; }
	if($son == 'oui') { $strQuery .= " INNER JOIN site.son USING(idobs)"; }
	if(!empty($poly)) { $strQuery .= " WHERE polygon(path'$poly') @> (c.lng::text || ',' || c.lat::text)::point"; $where = 'oui'; }
	if(!empty($dist)) { $strQuery .= " WHERE (6366*acos(cos(radians(:lat))*cos(radians(c.lat))*cos(radians(c.lng)-radians(:lng))+sin(radians(:lat))*sin(radians(c.lat)))) < :dist"; $where = 'oui'; }
	if(!empty($observa)) { $strQuery .= ($where == 'non') ? " WHERE observa IN($observa)" : " AND observa IN($observa)" ; $where = 'oui'; }
	if(!empty($cdnom)) { $strQuery .= ($where == 'non') ? " WHERE cdref IN($cdnom)" : " AND (cdref IN($cdnom))"; $where = 'oui'; }
	if(!empty($idobser)) { $strQuery .= ($where == 'non') ? " WHERE (observateur.idobser = :idobser OR plusobser.idobser = :idobser)" : " AND (observateur.idobser = :idobser OR plusobser.idobser = :idobser)"; $where = 'oui'; }
	if(!empty($codecom)) { $strQuery .= ($where == 'non') ? " WHERE fiche.codecom IN($codecom)" : " AND (fiche.codecom IN($codecom))"; $where = 'oui'; }
	if(!empty($idsite)) { $strQuery .= ($where == 'non') ? " WHERE fiche.idsite IN($idsite)" : " AND (fiche.idsite IN($idsite))"; $where = 'oui'; }
	if(!empty($site)) { $strQuery .= ($where == 'non') ? " WHERE site ILIKE :site" : " AND (site ILIKE :site)"; $where = 'oui'; }
	if(!empty($typedate) && $typedate == 'obs') { $strQuery .= ($where == 'non') ? " WHERE (date1 >= :date1 AND date1 <= :date2)" : " AND (date1 >= :date1 AND date1 <= :date2)"; $where = 'oui'; }
	if(!empty($typedate) && $typedate == 'saisie') { $strQuery .= ($where == 'non') ? " WHERE (datesaisie >= :date1 AND datesaisie <= :date2)" : " AND (datesaisie >= :date1 AND datesaisie <= :date2)"; $where = 'oui'; }
	if(!empty($decade)) { $strQuery .= ($where == 'non') ? " WHERE decade = :decade" : " AND (decade = :decade)"; $where = 'oui'; }
	if(!empty($vali)) { $strQuery .= ($where == 'non') ? " WHERE validation = :vali" : " AND (validation = :vali)"; $where = 'oui'; }
	if($etude != 0) { $strQuery .= ($where == 'non') ? " WHERE idetude = :etude" : " AND (idetude = :etude)"; $where = 'oui'; }
	$strQuery .= " ), sel1 AS";
	$strQuery .= " (SELECT sel.idfiche, idobs, liste.nom, nomvern AS nomfr, sel.codecom, commune, site, iddep, date1, date2, CONCAT(sel.observateur, ', ', string_agg(DISTINCT observateur.observateur, ', ')) AS observateur, x, y, lng, lat, codel93, iddet,rqobs FROM sel";
	$strQuery .= " INNER JOIN obs.obs USING(idfiche) INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref LEFT JOIN obs.plusobser ON plusobser.idfiche = sel.idfiche LEFT JOIN referentiel.observateur ON observateur.idobser = plusobser.idobser";
	if($photo == 'oui') { $strQuery .= " INNER JOIN site.photo USING(idobs)"; }
	if($son == 'oui') { $strQuery .= " INNER JOIN site.son USING(idobs)"; }
	if(!empty($observa)) { $strQuery .= ($where1 == 'non') ? " WHERE observa IN($observa)" : " AND observa IN($observa)" ; $where1 = 'oui'; }
	if(!empty($cdnom)) { $strQuery .= ($where1 == 'non') ? " WHERE cdref IN($cdnom)" : " AND (cdref IN($cdnom))"; $where1 = 'oui'; }
	if(!empty($vali)) { $strQuery .= ($where1 == 'non') ? " WHERE validation = :vali" : " AND (validation = :vali)"; $where1 = 'oui'; }
	$strQuery .= " GROUP BY sel.idfiche, idobs, sel.codecom, commune, site, iddep, date1, date2, liste.cdnom, liste.nom, nomvern, sel.observateur, x, y, lng, lat, codel93)";
	$strQuery .= " SELECT idobs, sel1.nom, sel1.nomfr, commune, site, date1, to_char(date1, 'DD/MM/YYYY') AS date, to_char(date2, 'DD/MM/YYYY') AS date2, sel1.observateur, observateur.observateur AS det, stade.stade, '' AS nb, ndiff, male, femelle, CASE WHEN denom = 'Co' THEN 'Compté' WHEN denom = 'Es' THEN 'Estimé' WHEN denom = 'NSP' THEN 'Non Rens.' END AS denom, typedenom, nbmin, nbmax, etatbio, methode, prospection, statutbio, idfiche, codecom, iddep, x, y, lng, lat, codel93,rqobs FROM sel1";
	$strQuery .= " LEFT JOIN referentiel.observateur ON observateur.idobser = sel1.iddet INNER JOIN obs.ligneobs AS l USING(idobs) INNER JOIN referentiel.stade ON stade.idstade = l.stade INNER JOIN referentiel.methode USING(idmethode) INNER JOIN referentiel.prospection USING(idpros) INNER JOIN referentiel.occetatbio USING(idetatbio) INNER JOIN referentiel.occstatutbio USING(idstbio) INNER JOIN referentiel.occtype USING(tdenom) ";
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	if(!empty($idobser)) { $req->bindValue(':idobser', $idobser); }	
	if(!empty($site)) { $req->bindValue(':site', '%'.$site.'%'); }
	if(!empty($dist)) { $req->bindValue(':lat', $lat); $req->bindValue(':lng', $lng); $req->bindValue(':dist', $dist); }
	if(!empty($date1)) { $req->bindValue(':date1', $date1); }
	if(!empty($date2)) { $req->bindValue(':date2', $date2); }
	if($etude != 0) { $req->bindValue(':etude', $etude); }
	if(!empty($decade)) { $req->bindValue(':decade', $decade); }
	if(!empty($vali)) { $req->bindValue(':vali', $vali); }
	$req->execute() or die(print_r($bdd->errorInfo()));
	$resultat = $req->fetchAll(PDO::FETCH_NUM);
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
		$l = '<table id="tblexport" class="table table-sm table-striped" cellspacing="0" width="100%">';
		$l .= '<thead>';
		$l .= '<tr><th>Idobs</th><th>Nom</th><th>Nomfr</th><th>Commune</th><th>Site</th><th>Date</th><th>Date2</th><th>Observateur</th><th>Déterminateur</th><th>Stade</th><th>Nb</th><th>Ndiff</th><th>M</th><th>F</th><th>Rq</th><th>Denom</th><th>Type</th><th>Nbmin</th><th>Nbmax</th><th>EtatBio</th><th>Methode</th><th>Prospection</th><th>StatutBio</th><th>Idfiche</th><th>Insee</th><th>Dep</th><th>xL93</th><th>yL93</th><th>Lng</th><th>Lat</th><th>CodeL93</th></tr>';
		$l .= '</thead>';
		$l .= '</table>';
				
		foreach($liste as $n)
		{
			$cnb = $n[12] + $n[13] + $n[14];
			$tridate = ['tri'=>$n[5],'date'=>$n[6]];
			$data[] = [$n[0],$n[1],$n[2],$n[3],$n[4],$tridate,$n[7],$n[8],$n[9],$n[10],$cnb,$n[12],$n[13],$n[14],$n[31],$n[15],$n[16],$n[17],$n[18],$n[19],$n[20],$n[21],$n[22],$n[23],$n[24],$n[25],$n[26],$n[27],$n[28],$n[29],$n[30]];			
		}
		$retour['data'] = $data;
		$retour['tblok'] = 'oui';
	}
	else
	{
		$l = 'Aucune observation pour ces critères';
		
	}
	
	$retour['tbl'] = $l;
	$retour['statut'] = 'Oui';	
	echo json_encode($retour);
}	