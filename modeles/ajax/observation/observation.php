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
function nbobs($sel,$dater,$tri)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$ordre = ($tri == 'dateobs') ? 'date1' : 'datesaisie';
	if($sel != 'aucun')
	{
		$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM obs.obs INNER JOIN obs.fiche USING(idfiche) WHERE observa = :sel AND $ordre >= :date ");
		$req->bindValue(':sel', $sel);
	}
	else
	{
		$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM obs.obs INNER JOIN obs.fiche USING(idfiche) WHERE $ordre >= :date ");		
	}
	$req->bindValue(':date', $dater);
	$req->execute();
	$nbobs = $req->fetchColumn();
	$req->closeCursor();	
	return $nbobs;
}
function nbobsperso($sel,$idobser,$dater,$perso,$tri)
{
	$ordre = ($tri == 'dateobs') ? 'date1' : 'datesaisie';
	$strQuery = 'SELECT COUNT(*) AS nb FROM obs.obs INNER JOIN obs.fiche USING(idfiche) LEFT JOIN obs.plusobser ON plusobser.idfiche = fiche.idfiche';
	if($sel != 'aucun' && $perso == 'oui') { $strQuery .= " WHERE observa = :sel AND (fiche.idobser = :idobser OR plusobser.idobser = :idobser) AND $ordre >= :date"; }
	if($sel != 'aucun' && $perso == 'non') { $strQuery .= " WHERE observa = :sel AND (fiche.idobser != :idobser OR plusobser.idobser != :idobser) AND $ordre >= :date"; }
	if($sel == 'aucun' && $perso == 'oui') { $strQuery .= " WHERE (fiche.idobser = :idobser OR plusobser.idobser = :idobser) AND $ordre >= :date"; }
	if($sel == 'aucun' && $perso == 'non') { $strQuery .= " WHERE (fiche.idobser != :idobser OR plusobser.idobser != :idobser) AND $ordre >= :date"; }
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	$bdd = PDO2::getInstance();
	if($sel != 'aucun') { $req->bindValue(':sel', $sel); }
	$req->bindValue(':idobser', $idobser);
	$req->bindValue(':date', $dater);
	$req->execute();
	$nbobs = $req->fetchColumn();
	$req->closeCursor();	
	return $nbobs;	
}
function recupidobser($idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idobser FROM referentiel.observateur WHERE idm = :idm ");		
	$req->bindValue(':idm', $idm);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();	
	return $resultat;
}
function listeobs($sel,$tri,$debut,$dater)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$ordre = ($tri == 'dateobs') ? 'date1' : 'datesaisie';
	if($sel != 'aucun')
	{
		$req = $bdd->prepare("WITH sel AS (SELECT fiche.idfiche, obs.idobs, to_char(date1, 'DD/MM/YYYY') AS datefr, date1, datesaisie, to_char(datesaisie, 'DD/MM/YYYY') AS dates, codecom, fiche.iddep, idsite, idcoord, fiche.idobser, validation, observa, obs.cdref, nb, localisation, fiche.floutage, plusobser FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							WHERE observa = :sel AND $ordre >= :date
							ORDER BY $ordre DESC, idcoord
							LIMIT 100 OFFSET :deb								
							)
							SELECT sel.idfiche, sel.idobs, sel.dates, sel.datefr, sel.codecom, commune, site, sel.iddep, sel.idobser, liste.nom, nomvern, ir, rang, auteur, sel.validation, sel.observa, sel.cdref, nb, localisation, floutage, observateur.nom AS nomobser, prenom, idm, plusobser, nbcom FROM sel 
							LEFT JOIN referentiel.commune ON commune.codecom = sel.codecom
							INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref
							INNER JOIN referentiel.observateur ON observateur.idobser = sel.idobser
							LEFT JOIN site.liencom ON liencom.idobs = sel.idobs
							LEFT JOIN obs.site ON site.idsite = sel.idsite
							ORDER BY $ordre DESC, sel.idcoord ");
		$req->bindValue(':sel', $sel);
	}
	else
	{
		$req = $bdd->prepare("WITH sel AS (SELECT fiche.idfiche, obs.idobs, to_char(date1, 'DD/MM/YYYY') AS datefr, date1, datesaisie, to_char(datesaisie, 'DD/MM/YYYY') AS dates, codecom, fiche.iddep, idsite, idcoord, fiche.idobser, validation, observa, obs.cdref, nb, localisation, fiche.floutage, plusobser FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							WHERE observa != 'aucun' AND $ordre >= :date
							ORDER BY $ordre DESC, idcoord
							LIMIT 100 OFFSET :deb								
							)
							SELECT sel.idfiche, sel.idobs, sel.dates, sel.datefr, sel.codecom, commune, site, sel.iddep, sel.idobser, liste.nom, nomvern, ir, rang, auteur, sel.validation, sensible, sel.observa, sel.cdref, nb, localisation, floutage, observateur.nom AS nomobser, prenom, idm, plusobser, nbcom FROM sel 
							LEFT JOIN referentiel.commune ON commune.codecom = sel.codecom
							INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref
							INNER JOIN referentiel.observateur ON observateur.idobser = sel.idobser
							LEFT JOIN referentiel.sensible ON sensible.cdnom = sel.cdref
							LEFT JOIN site.liencom ON liencom.idobs = sel.idobs
							LEFT JOIN obs.site ON site.idsite = sel.idsite
							ORDER BY $ordre DESC, sel.idcoord ");		
	}
	$req->bindValue(':deb', $debut);
	$req->bindValue(':date', $dater);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function listeobsperso($sel,$tri,$debut,$idobser,$dater,$perso)
{
	$ordre = ($tri == 'dateobs') ? 'date1' : 'datesaisie';
	$strQuery = "WITH sel AS (SELECT DISTINCT fiche.idfiche, obs.idobs, to_char(date1, 'DD/MM/YYYY') AS datefr, to_char(datesaisie, 'DD/MM/YYYY') AS dates, date1, datesaisie, codecom, fiche.iddep, idsite, idcoord, fiche.idobser, validation, observa, obs.cdref, nb, localisation, fiche.floutage, plusobser FROM obs.fiche";
	$strQuery .= ' INNER JOIN obs.obs USING(idfiche)';
	$strQuery .= ' INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser';
	$strQuery .= ' LEFT JOIN obs.plusobser ON plusobser.idfiche = fiche.idfiche';
	if($sel != 'aucun' && $perso == 'oui') { $strQuery .= " WHERE observa = :sel AND (observateur.idobser = :idobser OR plusobser.idobser = :idobser) AND $ordre >= :date"; }
	if($sel != 'aucun' && $perso == 'non') { $strQuery .= " WHERE observa = :sel AND (observateur.idobser != :idobser OR plusobser.idobser != :idobser) AND $ordre >= :date"; }
	if($sel == 'aucun' && $perso == 'oui') { $strQuery .= " WHERE observa != 'aucun' AND (observateur.idobser = :idobser OR plusobser.idobser = :idobser) AND $ordre >= :date"; }
	if($sel == 'aucun' && $perso == 'non') { $strQuery .= " WHERE observa != 'aucun' AND (observateur.idobser != :idobser OR plusobser.idobser != :idobser) AND $ordre >= :date"; }
	$strQuery .= " ORDER BY $ordre DESC LIMIT 100 OFFSET :deb)";
	$strQuery .= " SELECT sel.idfiche, sel.idobs, sel.dates, sel.datefr, sel.codecom, commune, site, sel.iddep, sel.idobser, liste.nom, nomvern, ir, rang, auteur, sel.validation, sensible, sel.observa, sel.cdref, nb, localisation, floutage, observateur.nom AS nomobser, prenom, idm, plusobser, nbcom FROM sel ";
	$strQuery .= ' LEFT JOIN referentiel.commune ON commune.codecom = sel.codecom';
	$strQuery .= ' INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref';
	$strQuery .= ' INNER JOIN referentiel.observateur ON observateur.idobser = sel.idobser';
	$strQuery .= ' LEFT JOIN referentiel.sensible ON sensible.cdnom = sel.cdref';
	$strQuery .= ' LEFT JOIN site.liencom ON liencom.idobs = sel.idobs';
	$strQuery .= ' LEFT JOIN obs.site ON site.idsite = sel.idsite';
	$strQuery .= ' ORDER BY '.$ordre.' DESC, sel.idcoord';
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	$bdd = PDO2::getInstance();
	if($sel != 'aucun') { $req->bindValue(':sel', $sel); }
	$req->bindValue(':deb', $debut);
	$req->bindValue(':idobser', $idobser);
	$req->bindValue(':date', $dater);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function listeobsdep($sel,$tri,$debut,$dater)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$ordre = ($tri == 'dateobs') ? 'date1' : 'datesaisie';
	if($sel != 'aucun')
	{
		$req = $bdd->prepare("WITH sel AS (SELECT fiche.idfiche, obs.idobs, to_char(date1, 'DD/MM/YYYY') AS datefr, date1, datesaisie, to_char(datesaisie, 'DD/MM/YYYY') AS dates, codecom, fiche.iddep, idsite, idcoord, fiche.idobser, validation, observa, obs.cdref, nb, localisation, fiche.floutage, plusobser FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							WHERE observa = :sel AND $ordre >= :date
							ORDER BY $ordre DESC, idcoord
							LIMIT 100 OFFSET :deb								
							)
							SELECT sel.idfiche, sel.idobs, sel.dates, sel.datefr, sel.codecom, commune, departement, site, sel.iddep, sel.idobser, liste.nom, nomvern, ir, rang, auteur, sel.validation, sel.observa, sel.cdref, nb, localisation, floutage, observateur.nom AS nomobser, prenom, idm, plusobser, nbcom FROM sel 
							LEFT JOIN referentiel.commune ON commune.codecom = sel.codecom
							INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref
							INNER JOIN referentiel.observateur ON observateur.idobser = sel.idobser
							INNER JOIN referentiel.departement ON departement.iddep = sel.iddep
							LEFT JOIN site.liencom ON liencom.idobs = sel.idobs
							LEFT JOIN obs.site ON site.idsite = sel.idsite
							ORDER BY $ordre DESC, sel.idcoord ");
		$req->bindValue(':sel', $sel);
	}
	else
	{
		$req = $bdd->prepare("WITH sel AS (SELECT fiche.idfiche, obs.idobs, to_char(date1, 'DD/MM/YYYY') AS datefr, date1, datesaisie, to_char(datesaisie, 'DD/MM/YYYY') AS dates, codecom, fiche.iddep, idsite, idcoord, fiche.idobser, validation, observa, obs.cdref, nb, localisation, fiche.floutage, plusobser FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							WHERE observa != 'aucun' AND $ordre >= :date
							ORDER BY $ordre DESC, idcoord
							LIMIT 100 OFFSET :deb								
							)
							SELECT sel.idfiche, sel.idobs, sel.dates, sel.datefr, sel.codecom, commune, departement, site, sel.iddep, sel.idobser, liste.nom, nomvern, ir, rang, auteur, sel.validation, sensible, sel.observa, sel.cdref, nb, localisation, floutage, observateur.nom AS nomobser, prenom, idm, plusobser, nbcom FROM sel 
							LEFT JOIN referentiel.commune ON commune.codecom = sel.codecom
							INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref
							INNER JOIN referentiel.observateur ON observateur.idobser = sel.idobser
							INNER JOIN referentiel.departement ON departement.iddep = sel.iddep
							LEFT JOIN referentiel.sensible ON sensible.cdnom = sel.cdref
							LEFT JOIN site.liencom ON liencom.idobs = sel.idobs
							LEFT JOIN obs.site ON site.idsite = sel.idsite
							ORDER BY $ordre DESC, sel.idcoord ");		
	}
	$req->bindValue(':deb', $debut);
	$req->bindValue(':date', $dater);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function listeobsdepperso($sel,$tri,$debut,$idobser,$dater,$perso)
{
	$ordre = ($tri == 'dateobs') ? 'date1' : 'datesaisie';
	$strQuery = "WITH sel AS (SELECT DISTINCT fiche.idfiche, obs.idobs, to_char(date1, 'DD/MM/YYYY') AS datefr, to_char(datesaisie, 'DD/MM/YYYY') AS dates, date1, datesaisie, codecom, fiche.iddep, idsite, idcoord, fiche.idobser, validation, observa, obs.cdref, nb, localisation, fiche.floutage, plusobser FROM obs.fiche";
	$strQuery .= ' INNER JOIN obs.obs USING(idfiche)';
	$strQuery .= ' INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser';
	$strQuery .= ' LEFT JOIN obs.plusobser ON plusobser.idfiche = fiche.idfiche';
	if($sel != 'aucun' && $perso == 'oui') { $strQuery .= " WHERE observa = :sel AND (observateur.idobser = :idobser OR plusobser.idobser = :idobser) AND $ordre >= :date"; }
	if($sel != 'aucun' && $perso == 'non') { $strQuery .= " WHERE observa = :sel AND (observateur.idobser != :idobser OR plusobser.idobser != :idobser) AND $ordre >= :date"; }
	if($sel == 'aucun' && $perso == 'oui') { $strQuery .= " WHERE observa != 'aucun' AND (observateur.idobser = :idobser OR plusobser.idobser = :idobser) AND $ordre >= :date"; }
	if($sel == 'aucun' && $perso == 'non') { $strQuery .= " WHERE observa != 'aucun' AND (observateur.idobser != :idobser OR plusobser.idobser != :idobser) AND $ordre >= :date"; }
	$strQuery .= " ORDER BY $ordre DESC LIMIT 100 OFFSET :deb)";
	$strQuery .= " SELECT sel.idfiche, sel.idobs, sel.dates, sel.datefr, sel.codecom, commune, departement, site, sel.iddep, sel.idobser, liste.nom, nomvern, ir, rang, auteur, sel.validation, sensible, sel.observa, sel.cdref, nb, localisation, floutage, observateur.nom AS nomobser, prenom, idm, plusobser, nbcom FROM sel ";
	$strQuery .= ' LEFT JOIN referentiel.commune ON commune.codecom = sel.codecom';
	$strQuery .= ' INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref';
	$strQuery .= ' INNER JOIN referentiel.observateur ON observateur.idobser = sel.idobser';
	$strQuery .= ' INNER JOIN referentiel.departement ON departement.iddep = sel.iddep';
	$strQuery .= ' LEFT JOIN referentiel.sensible ON sensible.cdnom = sel.cdref';
	$strQuery .= ' LEFT JOIN site.liencom ON liencom.idobs = sel.idobs';
	$strQuery .= ' LEFT JOIN obs.site ON site.idsite = sel.idsite';
	$strQuery .= ' ORDER BY '.$ordre.' DESC, sel.idcoord';
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	$bdd = PDO2::getInstance();
	if($sel != 'aucun') { $req->bindValue(':sel', $sel); }
	$req->bindValue(':deb', $debut);
	$req->bindValue(':idobser', $idobser);
	$req->bindValue(':date', $dater);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function listephoto($listefiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT DISTINCT photo.idobs AS photo, son.idobs AS son FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						LEFT JOIN site.photo USING(idobs)
						LEFT JOIN site.son USING(idobs)
						WHERE idfiche IN($listefiche) AND (photo.idobs IS NOT NULL OR son.idobs IS NOT NULL) ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function listesensible($listefiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT idfiche, sensible FROM obs.obs
						INNER JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
						WHERE idfiche IN($listefiche) ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function cherche_observateur($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
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

if(isset($_POST['sel']) && isset($_POST['regroup']))
{
	//date - 30 jrs
	$datej = new DateTime();
	$datej->sub(new DateInterval('P30D'));
	$dater = $datej->format('Y-m-d');
	
	$perso = htmlspecialchars($_POST['perso']);
	$nperso = htmlspecialchars($_POST['nperso']);
	$regroup = htmlspecialchars($_POST['regroup']);
	$sel = htmlspecialchars($_POST['sel']);
	$tri = htmlspecialchars($_POST['tri']);
	$dep = htmlspecialchars($_POST['dep']);
	$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
	$indice = $_POST['indice'];
	$json_site = file_get_contents('../../../json/site.json');
	$rjson_site = json_decode($json_site, true);
	if(($perso == 'oui' || $nperso == 'oui') && isset($_SESSION['idmembre'])) { $idobser = recupidobser($_SESSION['idmembre']); }
	//pagination
	$nbobs = (($perso == 'oui' || $nperso == 'oui') && isset($_SESSION['idmembre'])) ? nbobsperso($sel,$idobser,$dater,$perso,$tri) : nbobs($sel,$dater,$tri);
	$nbpage = ceil($nbobs/100);	
	$page = intval($_POST['page']);
	$pageaffiche = ($page > $nbpage) ? $nbpage : $page;
	$debut = ($pageaffiche * 100 - 100);
	
	$retour['pagination'] = ($indice != '0') ? '' : pagination($nbpage,$pageaffiche);
	
	if(($perso == 'oui' || $nperso == 'oui') && isset($_SESSION['idmembre']))
	{
		$listeobstmp = ($dep == 'oui') ? listeobsdepperso($sel,$tri,$debut,$idobser,$dater,$perso) : listeobsperso($sel,$tri,$debut,$idobser,$dater,$perso);
	}
	else
	{
		$listeobstmp = ($dep == 'oui') ? listeobsdep($sel,$tri,$debut,$dater) : listeobs($sel,$tri,$debut,$dater);
		//$listeobstmp = listeobs($sel,$tri,$debut,$dater);
	}
	
	if(count($listeobstmp) > 0)
	{
		if(isset($rjson_site['indice'][$sel])) 
		{ 
			$e = 'non'; $tr = 'non'; $r = 'non'; $ar = 'non';
			foreach($listeobstmp as $n)
			{
				if($n['ir'] == 'E') { $e = 'oui'; }
				elseif($n['ir'] == 'TR') { $tr = 'oui'; }
				elseif($n['ir'] == 'R') { $r = 'oui'; }
				elseif($n['ir'] == 'AR') { $ar = 'oui'; }
				if($indice != '0')
				{
					if($n['ir'] == $indice)
					{
						if($sel == 'aucun')
						{
							$listeobs[] = ['idfiche'=>$n['idfiche'], 'idobs'=>$n['idobs'], 'dates'=>$n['dates'], 'datefr'=>$n['datefr'], 'site'=>$n['site'], 'codecom'=>$n['codecom'], 'commune'=>$n['commune'], 'iddep'=>$n['iddep'], 'idobser'=>$n['idobser'], 'ir'=>$n['ir'], 'sensible'=>$n['sensible'], 'validation'=>$n['validation'], 'nom'=>$n['nom'], 'nomvern'=>$n['nomvern'], 'rang'=>$n['rang'], 'nb'=>$n['nb'], 'observa'=>$n['observa'], 'auteur'=>$n['auteur'], 'cdref'=>$n['cdref'], 'localisation'=>$n['localisation'], 'floutage'=>$n['floutage'], 'nbcom'=>$n['nbcom'], 'nomobser'=>$n['nomobser'], 'prenom'=>$n['prenom'], 'idm'=>$n['idm'], 'plusobser'=>$n['plusobser']];
						}
						else
						{
							$listeobs[] = ['idfiche'=>$n['idfiche'], 'idobs'=>$n['idobs'], 'dates'=>$n['dates'], 'datefr'=>$n['datefr'], 'site'=>$n['site'], 'codecom'=>$n['codecom'], 'commune'=>$n['commune'], 'iddep'=>$n['iddep'], 'idobser'=>$n['idobser'], 'ir'=>$n['ir'], 'validation'=>$n['validation'], 'nom'=>$n['nom'], 'nomvern'=>$n['nomvern'], 'rang'=>$n['rang'], 'nb'=>$n['nb'], 'observa'=>$n['observa'], 'auteur'=>$n['auteur'], 'cdref'=>$n['cdref'], 'localisation'=>$n['localisation'], 'floutage'=>$n['floutage'], 'nbcom'=>$n['nbcom'], 'nomobser'=>$n['nomobser'], 'prenom'=>$n['prenom'], 'idm'=>$n['idm'], 'plusobser'=>$n['plusobser']];
						}
					}
					$retour['indicet'] = 'oui';
				}
				else
				{
					if($sel == 'aucun')
					{
						$listeobs[] = ['idfiche'=>$n['idfiche'], 'idobs'=>$n['idobs'], 'dates'=>$n['dates'], 'datefr'=>$n['datefr'], 'site'=>$n['site'], 'codecom'=>$n['codecom'], 'commune'=>$n['commune'], 'iddep'=>$n['iddep'], 'idobser'=>$n['idobser'], 'ir'=>$n['ir'], 'sensible'=>$n['sensible'], 'validation'=>$n['validation'], 'nom'=>$n['nom'], 'nomvern'=>$n['nomvern'], 'rang'=>$n['rang'], 'nb'=>$n['nb'], 'observa'=>$n['observa'], 'auteur'=>$n['auteur'], 'cdref'=>$n['cdref'], 'localisation'=>$n['localisation'], 'floutage'=>$n['floutage'], 'nbcom'=>$n['nbcom'], 'nomobser'=>$n['nomobser'], 'prenom'=>$n['prenom'], 'idm'=>$n['idm'], 'plusobser'=>$n['plusobser']];
					}
					else
					{
						$listeobs[] = ['idfiche'=>$n['idfiche'], 'idobs'=>$n['idobs'], 'dates'=>$n['dates'], 'datefr'=>$n['datefr'], 'site'=>$n['site'], 'codecom'=>$n['codecom'], 'commune'=>$n['commune'], 'iddep'=>$n['iddep'], 'idobser'=>$n['idobser'], 'ir'=>$n['ir'], 'validation'=>$n['validation'], 'nom'=>$n['nom'], 'nomvern'=>$n['nomvern'], 'rang'=>$n['rang'], 'nb'=>$n['nb'], 'observa'=>$n['observa'], 'auteur'=>$n['auteur'], 'cdref'=>$n['cdref'], 'localisation'=>$n['localisation'], 'floutage'=>$n['floutage'], 'nbcom'=>$n['nbcom'], 'nomobser'=>$n['nomobser'], 'prenom'=>$n['prenom'], 'idm'=>$n['idm'], 'plusobser'=>$n['plusobser']];
					}
				}
			}
			$retour['indice'] = array('E'=>$e, 'TR'=>$tr, 'R'=>$r, 'AR'=>$ar);
		}
		else
		{
			$listeobs = $listeobstmp;
		}
		
		foreach($listeobs as $n)
		{
			$tabfichetmp[] = $n['idfiche'];
			if(!isset($_SESSION['droits']) || $_SESSION['droits'] == 0)
			{
				if($sel == 'aucun' && $perso == 'non')
				{
					if($n['sensible'] >= 2) { $tabsensible[] = $n['idfiche']; }	
					if($n['sensible'] == 1) { $tabsensible1[] = $n['idfiche']; }
				}
			}
		}
		$listefiche = array_unique($tabfichetmp);
		$listefiche = implode(',', $listefiche);
		
		if(!isset($_SESSION['droits']) || $_SESSION['droits'] == 0)
		{	
			if($sel != 'aucun' && $perso == 'non')
			{
				$tmpsensible = listesensible($listefiche);
				if(count($tmpsensible) > 0)
				{
					foreach($tmpsensible as $n)
					{
						if($n['sensible'] >= 2) { $tabsensible[] = $n['idfiche']; }	
						if($n['sensible'] == 1) { $tabsensible1[] = $n['idfiche']; }
					}
				}
			}
		}
		
		$listephoto = listephoto($listefiche);
		
		if(count($listephoto) > 0)
		{
			foreach($listephoto as $n)
			{
				if($n['photo'] != '')
				{
					$photo[] = $n['photo']; $okphoto = 'oui';
				}
				if($n['son'] != '')
				{
					$son[] = $n['son']; $okson = 'oui';
				}
			}
			if(isset($okphoto)) { $photo = array_flip($photo); }
			if(isset($okson)) { $son = array_flip($son); }
		}
		$tabfiche = array_count_values($tabfichetmp);
		$tabsensible = (isset($tabsensible)) ? array_flip($tabsensible) : '';
		$tabsensible1 = (isset($tabsensible1)) ? array_flip($tabsensible1) : '';
		
		foreach($listeobs as $n)
		{
			if(isset($rjson_site['indice'][$sel])) 
			{
				if($n['ir'] == 'E') { $class = 'text-danger'; $ouiindice = 'oui'; }
				elseif($n['ir'] == 'TR') { $class = 'text-danger'; $ouiindice = 'oui'; }
				elseif($n['ir'] == 'R') { $class = 'text-danger'; $ouiindice = 'oui'; }
				elseif($n['ir'] == 'AR') { $class = 'text-danger'; $ouiindice = 'oui'; }
				else { $ouiindice = 'non'; }
			}
			else
			{
				$ouiindice = 'non';
			}
					
			//regroupement
			if($regroup == 'date')
			{
				$tabregroup[] = $n['datefr'];
			}
			elseif($regroup == 'dates')
			{
				$tabregroup[] = $n['dates'];
			}
			elseif($regroup == 'commune')
			{
				$tabregroup[] = ($n['floutage'] >= 2 || isset($tabsensible[$n['idfiche']])) ? $n['iddep'] : $n['commune'];				
			}
			elseif($regroup == 'departement')
			{
				$tabregroup[] = $n['departement'];				
			}
			$plusfiche = (isset($tabfiche[$n['idfiche']]) && ($tabfiche[$n['idfiche']] > 1)) ? $n['idfiche'] : 'non';			
			$ouiphoto = (isset($photo) && isset($photo[$n['idobs']])) ? 'oui' : 'non';
			$ouison = (isset($son) && isset($son[$n['idobs']])) ? 'oui' : 'non';
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
			if($regroup == 'observateur')
			{
				$tabregroup[] = $obs;
			}
			$comobs = ($n['nbcom'] >= 1) ? $n['nbcom'] : 0;
			if($dep == 'oui')
			{
				$localisation = ($n['floutage'] >= 2 || isset($tabsensible[$n['idfiche']])) ? $n['departement'] : $n['commune'];
				$locadep = $n['departement']; 
				if($n['floutage'] >= 2 || isset($tabsensible[$n['idfiche']]))
				{
					$affichagelocalisation = '('.$n['departement'].')';
				}
				elseif($n['floutage'] == 1 || isset($tabsensible1[$n['idfiche']]))
				{
					$affichagelocalisation = '<a href="index.php?module=commune&amp;action=commune&amp;codecom='.$n['codecom'].'">'.$n['commune'].' ('.$n['iddep'].')</a>';
				}
				else
				{
					$affichagelocalisation = (!empty($n['site'])) ? '<a href="index.php?module=commune&amp;action=commune&amp;codecom='.$n['codecom'].'">'.$n['commune'].' ('.$n['iddep'].')</a>, '.$n['site'] : '<a href="index.php?module=commune&amp;action=commune&amp;codecom='.$n['codecom'].'">'.$n['commune'].' ('.$n['iddep'].')</a>';
				}
			}
			else
			{
				$localisation = ($n['floutage'] >= 2 || isset($tabsensible[$n['idfiche']])) ? $n['iddep'] : $n['commune'];
				$locadep = null;
				if($n['floutage'] >= 2 || isset($tabsensible[$n['idfiche']]))
				{
					$affichagelocalisation = '('.$n['iddep'].')';
				}
				elseif($n['floutage'] == 1 || isset($tabsensible1[$n['idfiche']]))
				{
					$affichagelocalisation = '<a href="index.php?module=commune&amp;action=commune&amp;codecom='.$n['codecom'].'">'.$n['commune'].'</a>';
				}
				else
				{
					$affichagelocalisation = (!empty($n['site'])) ? '<a href="index.php?module=commune&amp;action=commune&amp;codecom='.$n['codecom'].'">'.$n['commune'].'</a>, '.$n['site'] : '<a href="index.php?module=commune&amp;action=commune&amp;codecom='.$n['codecom'].'">'.$n['commune'].'</a>';
				}
			}
			foreach($rjson_site['observatoire'] as $d)
			{
				if($d['nomvar'] == $n['observa'])
				{
					if($d['latin'] == 'oui' && $latin == 'oui') { $afflatin = 'oui'; }
					elseif($d['latin'] == 'oui' && ($latin == 'defaut' || $latin == '')) { $afflatin = 'oui'; }
					elseif($d['latin'] == 'non' && $latin == 'oui') { $afflatin = 'oui'; }
					elseif($d['latin'] == 'non' || $latin == 'non') { $afflatin = 'non'; }
					if($afflatin == 'oui')
					{	
						if($n['rang'] != 'GN' && $n['rang'] != 'COM')
						{
							$afflatintab = ($ouiindice == 'oui') ? '<a class="'.$class.' font-weight-bold" href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].' '.$n['auteur'].'</i></a>' : '<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].' '.$n['auteur'].'</i></a>';
						}
						else
						{
							$afflatintab = ($n['rang'] == 'GN') ? '<a href="observatoire/index.php?module=fiche&amp;action=ficheg&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].' sp. '.$n['auteur'].'</i></a>' : '<a href="observatoire/index.php?module=fiche&amp;action=fichec&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].'</i></a>';
						}
					}
					else
					{
						if($n['nomvern'] != '')
						{
							if($n['rang'] != 'GN' && $n['rang'] != 'COM')
							{
								$afflatintab = ($ouiindice == 'oui') ? '<a class="'.$class.' font-weight-bold" href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'" class="tbleu" data-toggle="tooltip" data-placement="top" title="'.$n['nom'].'">'.$n['nomvern'].'</a>' : '<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'" class="tbleu" data-toggle="tooltip" data-placement="top" title="'.$n['nom'].'">'.$n['nomvern'].'</a>';
							}
							else
							{
								$afflatintab = ($n['rang'] == 'GN') ? '<a href="observatoire/index.php?module=fiche&amp;action=ficheg&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].' sp. '.$n['auteur'].'</i></a>' : '<a href="observatoire/index.php?module=fiche&amp;action=fichec&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].'</i></a>';
							}
						}
						else
						{
							if($n['rang'] != 'GN' && $n['rang'] != 'COM')
							{
								$afflatintab = ($ouiindice == 'oui') ? '<a class="'.$class.' font-weight-bold" href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].' '.$n['auteur'].'</i></a>' : '<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].' '.$n['auteur'].'</i></a>';
							}
							else
							{
								$afflatintab = ($n['rang'] == 'GN') ? '<a href="observatoire/index.php?module=fiche&amp;action=ficheg&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].' sp. '.$n['auteur'].'</i></a>' : '<a href="observatoire/index.php?module=fiche&amp;action=fichec&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].'</i></a>';
							}
						}											
					}
					if($regroup == 'espece')
					{
						$tabregroup[] = array('nom'=>$n['nom'], 'taxon'=>$afflatintab);
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
					$tabobs[] = ['latin'=>$afflatin, 'taxon'=>$afflatintab, 'vali'=>$clvali, 'tvali'=>$tolvali, 'dates'=>$n['dates'], 'datefr'=>$n['datefr'], 'nomlat'=>$n['nom'], 'nomfr'=>$n['nomvern'], 'nb'=>$n['nb'], 'icon'=>$d['icon'], 'loca'=>$localisation, 'afloca'=>$affichagelocalisation, 'obs'=>$obs, 'idobs'=>$n['idobs'], 'idfiche'=>$n['idfiche'], 'flou'=>$n['floutage'], 'com'=>$comobs, 'photo'=>$ouiphoto, 'son'=>$ouison, 'idm'=>$n['idm'], 'plusfiche'=>$plusfiche, 'locadep'=>$locadep];
				}
			}			
		}
		if($regroup == 'espece')
		{
			$tabtmp = array_map( 'serialize' , $tabregroup );
			$tabtmp = array_unique($tabtmp);
			$tabregroup = array_map( 'unserialize' , $tabtmp );
		}		
		else
		{
			$tabregroup = array_unique($tabregroup);
		}
		
		$liste = null;
		$liste .= '<table class="table table-hover table-sm tblobs"><tbody>';
		foreach($tabregroup as $r)
		{
			$liste .= '<tr>';
			if($regroup == 'espece') 
			{
				$liste .= '<td colspan="5" class="font-weight-bold">'.$r['taxon'].'</td>';
				$r = $r['nom'];
			}
			elseif($regroup == 'dates')
			{
				$liste .= '<td colspan="6" class=""><b>'.$r.'</b> (Date de saisie)</td>';
			}
			else
			{
				$liste .= '<td colspan="5" class="font-weight-bold">'.$r.'</td>';
			}		
			foreach($tabobs as $n)
			{
				if($regroup == 'date') {$listegroup = $n['datefr'];}
				elseif($regroup == 'dates') { $listegroup = $n['dates']; }
				elseif($regroup == 'espece') {$listegroup = $n['nomlat'];}
				elseif($regroup == 'commune') {$listegroup = $n['loca'];}
				elseif($regroup == 'departement') {$listegroup = $n['locadep'];}
				elseif($regroup == 'observateur') {$listegroup = $n['obs'];}
				if($listegroup == $r)
				{
					$liste .= '<tr>';
					
					$liste .= ($sel == 'aucun') ? '<td><i class="'.$n['icon'].' fa-15x"></i>' : '<td>';
					$liste .= '&nbsp;<i class="fa fa-check-circle '.$n['vali'].'" data-toggle="tooltip" data-placement="top" title="'.$n['tvali'].'"></i>';
					$liste .= '&nbsp;'.$n['nb'];
					$liste .= '</td>';
					if($regroup == 'date')
					{
						$liste .= '<td>'.$n['taxon'].'</td>';						
						$liste .= '<td>'.$n['afloca'].'</td>';
						$liste .= '<td>'.$n['obs'].'</td>';						
					}
					elseif($regroup == 'dates')
					{
						$liste .= '<td>'.$n['datefr'].'</td>';
						$liste .= '<td>'.$n['taxon'].'</td>';						
						$liste .= '<td>'.$n['afloca'].'</td>';
						$liste .= '<td>'.$n['obs'].'</td>';	
					}
					elseif($regroup == 'commune')
					{
						$liste .= '<td>'.$n['datefr'].'</td>';
						$liste .= '<td>'.$n['taxon'].'</td>';
						$liste .= '<td>'.$n['obs'].'</td>';
					}
					elseif($regroup == 'departement')
					{
						$liste .= '<td>'.$n['datefr'].'</td>';
						$liste .= '<td>'.$n['taxon'].'</td>';
						$liste .= '<td>'.$n['afloca'].'</td>';
						$liste .= '<td>'.$n['obs'].'</td>';
					}
					elseif($regroup == 'espece')
					{
						$liste .= '<td>'.$n['datefr'].'</td>';
						$liste .= '<td>'.$n['afloca'].'</td>';
						$liste .= '<td>'.$n['obs'].'</td>';
					}
					elseif($regroup == 'observateur')
					{
						$liste .= '<td>'.$n['datefr'].'</td>';
						$liste .= '<td>'.$n['taxon'].'</td>';
						$liste .= '<td>'.$n['afloca'].'</td>';
					}
					$liste .= '<td>';
					$liste .= '<i class="fa fa-info-circle text-info curseurlien" data-toggle="modal" data-target="#obs" data-nomfr="'.$n['nomfr'].'" data-nomlat="'.$n['nomlat'].'" data-idobs="'.$n['idobs'].'" data-latin="'.$n['latin'].'" data-photo="'.$n['photo'].'" data-idmor="'.$n['idm'].'"></i>';
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
					if(isset($_SESSION['idmembre']) && $n['idm'] == $_SESSION['idmembre'])
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
			}							
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
	$retour['statut'] = 'Oui';
	echo json_encode($retour);
}	