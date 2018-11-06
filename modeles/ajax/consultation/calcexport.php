<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function nbobs($idobser,$observa,$cdnom,$codecom,$idsite,$site,$date1,$date2,$typedate,$vali,$photo,$son,$decade,$poly,$dist,$lat,$lng,$etude,$orga,$indice,$statut,$typedon,$flou,$pr,$habitat)
{
	$where = 'non';
	$strQuery = 'SELECT COUNT(DISTINCT idobs) AS nb FROM obs.obs INNER JOIN obs.fiche USING(idfiche)';
	if(!empty($idobser)) { $strQuery .= ' LEFT JOIN obs.plusobser ON plusobser.idfiche = fiche.idfiche'; }
	if(!empty($poly) || !empty($dist)) { $strQuery .= " INNER JOIN obs.coordonnee USING(idcoord)"; }
	if(!empty($site)) { $strQuery .= ' INNER JOIN obs.site USING(idsite)'; }
	if(!empty($indice)) { $strQuery .= ' INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref'; }
	if(!empty($statut)) { $strQuery .= ' INNER JOIN statut.statut ON statut.cdnom = obs.cdref INNER JOIN statut.statutsite USING(cdprotect)'; }
	if($habitat != 'NR') { $strQuery .= ' INNER JOIN obs.obshab USING(idobs) INNER JOIN referentiel.eunis USING(cdhab)'; }
	if($photo == 'oui') { $strQuery .= ' INNER JOIN site.photo USING(idobs)'; }
	if($son == 'oui') { $strQuery .= ' INNER JOIN site.son USING(idobs)'; }
	if(!empty($poly)) { $strQuery .= " WHERE polygon(path'$poly') @> (lng::text || ',' || lat::text)::point"; $where = 'oui'; }
	if(!empty($dist)) { $strQuery .= " WHERE (6366*acos(cos(radians(:lat))*cos(radians(lat))*cos(radians(lng)-radians(:lng))+sin(radians(:lat))*sin(radians(lat)))) < :dist"; $where = 'oui'; }
	if(!empty($observa)) { $strQuery .= ($where == 'non') ? " WHERE obs.observa IN($observa)" : " AND obs.observa IN($observa)" ; $where = 'oui'; }
	if(!empty($cdnom)) { $strQuery .= ($where == 'non') ? ' WHERE cdref IN('.$cdnom.')' : ' AND (cdref IN('.$cdnom.'))'; $where = 'oui'; }
	if(!empty($idobser)) { $strQuery .= ($where == 'non') ? " WHERE (fiche.idobser = :idobser OR plusobser.idobser = :idobser)" : " AND (fiche.idobser = :idobser OR plusobser.idobser = :idobser)"; $where = 'oui'; }
	if(!empty($codecom)) { $strQuery .= ($where == 'non') ? " WHERE fiche.codecom IN($codecom)" : " AND (fiche.codecom IN($codecom))"; $where = 'oui'; }
	if(!empty($idsite)) { $strQuery .= ($where == 'non') ? ' WHERE fiche.idsite IN('.$idsite.')' : ' AND (fiche.idsite IN('.$idsite.'))'; $where = 'oui'; }
	if(!empty($site)) { $strQuery .= ($where == 'non') ? " WHERE site ILIKE :site" : " AND (site ILIKE :site)"; $where = 'oui'; }
	if(!empty($typedate) && $typedate == 'obs') { $strQuery .= ($where == 'non') ? " WHERE (date1 >= :date1 AND date1 <= :date2)" : " AND (date1 >= :date1 AND date1 <= :date2)"; $where = 'oui'; }
	if(!empty($typedate) && $typedate == 'saisie') { $strQuery .= ($where == 'non') ? " WHERE (datesaisie >= :date1 AND datesaisie <= :date2)" : " AND (datesaisie >= :date1 AND datesaisie <= :date2)"; $where = 'oui'; }
	if(!empty($decade)) { $strQuery .= ($where == 'non') ? " WHERE decade = :decade" : " AND (decade = :decade)"; $where = 'oui'; }
	if(!empty($vali)) { $strQuery .= ($where == 'non') ? " WHERE validation = :vali" : " AND (validation = :vali)"; $where = 'oui'; }
	if(!empty($indice)) { $strQuery .= ($where == 'non') ? ' WHERE ir IN('.$indice.')' : ' AND (ir IN('.$indice.'))'; $where = 'oui'; }
	if(!empty($statut)) { $strQuery .= ($where == 'non') ? ' WHERE '.$statut.'' : ' AND ('.$statut.')'; $where = 'oui'; }
	if($habitat != 'NR') { $strQuery .= ($where == 'non') ? " WHERE lbcode LIKE :habitat" : " AND (lbcode LIKE :habitat)"; $where = 'oui'; }
	if($etude != 0) { $strQuery .= ($where == 'non') ? " WHERE idetude = :etude" : " AND (idetude = :etude)"; $where = 'oui'; }
	if($orga != 'NR') { $strQuery .= ($where == 'non') ? " WHERE idorg = :orga" : " AND (idorg = :orga)"; $where = 'oui'; }
	if($typedon != 'NR') { $strQuery .= ($where == 'non') ? " WHERE typedon = :typedon" : " AND (typedon = :typedon)"; $where = 'oui'; }
	if($flou != 'NR') { $strQuery .= ($where == 'non') ? " WHERE floutage = :flou" : " AND (floutage = :flou)"; $where = 'oui'; }
	if($pr != 'NR') { $strQuery .= ($where == 'non') ? " WHERE localisation = :pr" : " AND (localisation = :pr)"; $where = 'oui'; }
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	if(!empty($idobser)) { $req->bindValue(':idobser', $idobser); }	
	if(!empty($site)) { $req->bindValue(':site', '%'.$site.'%'); }
	if(!empty($dist)) { $req->bindValue(':lat', $lat); $req->bindValue(':lng', $lng); $req->bindValue(':dist', $dist); }
	if(!empty($date1)) { $req->bindValue(':date1', $date1); }
	if(!empty($date2)) { $req->bindValue(':date2', $date2); }
	if(!empty($decade)) { $req->bindValue(':decade', $decade); }
	if($etude != 0) { $req->bindValue(':etude', $etude); }
	if($orga != 'NR') { $req->bindValue(':orga', $orga); }
	if($typedon != 'NR') { $req->bindValue(':typedon', $typedon); }
	if($flou != 'NR') { $req->bindValue(':flou', $flou); }
	if($pr != 'NR') { $req->bindValue(':pr', $pr); }
	if($habitat != 'NR') { $req->bindValue(':habitat', $habitat.'%'); }
	if(!empty($vali)) { $req->bindValue(':vali', $vali); }
	$req->execute();
	$nbobs = $req->fetchColumn();
	$req->closeCursor();	
	return $nbobs;
}

if(isset($_POST['choixtax']) && isset($_POST['choixloca']))
{
	$idobser = $_POST['idobser'];
	$choixtax = $_POST['choixtax'];
	$choixloca = $_POST['choixloca'];
	$photo = (isset($_POST['photo'])) ? 'oui' : 'non';
	$son = (isset($_POST['son'])) ? 'oui' : 'non';
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	$etude = $_POST['etude'];
	$orga = $_POST['orga'];
	$typedon = $_POST['typedon'];
	$flou = $_POST['flou'];
	$pr = $_POST['pr'];
	$habitat = $_POST['habitat'];
	
	if(!empty($choixtax))
	{
		$observa = ($choixtax == 'observa') ? $_POST['rchoixtax'] : null;
		$cdnom = ($choixtax == 'espece') ? $_POST['rchoixtax'] : null;
	}
	else
	{
		$observa = null; $cdnom = null;
	}	
	if(!empty($choixloca))
	{
		$codecom = ($choixloca == 'commune') ? $_POST['rchoixloca'] : null;
		$idsite = ($choixloca == 'site') ? $_POST['rchoixloca'] : null;
		$site = ($choixloca == 'sitee') ? $_POST['sitee'] : null;
		$poly = ($choixloca == 'poly') ? $_POST['poly'] : null;
		$dist = ($choixloca == 'cercle') ? $_POST['rayon'] : null;
	}
	else
	{
		$codecom = null; $idsite = null; $site = null; $poly = null; $dist = null;
	}
	$date1 = null; $date2 = null; $typedate = null;
	if(isset($_POST['date']) && !empty($_POST['date']))
	{
		$typedate = 'obs';
		$date1 = DateTime::createFromFormat('d/m/Y', $_POST['date']);
		$date1 = $date1->format('Y-m-d');
		$date2 = DateTime::createFromFormat('d/m/Y', $_POST['date2']);
		$date2 = $date2->format('Y-m-d');
	}
	if(isset($_POST['dates']) && !empty($_POST['dates']))
	{
		$typedate = 'saisie';
		$date1 = DateTime::createFromFormat('d/m/Y', $_POST['dates']);
		$date1 = $date1->format('Y-m-d');
		$date2 = DateTime::createFromFormat('d/m/Y', $_POST['dates2']);
		$date2 = $date2->format('Y-m-d');
	}
	$decade = ($_POST['decade'] != 'NR') ? $_POST['decade'] : null;
	$vali = ($_POST['vali'] != 'NR') ? $_POST['vali'] : null;
	$indice = (!empty($_POST['rindice'])) ? $_POST['rindice'] : null;
		
	if(!empty($_POST['rstatut']))
	{
		if(empty($_POST['rlrr']) && empty($_POST['rlre']) && empty($_POST['rlrf']))
		{
			$statut = 'type IN('.$_POST['rstatut'].')';
		}
		else
		{
			$tmp = explode(',', $_POST['rstatut']);
			$statut = null; $con = 'non';
			foreach($tmp as $n)
			{
				if($n == "'LRR'" && !empty($_POST['rlrr']))
				{
					$statut = ($con == 'non') ? 'type = '.$n.' AND lr IN('.$_POST['rlrr'].')' : $statut.' OR (type = '.$n.' AND lr IN('.$_POST['rlrr'].'))';
					$con = 'oui';
				}
				if($n == "'LRE'" && !empty($_POST['rlre']))
				{
					$statut = ($con == 'non') ? 'type = '.$n.' AND lr IN('.$_POST['rlre'].')' : $statut.' OR (type = '.$n.' AND lr IN('.$_POST['rlre'].'))';
					$con = 'oui';
				}
				if($n == "'LRF'" && !empty($_POST['rlrf']))
				{
					$statut = ($con == 'non') ? 'type = '.$n.' AND lr IN('.$_POST['rlrf'].')' : $statut.' OR (type = '.$n.' AND lr IN('.$_POST['rlrf'].'))';
					$con = 'oui';
				}
			}			
		}
	} 
	else
	{
		$statut = null;
	}
	
	$nbobs = nbobs($idobser,$observa,$cdnom,$codecom,$idsite,$site,$date1,$date2,$typedate,$vali,$photo,$son,$decade,$poly,$dist,$lat,$lng,$etude,$orga,$indice,$statut,$typedon,$flou,$pr,$habitat);
	$retour['nbobs'] = $nbobs;	
	$retour['statut'] = 'Oui';	
	
	echo json_encode($retour);
}	