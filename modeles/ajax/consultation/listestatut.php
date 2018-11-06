<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function listestatut($idobser,$observa,$codecom,$idsite,$site,$date1,$date2,$typedate,$vali,$droit,$poly,$dist,$lat,$lng,$decade,$etude)
{
	$where = 'non';
	$strQuery = "WITH sel AS (SELECT COUNT(idobs) AS nbt, cdref FROM obs.obs
					INNER JOIN obs.fiche USING(idfiche)
					INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref";
	$strQuery .= (!empty($observa)) ? " WHERE observa IN($observa) AND (rang = 'ES' OR rang = 'SSES')" :  " WHERE rang = 'ES' OR rang = 'SSES'" ;				
	$strQuery .= "	GROUP BY cdref
				)
				SELECT sel.nbt, COUNT(idobs) AS nb, liste.nom, nomvern, sel.cdref, ir, observa FROM sel
				INNER JOIN obs.obs ON obs.cdref = sel.cdref
				INNER JOIN obs.fiche USING(idfiche)
				INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref";
	if(!empty($idobser)) { $strQuery .= " INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser LEFT JOIN obs.plusobser ON plusobser.idfiche = fiche.idfiche"; }
	if(!empty($site)) { $strQuery .= " INNER JOIN obs.site USING(idsite)"; }
	if(!empty($poly) || !empty($dist)) { $strQuery .= " INNER JOIN obs.coordonnee USING(idcoord)"; }
	if((!empty($idsite) || !empty($codecom) || !empty($site)) && $droit == 'non') { $strQuery .= ' LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref'; }
	if(!empty($poly)) { $strQuery .= " WHERE polygon(path'$poly') @> (lng::text || ',' || lat::text)::point"; $where = 'oui'; }
	if(!empty($dist)) { $strQuery .= " WHERE (6366*acos(cos(radians(:lat))*cos(radians(lat))*cos(radians(lng)-radians(:lng))+sin(radians(:lat))*sin(radians(lat)))) < :dist"; $where = 'oui'; }
	if(!empty($idobser)) { $strQuery .= ($where == 'non') ? " WHERE (observateur.idobser = :idobser OR plusobser.idobser = :idobser)" : " AND (observateur.idobser = :idobser OR plusobser.idobser = :idobser)"; $where = 'oui'; }
	if(!empty($codecom)) { $strQuery .= ($where == 'non') ? " WHERE fiche.codecom IN($codecom)" : " AND (fiche.codecom IN($codecom))"; $where = 'oui'; }
	if(!empty($idsite)) { $strQuery .= ($where == 'non') ? " WHERE fiche.idsite IN($idsite)" : " AND (WHERE fiche.idsite IN($idsite))"; $where = 'oui'; }
	if(!empty($site)) { $strQuery .= ($where == 'non') ? " WHERE site ILIKE :site" : " AND (site ILIKE :site)"; $where = 'oui'; }
	if(!empty($typedate) && $typedate == 'obs') { $strQuery .= ($where == 'non') ? " WHERE (date1 >= :date1 AND date1 <= :date2)" : " AND (date1 >= :date1 AND date1 <= :date2)"; $where = 'oui'; }
	if(!empty($typedate) && $typedate == 'saisie') { $strQuery .= ($where == 'non') ? " WHERE (datesaisie >= :date1 AND datesaisie <= :date2)" : " AND (datesaisie >= :date1 AND datesaisie <= :date2)"; $where = 'oui'; }
	if(!empty($decade)) { $strQuery .= ($where == 'non') ? " WHERE decade = :decade" : " AND (decade = :decade)"; $where = 'oui'; }
	if(!empty($vali)) { $strQuery .= ($where == 'non') ? " WHERE validation = :vali" : " AND (validation = :vali)"; $where = 'oui'; }
	if($etude != 0) { $strQuery .= ($where == 'non') ? " WHERE idetude = :etude" : " AND (idetude = :etude)"; $where = 'oui'; }
	if((!empty($idsite) || !empty($codecom) || !empty($site)) && $droit == 'non') { $strQuery .= " AND (sensible <= 1 or sensible is null) AND floutage <= 1"; }
	$strQuery .= " AND (rang = 'ES' OR rang ='SSES')";
	$strQuery .= " GROUP BY sel.cdref, liste.nom, nomvern, nbt, ir, observa";
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
	if(!empty($vali)) { $req->bindValue(':vali', $vali); }
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherchestatut($observa,$codecom,$idsite,$site,$poly,$dist,$lat,$lng)
{
	$where = 'non';
	$strQuery = "SELECT cdref, type, lr FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN statut.statut ON statut.cdnom = obs.cdref
						INNER JOIN statut.libelle ON libelle.cdprotect = statut.cdprotect";
	if(!empty($site)) { $strQuery .= " INNER JOIN obs.site USING(idsite)"; }
	if(!empty($poly) || !empty($dist)) { $strQuery .= " INNER JOIN obs.coordonnee USING(idcoord)"; }
	if(!empty($poly)) { $strQuery .= " WHERE polygon(path'$poly') @> (lng::text || ',' || lat::text)::point"; $where = 'oui'; }
	if(!empty($dist)) { $strQuery .= " WHERE (6366*acos(cos(radians(:lat))*cos(radians(lat))*cos(radians(lng)-radians(:lng))+sin(radians(:lat))*sin(radians(lat)))) < :dist"; $where = 'oui'; }
	if(!empty($observa)) { $strQuery .= ($where == 'non') ? " WHERE observa IN($observa)" : " AND observa IN($observa)" ; $where = 'oui'; }
	if(!empty($codecom)) { $strQuery .= ($where == 'non') ? " WHERE fiche.codecom IN($codecom)" : " AND (fiche.codecom IN($codecom))"; $where = 'oui'; }
	if(!empty($idsite)) { $strQuery .= ($where == 'non') ? " WHERE fiche.idsite IN($idsite)" : " AND (WHERE fiche.idsite IN($idsite))"; $where = 'oui'; }
	if(!empty($site)) { $strQuery .= ($where == 'non') ? " WHERE site ILIKE :site" : " AND (site ILIKE :site)"; $where = 'oui'; }
	$strQuery .= "	GROUP BY cdref, type, lr ";
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	if(!empty($site)) { $req->bindValue(':site', '%'.$site.'%'); }
	if(!empty($dist)) { $req->bindValue(':lat', $lat); $req->bindValue(':lng', $lng); $req->bindValue(':dist', $dist); }
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
if(isset($_POST['choixloca']))
{
	$idobser = $_POST['idobser'];
	$choixtax = $_POST['choixtax'];
	$choixloca = $_POST['choixloca'];
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	$etude = $_POST['etude'];
	
	$json_site = file_get_contents('../../../json/site.json');
	$rjson_site = json_decode($json_site, true);
	
	$droit = ((isset($_SESSION['droits']) && $_SESSION['droits'] >= 1) || $_POST['d'] == 'oui') ? 'oui' : 'non';
	
	$observa = null; $loca = null; $codecom = null; $idsite = null; $site = null; $poly = null; $dist = null;
	if(!empty($choixtax))
	{
		$observa = $_POST['observa'];				
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
	
	$liste = listestatut($idobser,$observa,$codecom,$idsite,$site,$date1,$date2,$typedate,$vali,$droit,$poly,$dist,$lat,$lng,$decade,$etude);
	
	if($liste != false)
	{
		foreach($rjson_site['observatoire'] as $n)
		{
			$nomobser[$n['nomvar']] = $n['nom'];
		}
		
		$statut = recherchestatut($observa,$codecom,$idsite,$site,$poly,$dist,$lat,$lng);
		foreach($statut as $n)
		{
			if($n['type'] == 'Z') { $znieff[] = $n['cdref']; }		
			if($n['type'] == 'DH') { $dh[] = $n['cdref']; }	
			if($n['type'] == 'PN') { $pn[] = $n['cdref']; }	
			if($n['type'] == 'LRR') { $lr[$n['cdref']] = $n['lr']; }			
		}
		$znieff = (isset($znieff)) ? array_flip($znieff) : '';
		$dh = (isset($dh)) ? array_flip($dh) : '';
		$pn = (isset($pn)) ? array_flip($pn) : '';
		
		$l = null;
		if($droit == 'non')
		{
			$l .= '<p>Sauf espèces sensibles et/ou floutées lors de la saisie</p>';
		}
		$l .= '<table id="statut" class="table table-hover table-sm" cellspacing="0" width="100%">';
		$l .= '<thead><tr><th>Observatoire</th><th>Nom</th><th>Nom français</th><th title="Observation">Nb</th><th title="% des observations totales">%</th><th>DE</th><th>PF</th><th>ZNIEFF</th><th>LR Régionale</th><th>Indice</th></tr></thead>';
		$l .= '<tbody>';
		
		foreach($liste as $n)
		{
			$pourcent = round($n['nb']/$n['nbt'] * 100,1);
			$lznieff = (isset($znieff[$n['cdref']])) ? 'Oui' : '';
			$ldh = (isset($dh[$n['cdref']])) ? 'Oui' : '';
			$lpn = (isset($pn[$n['cdref']])) ? 'Oui' : '';
			$llr = (isset($lr[$n['cdref']])) ? $lr[$n['cdref']] : '';
			$ico = (isset($nomobser[$n['observa']])) ? $nomobser[$n['observa']] : '';
			
			$l .= '<tr>';
			$l .= '<td>'.$ico.'</td><td><a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$n['observa'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].'</i></a></td>';
			$l .= '<td>'.$n['nomvern'].'</td><td>'.$n['nb'].'</td><td>'.$pourcent.'</td><td>'.$ldh.'</td><td>'.$lpn.'</td><td>'.$lznieff.'</td><td>'.$llr.'</td><td>'.$n['ir'].'</td>';
			$l .= '</tr>';			
		}
		$l .= '</tbody></table>';
	}
	else
	{
		$l = 'Aucune espèce pour ces critères';
	}
	
	$retour['tbl'] = $l;
	$retour['d'] = ($droit == 'oui') ? 'oui' : 'non';
	$retour['statut'] = 'Oui';
	echo json_encode($retour);
}	