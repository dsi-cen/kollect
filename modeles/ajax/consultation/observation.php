<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function pagination($nbpage,$pageaffiche)
{
	$prec = $pageaffiche - 1;
	$suiv = $pageaffiche + 1;
	$avdern = $nbpage - 1;
	$adj = 2;
	$listp = '';
	if($nbpage > 1)
	{
		$listp .= '<ul class="pagination">';
		if($pageaffiche == 2)
		{
			$listp .= '<li id="pp1" class="page-item"><span class="page-link curseurlien">&laquo;</span></li>';			
		}
		elseif($pageaffiche > 2)
		{
			$listp .= '<li id="pp'.$prec.'" class="page-item"><span class="page-link curseurlien">&laquo;</span></li>';
		}		
		if($nbpage < 7 + ($adj * 2))
		{
			$listp .= ($pageaffiche == 1) ? '<li id="p1" class="page-item active"><a class="page-link">1</a></li>' : '<li id="p1" class="page-item"><a class="page-link curseurlien">1</a></li>';
			for($i=2; $i<=$nbpage; $i++)
			{
				$listp .= ($i == $pageaffiche) ? '<li id="p'.$i.'" class="page-item active"><a class="page-link">'.$i.'</a></li>' : '<li id="p'.$i.'" class="page-item"><a class="page-link curseurlien">'.$i.'</a></li>';
			}
		}
		else
		{
			if($pageaffiche < 2 + ($adj * 2))
			{
				$listp .= ($pageaffiche == 1) ? '<li id="p1" class="page-item active"><a class="page-link">1</a></li>' : '<li id="p1" class="page-item"><a class="page-link curseurlien">1</a></li>';
				for($i=2; $i <= 4 + ($adj * 2); $i++)
				{
					$listp .= ($i == $pageaffiche) ? '<li id="p'.$i.'" class="page-item active"><a class="page-link">'.$i.'</a></li>' : '<li id="p'.$i.'" class="page-item"><a class="page-link curseurlien">'.$i.'</a></li>';
				}
				$listp .= '<li class="page-item"><span class="page-link">&hellip;</span></li>';
				$listp .= '<li id="p'.$avdern.'" class="page-item"><a class="page-link curseurlien">'.$avdern.'</a></li>';
				$listp .= '<li id="p'.$nbpage.'" class="page-item"><a class="page-link curseurlien">'.$nbpage.'</a></li>';
			}
			elseif((($adj * 2) + 1 < $pageaffiche) && ($pageaffiche < $nbpage - ($adj * 2)))
			{
				$listp .= '<li id="p1" class="page-item"><a class="page-link curseurlien">1</a></li>';
				$listp .= '<li id="p2" class="page-item"><a class="page-link curseurlien">2</a></li>';
				$listp .= '<li class="page-item"><span class="page-link">&hellip;</span></li>';
				for($i = $pageaffiche - $adj; $i <= $pageaffiche + $adj; $i++) 
				{
					$listp .= ($i == $pageaffiche) ? '<li id="p'.$i.'" class="page-item active"><a class="page-link">'.$i.'</a></li>' : '<li id="p'.$i.'" class="page-item"><a class="page-link curseurlien">'.$i.'</a></li>';
				}
				$listp .= '<li class="page-item"><span class="page-link">&hellip;</span></li>';
				$listp .= '<li id="p'.$avdern.'" class="page-item"><a class="page-link curseurlien">'.$avdern.'</a></li>';
				$listp .= '<li id="p'.$nbpage.'" class="page-item"><a class="page-link curseurlien">'.$nbpage.'</a></li>';
			}
			else
			{
				$listp .= '<li id="p1" class="page-item"><a class="page-link curseurlien">1</a></li>';
				$listp .= '<li id="p2" class="page-item"><a class="page-link curseurlien">2</a></li>';
				$listp .= '<li class="page-item"><span class="page-link">&hellip;</span></li>';
				for($i = $nbpage - (2 + ($adj * 2)); $i <= $nbpage; $i++)
				{
					$listp .= ($i == $pageaffiche) ? '<li id="p'.$i.'" class="page-item active"><a class="page-link">'.$i.'</a></li>' : '<li id="p'.$i.'" class="page-item"><a class="page-link curseurlien">'.$i.'</a></li>';
				}
			}			
		}
		if($pageaffiche != $nbpage)
		{
			$listp .= '<li id="pp'.$suiv.'" class="page-item"><span class="page-link curseurlien">&raquo;</span></li>';
		}			
		$listp .= '</ul>';
	}
	return $listp;
}
function cherche_observateur($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT nom, prenom, idm, observateur.idobser FROM obs.plusobser
						INNER JOIN referentiel.observateur ON observateur.idobser = plusobser.idobser
						WHERE idfiche = :idfiche
						ORDER BY idplus ");
	$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
	$req->execute();
	$obsplus = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $obsplus;
}
function listephoto($listefiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT DISTINCT photo.idobs AS photo, son.idobs AS son FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						LEFT JOIN site.photo USING(idobs)
						LEFT JOIN site.son USING(idobs)
						WHERE idfiche IN($listefiche) AND (photo.idobs IS NOT NULL OR son.idobs IS NOT NULL) ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function nbobs($idobser,$observa,$cdnom,$codecom,$idsite,$site,$date1,$date2,$typedate,$vali,$photo,$son,$droit,$decade,$poly,$dist,$lat,$lng,$etude,$orga,$indice,$statut,$typedon,$flou,$pr,$habitat)
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
	if((!empty($idsite) || !empty($codecom) || !empty($site) || !empty($poly) || !empty($dist)) && $droit == 'non') { $strQuery .= ' LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref'; }
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
	if($etude != 0) { $strQuery .= ($where == 'non') ? " WHERE fiche.idetude = :etude" : " AND (fiche.idetude = :etude)"; $where = 'oui'; }
	if($orga != 'NR') { $strQuery .= ($where == 'non') ? " WHERE idorg = :orga" : " AND (idorg = :orga)"; $where = 'oui'; }
	if($typedon != 'NR') { $strQuery .= ($where == 'non') ? " WHERE typedon = :typedon" : " AND (typedon = :typedon)"; $where = 'oui'; }
	if($flou != 'NR') { $strQuery .= ($where == 'non') ? " WHERE floutage = :flou" : " AND (floutage = :flou)"; $where = 'oui'; }
	if($pr != 'NR') { $strQuery .= ($where == 'non') ? " WHERE localisation = :pr" : " AND (localisation = :pr)"; $where = 'oui'; }
	if((!empty($idsite) || !empty($codecom) || !empty($site) || !empty($poly) || !empty($dist)) && $droit == 'non') { $strQuery .= " AND (sensible <= 1 or sensible is null) AND floutage <= 1"; }
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
	//return $nbobs;
	$rreq = $strQuery;
	return array($nbobs, $rreq);
}
function listeobs($idobser,$observa,$cdnom,$codecom,$idsite,$site,$date1,$date2,$typedate,$vali,$photo,$son,$debut,$droit,$decade,$poly,$dist,$lat,$lng,$etude,$orga,$indice,$statut,$typedon,$flou,$pr,$habitat)
{
	$where = 'non';
	$strQuery = "WITH sel AS (SELECT DISTINCT fiche.idfiche, obs.idobs, to_char(date1, 'DD/MM/YYYY') AS datefr, date1, obs.datesaisie, fiche.codecom, fiche.iddep, fiche.idsite, fiche.idobser, validation, obs.observa, obs.cdref, nb, localisation, fiche.floutage, plusobser FROM obs.fiche INNER JOIN obs.obs USING(idfiche)";
	if(!empty($idobser)) { $strQuery .= " INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser LEFT JOIN obs.plusobser ON plusobser.idfiche = fiche.idfiche"; }
	if(!empty($poly) || !empty($dist)) { $strQuery .= " INNER JOIN obs.coordonnee USING(idcoord)"; }
	if(!empty($site)) { $strQuery .= " INNER JOIN obs.site USING(idsite)"; }
	if(!empty($indice)) { $strQuery .= ' INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref'; }
	if(!empty($statut)) { $strQuery .= ' INNER JOIN statut.statut ON statut.cdnom = obs.cdref INNER JOIN statut.statutsite USING(cdprotect)'; }
	if($habitat != 'NR') { $strQuery .= ' INNER JOIN obs.obshab USING(idobs) INNER JOIN referentiel.eunis USING(cdhab)'; }
	if($photo == 'oui') { $strQuery .= " INNER JOIN site.photo USING(idobs)"; }
	if($son == 'oui') { $strQuery .= " INNER JOIN site.son USING(idobs)"; }
	if((!empty($idsite) || !empty($codecom) || !empty($site) || !empty($poly) || !empty($dist)) && $droit == 'non') { $strQuery .= ' LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref'; }
	if(!empty($poly)) { $strQuery .= " WHERE polygon(path'$poly') @> (lng::text || ',' || lat::text)::point"; $where = 'oui'; }
	if(!empty($dist)) { $strQuery .= " WHERE (6366*acos(cos(radians(:lat))*cos(radians(lat))*cos(radians(lng)-radians(:lng))+sin(radians(:lat))*sin(radians(lat)))) < :dist"; $where = 'oui'; }
	if(!empty($observa)) { $strQuery .= ($where == 'non') ? " WHERE obs.observa IN($observa)" : " AND obs.observa IN($observa)" ; $where = 'oui'; }
	if(!empty($cdnom)) { $strQuery .= ($where == 'non') ? " WHERE cdref IN($cdnom)" : " AND (cdref IN($cdnom))"; $where = 'oui'; }
	if(!empty($idobser)) { $strQuery .= ($where == 'non') ? " WHERE (observateur.idobser = :idobser OR plusobser.idobser = :idobser)" : " AND (observateur.idobser = :idobser OR plusobser.idobser = :idobser)"; $where = 'oui'; }
	if(!empty($codecom)) { $strQuery .= ($where == 'non') ? " WHERE fiche.codecom IN($codecom)" : " AND (fiche.codecom IN($codecom))"; $where = 'oui'; }
	if(!empty($idsite)) { $strQuery .= ($where == 'non') ? " WHERE fiche.idsite IN($idsite)" : " AND (fiche.idsite IN($idsite))"; $where = 'oui'; }
	if(!empty($site)) { $strQuery .= ($where == 'non') ? " WHERE site ILIKE :site" : " AND (site ILIKE :site)"; $where = 'oui'; }
	if(!empty($typedate) && $typedate == 'obs') { $strQuery .= ($where == 'non') ? " WHERE (date1 >= :date1 AND date1 <= :date2)" : " AND (date1 >= :date1 AND date1 <= :date2)"; $where = 'oui'; }
	if(!empty($typedate) && $typedate == 'saisie') { $strQuery .= ($where == 'non') ? " WHERE (datesaisie >= :date1 AND datesaisie <= :date2)" : " AND (datesaisie >= :date1 AND datesaisie <= :date2)"; $where = 'oui'; }
	if(!empty($decade)) { $strQuery .= ($where == 'non') ? " WHERE decade = :decade" : " AND (decade = :decade)"; $where = 'oui'; }
	if(!empty($vali)) { $strQuery .= ($where == 'non') ? " WHERE validation = :vali" : " AND (validation = :vali)"; $where = 'oui'; }
	if(!empty($indice)) { $strQuery .= ($where == 'non') ? ' WHERE ir IN('.$indice.')' : ' AND (ir IN('.$indice.'))'; $where = 'oui'; }
	if(!empty($statut)) { $strQuery .= ($where == 'non') ? ' WHERE '.$statut.'' : ' AND ('.$statut.')'; $where = 'oui'; }
	if($habitat != 'NR') { $strQuery .= ($where == 'non') ? " WHERE lbcode LIKE :habitat" : " AND (lbcode LIKE :habitat)"; $where = 'oui'; }
	if($etude != 0) { $strQuery .= ($where == 'non') ? " WHERE fiche.idetude = :etude" : " AND (fiche.idetude = :etude)"; $where = 'oui'; }
	if($orga != 'NR') { $strQuery .= ($where == 'non') ? " WHERE idorg = :orga" : " AND (idorg = :orga)"; $where = 'oui'; }
	if($typedon != 'NR') { $strQuery .= ($where == 'non') ? " WHERE typedon = :typedon" : " AND (typedon = :typedon)"; $where = 'oui'; }
	if($flou != 'NR') { $strQuery .= ($where == 'non') ? " WHERE floutage = :flou" : " AND (floutage = :flou)"; $where = 'oui'; }
	if($pr != 'NR') { $strQuery .= ($where == 'non') ? " WHERE localisation = :pr" : " AND (localisation = :pr)"; $where = 'oui'; }
	if((!empty($idsite) || !empty($codecom) || !empty($site) || !empty($poly) || !empty($dist)) && $droit == 'non') { $strQuery .= " AND (sensible <= 1 OR sensible IS NULL) AND floutage <= 1"; }
	if(!empty($typedate)) { $strQuery .= ($typedate == 'obs') ? " ORDER BY date1 DESC" : " ORDER BY datesaisie DESC"; } else { $strQuery .= " ORDER BY date1 DESC"; }	
	$strQuery .= " LIMIT 10000 OFFSET :deb )";
	$strQuery .= " SELECT sel.idfiche, sel.idobs, sel.datefr, commune, site, sel.iddep, sel.idobser, liste.nom, nomvern, rang, auteur, sel.validation, sensible, sel.observa, sel.cdref, nb, localisation, floutage, observateur.nom AS nomobser, prenom, idm, plusobser, nbcom FROM sel";
	$strQuery .= " LEFT JOIN referentiel.commune ON commune.codecom = sel.codecom INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref INNER JOIN referentiel.observateur ON observateur.idobser = sel.idobser LEFT JOIN referentiel.sensible ON sensible.cdnom = sel.cdref LEFT JOIN site.liencom ON liencom.idobs = sel.idobs LEFT JOIN obs.site ON site.idsite = sel.idsite";
	if(!empty($typedate)) { $strQuery .= ($typedate == 'obs') ? " ORDER BY date1 DESC" : " ORDER BY datesaisie DESC"; } else { $strQuery .= " ORDER BY date1 DESC"; }
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':deb', $debut);
	if(!empty($idobser)) { $req->bindValue(':idobser', $idobser); }	
	if(!empty($site)) { $req->bindValue(':site', '%'.$site.'%'); }
	if(!empty($dist)) { $req->bindValue(':lat', $lat); $req->bindValue(':lng', $lng); $req->bindValue(':dist', $dist); }
	if(!empty($date1)) { $req->bindValue(':date1', $date1); }
	if(!empty($date2)) { $req->bindValue(':date2', $date2); }
	if($etude != 0) { $req->bindValue(':etude', $etude); }
	if(!empty($decade)) { $req->bindValue(':decade', $decade); }
	if($orga != 'NR') { $req->bindValue(':orga', $orga); }
	if($typedon != 'NR') { $req->bindValue(':typedon', $typedon); }
	if($flou != 'NR') { $req->bindValue(':flou', $flou); }
	if($pr != 'NR') { $req->bindValue(':pr', $pr); }
	if($habitat != 'NR') { $req->bindValue(':habitat', $habitat.'%'); }
	if(!empty($vali)) { $req->bindValue(':vali', $vali); }
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	//$rreq = $strQuery;
	//return array($resultat, $rreq);
	return $resultat;
}

function rechercheobservateur($idm)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idobser FROM referentiel.observateur WHERE idm = :idm ");
	$req->bindValue(':idm', $idm);
	$req->execute();
	$idobser = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $idobser;		
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
	
	$json_site = file_get_contents('../../../json/site.json');
	$rjson_site = json_decode($json_site, true);
	
	$demandeur = rechercheobservateur($_SESSION['idmembre']);
	$droit = ((isset($_SESSION['droits']) && $_SESSION['droits'] >= 1) || $_POST['d'] == 'oui' || $demandeur['idobser'] == $idobser) ? 'oui' : 'non';
	
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
	
	$nbobs = nbobs($idobser,$observa,$cdnom,$codecom,$idsite,$site,$date1,$date2,$typedate,$vali,$photo,$son,$droit,$decade,$poly,$dist,$lat,$lng,$etude,$orga,$indice,$statut,$typedon,$flou,$pr,$habitat);
	$retour['nbobs'] = $nbobs[0];
	//$retour['rqnbobs'] = $nbobs[1];
	if($nbobs[0] > 0)
	{
		$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
		
		$nbpage = ceil($nbobs[0]/100);
		if($nbpage > 1)
		{
			$page = intval($_POST['page']);
			$pageaffiche = ($page > $nbpage) ? $nbpage : $page;
			$debut = ($pageaffiche * 100 - 100);
			$retour['pagination'] = pagination($nbpage,$pageaffiche);
		}
		else
		{
			$debut = 0;
		}
		
		$listeobs = listeobs($idobser,$observa,$cdnom,$codecom,$idsite,$site,$date1,$date2,$typedate,$vali,$photo,$son,$debut,$droit,$decade,$poly,$dist,$lat,$lng,$etude,$orga,$indice,$statut,$typedon,$flou,$pr,$habitat);
				
		foreach($listeobs as $n)
		{
			$tabfichetmp[] = $n['idfiche'];
			if($droit == 'non')
			{
				if($n['sensible'] >= 2) { $tabsensible[] = $n['idfiche']; }	
				if($n['sensible'] == 1) { $tabsensible1[] = $n['idfiche']; }
			}
		}
		$listefiche = array_unique($tabfichetmp);
		$listefiche = implode(',', $listefiche);
		$listephoto = listephoto($listefiche);
		
		if(count($listephoto) > 0)
		{
			foreach($listephoto as $n)
			{
				if($n['photo'] != '')
				{
					$photoobs[] = $n['photo']; $okphoto = 'oui';
				}
				if($n['son'] != '')
				{
					$sonobs[] = $n['son']; $okson = 'oui';
				}
			}
			if(isset($okphoto)) { $photoobs = array_flip($photoobs); }
			if(isset($okson)) { $sonobs = array_flip($sonobs); }
		}
		$tabfiche = array_count_values($tabfichetmp);
		$tabsensible = (isset($tabsensible)) ? array_flip($tabsensible) : '';
		$tabsensible1 = (isset($tabsensible1)) ? array_flip($tabsensible1) : '';
		
		foreach($listeobs as $n)
		{
			$plusfiche = (isset($tabfiche[$n['idfiche']]) && ($tabfiche[$n['idfiche']] > 1)) ? $n['idfiche'] : 'non';
			$ouiphoto = (isset($photoobs) && isset($photoobs[$n['idobs']])) ? 'oui' : 'non';
			$ouison = (isset($sonobs) && isset($sonobs[$n['idobs']])) ? 'oui' : 'non';
			if($n['plusobser'] == 'oui')
			{
				$obs2[] = '<a href="index.php?module=infoobser&amp;action=info&amp;idobser='.$n['idobser'].'">'.$n['prenom'].' '.$n['nomobser'].'</a>';
				$obsplus = cherche_observateur($n['idfiche']);
				foreach($obsplus as $o)
				{
					$obs2[] = '<a href="index.php?module=infoobser&amp;action=info&amp;idobser='.$o['idobser'].'">'.$o['prenom'].' '.$o['nom'].'</a>'; 
				}
				$obs = implode(', ', $obs2);
				$obs2 = null;
			}
			else
			{
				$obs = '<a href="index.php?module=infoobser&amp;action=info&amp;idobser='.$n['idobser'].'">'.$n['prenom'].' '.$n['nomobser'].'</a>';
			}
			$comobs = ($n['nbcom'] >= 1) ? $n['nbcom'] : 0;
			$localisation = ($n['floutage'] >= 2 || isset($tabsensible[$n['idfiche']])) ? $n['iddep'] : $n['commune'];
			$locadep = null;
			if($n['floutage'] >= 2 || isset($tabsensible[$n['idfiche']]))
			{
				$affichagelocalisation = '('.$n['iddep'].')';
			}
			elseif($n['floutage'] == 1 || isset($tabsensible1[$n['idfiche']]))
			{
				$affichagelocalisation = $n['commune'];
			}
			else
			{
				$affichagelocalisation = (!empty($n['site'])) ? $n['commune'].', '.$n['site'] : $n['commune'];
			}
			foreach($rjson_site['observatoire'] as $d)
			{
				if($d['nomvar'] == $n['observa'])
				{
					if($d['latin'] == 'oui' && $latin == 'oui') { $afflatin = 'oui'; }
					elseif($d['latin'] == 'oui' && ($latin == 'defaut' || $latin == '')) { $afflatin = 'oui'; }
					elseif($d['latin'] == 'non' && $latin == 'oui') { $afflatin = 'oui'; }
					elseif($d['latin'] == 'non' || $latin == 'non') { $afflatin = 'non'; }
					if ($afflatin == 'oui')
					{	
						if($n['rang'] != 'GN')
						{
							$afflatintab = '<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].'</i></a>';
						}
						else
						{
							$afflatintab = '<a href="observatoire/index.php?module=fiche&amp;action=ficheg&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].' sp.</i></a>';
						}
					}
					else
					{
						if($n['nomvern'] != '')
						{
							$afflatintab = '<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'" class="tbleu" data-toggle="tooltip" data-placement="top" title="'.$n['nom'].'">'.$n['nomvern'].'</a>';
						}
						else
						{
							if($n['rang'] != 'GN')
							{
								$afflatintab = '<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].'</i></a>';
							}
							else
							{
								$afflatintab = '<a href="observatoire/index.php?module=fiche&amp;action=ficheg&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].' sp.</i></a>';
							}
						}											
					}
					//validation
					switch($n['validation'])
					{
						case 1:$clvali = 'val1'; $tolvali = 'Donnée certaine / très probable.'; break;
						case 2:$clvali = 'val2'; $tolvali = 'Donnée probable'; break;
						case 3:$clvali = 'val3'; $tolvali = 'Donnée douteuse'; break;
						case 4:$clvali = 'val4'; $tolvali = 'Donnée invalide'; break;
						case 5:$clvali = 'val5'; $tolvali = 'Validation non réalisable'; break;
						case 6:$clvali = ''; $tolvali = 'En attente de validation'; break;
						case 7:$clvali = ''; $tolvali = 'En attente de validation'; break;
					}
					$tabobs[] = ['latin'=>$afflatin, 'taxon'=>$afflatintab, 'vali'=>$clvali, 'tvali'=>$tolvali, 'datefr'=>$n['datefr'], 'nomlat'=>$n['nom'], 'nomfr'=>$n['nomvern'], 'nb'=>$n['nb'], 'icon'=>$d['icon'], 'loca'=>$localisation, 'afloca'=>$affichagelocalisation, 'obs'=>$obs, 'idobs'=>$n['idobs'], 'idfiche'=>$n['idfiche'], 'flou'=>$n['floutage'], 'com'=>$comobs, 'photo'=>$ouiphoto, 'son'=>$ouison, 'idm'=>$n['idm'], 'plusfiche'=>$plusfiche, 'locadep'=>$locadep, 'idobser'=>$n['idobser']];
				}
			}
		}
		$liste = null;
		$liste .= '<table id="querytable" class="table table-hover table-sm tblobs">';
		$liste .= '<thead><tr><th>Nombre</th><th>Date</th><th>Nom latin</th><th>Commune</th><th>Observateur</th><th></th></tr></thead><tbody>';

		foreach($tabobs as $n)
		{
			$liste .= '<tr>';
			$liste .= (empty($observa) && empty($cdnom)) ? '<td><i class="'.$n['icon'].' fa-15x"></i>' : '<td>';
			$liste .= '&nbsp;<i class="fa fa-check-circle '.$n['vali'].'" data-toggle="tooltip" data-placement="top" title="'.$n['tvali'].'"></i>';
			$liste .= '&nbsp;'.$n['nb'];
			$liste .= '</td>';
			$liste .= '<td>'.$n['datefr'].'</td>';
			$liste .= '<td>'.$n['taxon'].'</td>';
			$liste .= '<td>'.$n['afloca'].'</td>';
			$liste .= '<td>'.$n['obs'].'</td>';
			$liste .= '<td>';
			$liste .= '<i class="fa fa-info-circle text-info curseurlien" data-toggle="modal" data-target="#obs" data-nomfr="'.$n['nomfr'].'" data-nomlat="'.$n['nomlat'].'" data-idobs="'.$n['idobs'].'" data-latin="'.$n['latin'].'" data-photo="'.$n['photo'].'" data-idmor="'.$n['idm'].'"></i>';
			$liste .= '&nbsp;<a href="index.php?module=observation&amp;action=detail&amp;idobs='.$n['idobs'].'" target="_blank"><i class="fa fa-eye text-info"></i></a>';
			if($n['plusfiche'] != 'non')
			{
				$liste .= '&nbsp;<i class="fa fa-list-ol color1 curseurlien" data-toggle="modal" data-target="#fiche" data-idfiche="'.$n['plusfiche'].'"></i>';
			}
			if($n['photo'] == 'oui')
			{
				$liste .= '&nbsp;<i class="fa fa-camera"></i>';
			}
			if($n['son'] == 'oui')
			{
				$liste .= '&nbsp;<i class="fa fa-volume-off"></i>';
			}
			if((isset($_SESSION['idmembre']) && $n['idm'] == $_SESSION['idmembre']) || (isset($_SESSION['virtobs']) && $n['idobser'] == $_SESSION['idmembre']))
			{
				$liste .= '&nbsp;<i class="fa fa-pencil curseurlien text-warning" onclick="modfiche('.$n['idfiche'].')"></i>';
			}
			if($n['com'] == 1)
			{
				$liste .= '&nbsp;<i class="fa fa-comment-o" data-toggle="tooltip" data-placement="top" title="1 commentaire"></i>';
			}
			elseif($n['com'] > 1)
			{
				$liste .= '&nbsp;<i class="fa fa-comments-o" data-toggle="tooltip" data-placement="top" title="Plusieurs commentaires"></i>';
			}
			$liste .= '</td>';
			$liste .= '</tr>';
		}
		$liste .= '</tbody></table>';		
		unset($tabobs);
	}
	else
	{
		$liste = 'Aucune observation pour ces critères';
	}
	
	$retour['listeobs'] = $liste;
	$retour['d'] = ($droit == 'oui') ? 'oui' : 'non';
	$retour['statut'] = 'Oui';
	echo json_encode($retour);
}	
