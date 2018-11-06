<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function calc_obs($sel,$obs)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(nb) FROM (
							SELECT codel93, COUNT(idobs) AS nb FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
							WHERE observa = :sel 
							GROUP BY codel93 ) AS s
						WHERE nb <= :mini ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':mini', $obs);
	$req->bindValue(':sel', $sel);
	$req->execute();
	$m = $req->fetchColumn();
	$req->closeCursor();
	return $m;
}
function calc_es($sel,$es)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(nb) FROM (
							SELECT codel93, COUNT(distinct cdref) AS nb FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
							WHERE observa = :sel
							GROUP BY codel93 ) AS s
						WHERE nb <= :mini ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':mini', $es);
	$req->bindValue(':sel', $sel);
	$req->execute();
	$m = $req->fetchColumn();
	$req->closeCursor();
	return $m;
}
function listeindice($sel,$tri,$debut)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$ordre = ($tri == 'dateobs') ? 'date1' : 'datesaisie';
	$req = $bdd->prepare("WITH sel AS (SELECT obs.cdref FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
							WHERE observa = :sel
							ORDER BY $ordre DESC, liste.nom
							LIMIT 100 OFFSET :deb)
						SELECT sel.cdref, COUNT(DISTINCT codel93) AS nb FROM sel
						INNER JOIN obs.obs ON obs.cdref = sel.cdref
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						GROUP BY sel.cdref ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':sel', $sel);
	$req->bindValue(':deb', $debut);
	$req->execute();	
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
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
function nbobs($sel)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($sel != 'aucun')
	{
		$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM obs.obs WHERE observa = :sel ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':sel', $sel);
		$req->execute();
	}
	else
	{
		$req = $bdd->query("SELECT COUNT(*) AS nb FROM obs.obs ") or die(print_r($bdd->errorInfo()));		
	}
	$nbobs = $req->fetchColumn();
	$req->closeCursor();	
	return $nbobs;
}
function nbobsperso($sel,$idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($sel != 'aucun')
	{
		$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM obs.obs 
							INNER JOIN obs.fiche USING(idfiche)
							LEFT JOIN obs.plusobser ON plusobser.idfiche = fiche.idfiche
							WHERE observa = :sel AND (fiche.idobser = :idobser OR plusobser.idobser = :idobser) ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':sel', $sel);
	}
	else
	{
		$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							LEFT JOIN obs.plusobser ON plusobser.idfiche = fiche.idfiche
							WHERE fiche.idobser = :idobser OR plusobser.idobser = :idobser ") or die(print_r($bdd->errorInfo()));		
	}
	$req->bindValue(':idobser', $idobser);
	$req->execute();
	$nbobs = $req->fetchColumn();
	$req->closeCursor();	
	return $nbobs;
}
function recupidobser($idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idobser FROM referentiel.observateur WHERE idm = :idm ") or die(print_r($bdd->errorInfo()));		
	$req->bindValue(':idm', $idm);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();	
	return $resultat;
}
function listeobs($sel,$tri,$debut)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$ordre = ($tri == 'dateobs') ? 'date1' : 'datesaisie';
	if($sel != 'aucun')
	{
		$req = $bdd->prepare("SELECT fiche.idfiche, obs.idobs, to_char(date1, 'DD/MM/YYYY') AS datefr, commune, fiche.iddep, fiche.idobser, sensible, validation, liste.nom, nomvern, rang, nb, observa, auteur, obs.cdref, localisation, fiche.floutage, nbcom, observateur.nom AS nomobser, prenom, idm, plusobser FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							LEFT JOIN referentiel.commune ON commune.codecom = fiche.codecom
							INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
							LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
							LEFT JOIN site.liencom ON liencom.idobs = obs.idobs
							WHERE observatoire = :sel
							ORDER BY $ordre DESC, liste.nom 
							LIMIT 100 OFFSET :deb ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':sel', $sel);
	}
	else
	{
		$req = $bdd->prepare("SELECT fiche.idfiche, obs.idobs, to_char(date1, 'DD/MM/YYYY') AS datefr, commune, fiche.iddep, fiche.idobser, sensible, validation, liste.nom, nomvern, rang, nb, observa, auteur, obs.cdref, localisation, fiche.floutage, nbcom, observateur.nom AS nomobser, prenom, idm, plusobser FROM obs.fiche
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							LEFT JOIN referentiel.commune ON commune.codecom = fiche.codecom
							INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
							LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
							LEFT JOIN site.liencom ON liencom.idobs = obs.idobs
							WHERE observatoire != 'aucun'
							ORDER BY $ordre DESC, liste.nom 
							LIMIT 100 OFFSET :deb ") or die(print_r($bdd->errorInfo()));		
	}
	$req->bindValue(':deb', $debut);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function listeobsperso($sel,$tri,$debut,$idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$ordre = ($tri == 'dateobs') ? 'date1' : 'datesaisie';
	if($sel != 'aucun')
	{
		$req = $bdd->prepare("SELECT fiche.idfiche, obs.idobs, to_char(date1, 'DD/MM/YYYY') AS datefr, commune, fiche.iddep, fiche.idobser, sensible, validation, liste.nom, nomvern, rang, nb, observa, auteur, obs.cdref, localisation, fiche.floutage, nbcom, observateur.nom AS nomobser, prenom, idm, plusobser FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							LEFT JOIN referentiel.commune ON commune.codecom = fiche.codecom
							INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
							LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
							LEFT JOIN site.liencom ON liencom.idobs = obs.idobs
							LEFT JOIN obs.plusobser ON plusobser.idfiche = fiche.idfiche
							WHERE observatoire = :sel AND (observateur.idobser = :idobser OR plusobser.idobser = :idobser)
							ORDER BY $ordre DESC, liste.nom 
							LIMIT 100 OFFSET :deb ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':sel', $sel);
	}
	else
	{
		$req = $bdd->prepare("SELECT fiche.idfiche, obs.idobs, to_char(date1, 'DD/MM/YYYY') AS datefr, commune, fiche.iddep, fiche.idobser, sensible, validation, liste.nom, nomvern, rang, nb, observa, auteur, obs.cdref, localisation, fiche.floutage, nbcom, observateur.nom AS nomobser, prenom, idm, plusobser FROM obs.fiche
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							LEFT JOIN referentiel.commune ON commune.codecom = fiche.codecom
							INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
							LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
							LEFT JOIN site.liencom ON liencom.idobs = obs.idobs
							LEFT JOIN obs.plusobser ON plusobser.idfiche = fiche.idfiche
							WHERE observatoire != 'aucun' AND (observateur.idobser = :idobser OR plusobser.idobser = :idobser)
							ORDER BY $ordre DESC, liste.nom 
							LIMIT 100 OFFSET :deb ") or die(print_r($bdd->errorInfo()));
	}
	$req->bindValue(':deb', $debut);
	$req->bindValue(':idobser', $idobser);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function listeobsdep($sel,$tri)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$ordre = ($tri == 'dateobs') ? 'date1' : 'datesaisie';
	if($sel != 'aucun')
	{
		$req = $bdd->prepare("SELECT fiche.idfiche, obs.idobs, to_char(date1, 'DD/MM/YYYY') AS datefr, commune, departement, fiche.iddep, fiche.idobser, validation, liste.nom, nomvern, rang, nb, observa, auteur, obs.cdref, localisation, fiche.floutage, nbcom, observateur.nom AS nomobser, prenom, idm, plusobser FROM obs.fiche
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							INNER JOIN referentiel.departement ON departement.iddep = fiche.iddep
							LEFT JOIN referentiel.commune ON commune.codecom = fiche.codecom
							INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
							LEFT JOIN site.liencom ON liencom.idobs = obs.idobs
							WHERE observatoire = :sel
							ORDER BY $ordre DESC, liste.nom 
							LIMIT 100 OFFSET :deb ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':sel', $sel);
		$req->bindValue(':deb', $debut);
		$req->execute();
	}
	else
	{
		$req = $bdd->prepare("SELECT fiche.idfiche, obs.idobs, to_char(date1, 'DD/MM/YYYY') AS datefr, commune, departement, fiche.iddep, fiche.idobser, validation, liste.nom, nomvern, rang, nb, observa, auteur, obs.cdref, localisation, fiche.floutage, nbcom, observateur.nom AS nomobser, prenom, idm, plusobser FROM obs.fiche
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							INNER JOIN referentiel.departement ON departement.iddep = fiche.iddep
							LEFT JOIN referentiel.commune ON commune.codecom = fiche.codecom
							INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
							LEFT JOIN site.liencom ON liencom.idobs = obs.idobs
							WHERE observatoire != 'aucun'
							ORDER BY $ordre DESC, liste.nom 
							LIMIT 100 OFFSET :deb ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':deb', $debut);
		$req->execute();
	}
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function listephoto($listefiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT DISTINCT idobs FROM site.photo
						INNER JOIN obs.obs USING(idobs)
						INNER JOIN obs.fiche USING(idfiche)
						WHERE idfiche IN($listefiche) ") or die(print_r($bdd->errorInfo()));
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function cherche_observateur($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT nom, prenom, idm, observateur.idobser FROM obs.plusobser
						INNER JOIN referentiel.observateur ON observateur.idobser = plusobser.idobser
						WHERE idfiche = :idfiche
						ORDER BY idplus ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
	$req->execute();
	$obsplus = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $obsplus;
}

if (isset($_POST['sel']) && isset($_POST['regroup']))
{
	$perso = htmlspecialchars($_POST['perso']);
	$regroup = htmlspecialchars($_POST['regroup']);
	$sel = htmlspecialchars($_POST['sel']);
	$tri = htmlspecialchars($_POST['tri']);
	$dep = htmlspecialchars($_POST['dep']);
	$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
	$indice = $_POST['indice'];
	$json_site = file_get_contents('../../../json/site.json');
	$rjson_site = json_decode($json_site, true);
	if($perso == 'oui' && isset($_SESSION['idmembre'])) { $idobser = recupidobser($_SESSION['idmembre']); }
	//pagination
	$nbobs = ($perso == 'oui' && isset($_SESSION['idmembre'])) ? nbobsperso($sel,$idobser) : nbobs($sel);
	$nbpage = ceil($nbobs/100);	
	$page = intval($_POST['page']);
	$pageaffiche = ($page > $nbpage) ? $nbpage : $page;
	$debut = ($pageaffiche * 100 - 100);
	
	if(isset($rjson_site['indice'][$sel])) 
	{ 
		if(!isset($_SESSION[$sel.'ic']))
		{
			$m = ($rjson_site['indice'][$sel]['choix'] == 'obs') ? calc_obs($sel,$rjson_site['indice'][$sel]['valchoix']) : calc_es($sel,$rjson_site['indice'][$sel]['valchoix']);
			$_SESSION[$sel.'ic'] = $m;
		}
		else
		{
			$m = $_SESSION[$sel.'ic'];
		}
		$mt = ($rjson_site['indice'][$sel]['maillage'] == 'l935') ? $_POST['mt5'] : $_POST['mt'];
		$M = round($m/$mt * 100,1);
		$cr1a = round(100 - ($rjson_site['indice'][$sel]['ms']/$mt) * 100,1);
		$cr2a = $cr1a - 1; $cr3a = $cr2a - 2; $cr4a = $cr3a - 4;
		$cr1 = round($cr1a + ($M - ($cr1a * $M/100)),1);
		$cr2 = round($cr2a + ($M - ($cr2a * $M/100)),1);
		$cr3 = round($cr3a + ($M - ($cr3a * $M/100)),1);
		$cr4 = round($cr4a + ($M - ($cr4a * $M/100)),1);
		$retour['indice'] = 'oui';
		
		$listeindice = listeindice($sel,$tri,$debut);
		$e = 'non'; $tr = 'non'; $r = 'non'; $ar = 'non';
		foreach($listeindice as $n)
		{
			$ir = round(100 - ($n['nb']/$mt) * 100,1);
			if($ir >= $cr1) { $e = 'oui'; }
			elseif($ir >= $cr2 && $ir < $cr1) { $tr = 'oui'; }
			elseif($ir >= $cr3 && $ir < $cr2) { $r = 'oui'; }
			elseif($ir >= $cr4 && $ir < $cr3) { $ar = 'oui'; }
		}	
		$retour['indice'] = array('E'=>$e, 'TR'=>$tr, 'R'=>$r, 'AR'=>$ar);
		
		if($indice != '0')
		{
			foreach($listeindice as $n)
			{
				$ir = round(100 - ($n['nb']/$mt) * 100,1);
				if($indice == 'E')
				{
					if($ir >= $cr1) { $tabindice[] = $n['cdref']; }
				}
				elseif($indice == 'TR')
				{
					if($ir >= $cr2 && $ir < $cr1) { $tabindice[] = $n['cdref']; }
				}	
				elseif($indice == 'R')
				{
					if($ir >= $cr3 && $ir < $cr2) { $tabindice[] = $n['cdref']; }
				}
				elseif($indice == 'AR')
				{
					if($ir >= $cr4 && $ir < $cr3) { $tabindice[] = $n['cdref']; }
				}				
			}
			if(isset($tabindice))
			{
				$tabindice = array_flip($tabindice);
				$retour['indicet'] = 'oui';
			}
		}
		else
		{
			foreach($listeindice as $n)
			{
				$ir = round(100 - ($n['nb']/$mt) * 100,1);
				if($ir >= $cr1) { $tabindice[] = $n['cdref']; }
				if($ir >= $cr2 && $ir < $cr1) { $tabindice[] = $n['cdref']; }
				if($ir >= $cr3 && $ir < $cr2) { $tabindice[] = $n['cdref']; }
			}
			if(isset($tabindice))
			{
				$tabindice = array_flip($tabindice);
				$retour['indicet'] = 'oui';
			}
		}
	}		
	if($indice != '0' && !isset($tabindice))
	{
		$retour['listeobs'] = 'Aucune observation pour ces critères';
		$retour['pagination'] = '';
		$retour['statut'] = 'Oui';
		echo json_encode($retour);	
		exit;		
	}
	
	//$retour['pagination'] = (isset($tabindice)) ? '' : pagination($nbpage,$pageaffiche);
	$retour['pagination'] = ($indice != '0') ? '' : pagination($nbpage,$pageaffiche);
	
	if($perso == 'oui' && isset($_SESSION['idmembre']))
	{
		$listeobstmp = ($dep == 'oui') ? listeobsdepperso($sel,$tri) : listeobsperso($sel,$tri,$debut,$idobser);
	}
	else
	{
		$listeobstmp = ($dep == 'oui') ? listeobsdep($sel,$tri) : listeobs($sel,$tri,$debut);
	}
	
	if(count($listeobstmp) > 0)
	{
		if($indice != '0' && isset($tabindice))
		{
			foreach($listeobstmp as $n)
			{
				if(isset($tabindice[$n['cdref']]))
				{
					$listeobs[] = array('idfiche'=>$n['idfiche'], 'idobs'=>$n['idobs'], 'datefr'=>$n['datefr'], 'commune'=>$n['commune'], 'iddep'=>$n['iddep'], 'idobser'=>$n['idobser'], 'sensible'=>$n['sensible'], 'validation'=>$n['validation'], 'nom'=>$n['nom'], 'nomvern'=>$n['nomvern'], 'rang'=>$n['rang'], 'nb'=>$n['nb'], 'observa'=>$n['observa'], 'auteur'=>$n['auteur'], 'cdref'=>$n['cdref'], 'localisation'=>$n['localisation'], 'floutage'=>$n['floutage'], 'nbcom'=>$n['nbcom'], 'nomobser'=>$n['nomobser'], 'prenom'=>$n['prenom'], 'idm'=>$n['idm'], 'plusobser'=>$n['plusobser']);
				}
			}
		}
		else
		{
			$listeobs = $listeobstmp;
		}
		
		foreach($listeobs as $n)
		{
			$tabfichetmp[] = $n['idfiche'];
			if($n['sensible'] >= 2) { $tabsensible[] = $n['idfiche']; }			
		}
		$listefiche = array_unique($tabfichetmp);
		$listefiche = implode(',', $listefiche);
		$listephoto = listephoto($listefiche);
		if(count($listephoto) > 0)
		{
			foreach($listephoto as $n)
			{
				$photo[] = $n['idobs'];
			}
			$photo = array_flip($photo);
		}
		$tabfiche = array_count_values($tabfichetmp);
		$tabsensible = (isset($tabsensible)) ? array_flip($tabsensible) : '';
		
		foreach($listeobs as $n)
		{
			if(isset($tabindice))
			{
				if(isset($tabindice[$n['cdref']]))
				{
					$ouiindice = 'oui';
				}
				else
				{
					$ouiindice = 'non';
				}
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
			//$localisation = ($n['floutage'] >= 2) ? $n['iddep'] : $n['commune'];
			if($dep == 'oui')
			{
				$localisation = ($n['floutage'] >= 2 || isset($tabsensible[$n['idfiche']])) ? $n['departement'] : $n['commune'];
				$locadep = $n['departement']; 
			}
			else
			{
				$localisation = ($n['floutage'] >= 2 || isset($tabsensible[$n['idfiche']])) ? $n['iddep'] : $n['commune'];
				$locadep = null;
			}
			if($dep == 'oui')
			{
				$affichagelocalisation = ($n['floutage'] >= 2 || isset($tabsensible[$n['idfiche']])) ? $n['departement'] : $n['commune'].' ('.$n['departement'].')';
			}
			else
			{
				$affichagelocalisation = ($n['floutage'] >= 2 || isset($tabsensible[$n['idfiche']])) ? '('.$n['iddep'].')' : $n['commune'];
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
							$afflatintab = ($ouiindice == 'oui') ? '<a class="text-danger font-weight-bold" href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].' '.$n['auteur'].'</i></a>' : '<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].' '.$n['auteur'].'</i></a>';
						}
						else
						{
							$afflatintab = '<a href="observatoire/index.php?module=fiche&amp;action=ficheg&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].' sp. '.$n['auteur'].'</i></a>';
						}
					}
					else
					{
						if($n['nomvern'] != '')
						{
							$afflatintab = ($ouiindice == 'oui') ? '<a class="text-danger font-weight-bold" href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'" class="tbleu" data-toggle="tooltip" data-placement="top" title="'.$n['nom'].'">'.$n['nomvern'].'</a>' : '<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'" class="tbleu" data-toggle="tooltip" data-placement="top" title="'.$n['nom'].'">'.$n['nomvern'].'</a>';
						}
						else
						{
							if($n['rang'] != 'GN')
							{
								$afflatintab = ($ouiindice == 'oui') ? '<a class="text-danger font-weight-bold" href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].' '.$n['auteur'].'</i></a>' : '<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].' '.$n['auteur'].'</i></a>';
							}
							else
							{
								$afflatintab = '<a href="observatoire/index.php?module=fiche&amp;action=ficheg&amp;d='.$d['nomvar'].'&amp;id='.$n['cdref'].'"><i>'.$n['nom'].' sp. '.$n['auteur'].'</i></a>';
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
						case 4:$clvali = 'val5'; $tolvali = 'Donnée invalide'; break;
						case 5:$clvali = 'val5'; $tolvali = 'Validation non réalisable'; break;
						case 6:$clvali = ''; $tolvali = 'En attente de validation'; break;
					}
					$tabobs[] = array('latin'=>$afflatin, 'taxon'=>$afflatintab, 'vali'=>$clvali, 'tvali'=>$tolvali, 'datefr'=>$n['datefr'], 'nomlat'=>$n['nom'], 'nomfr'=>$n['nomvern'], 'nb'=>$n['nb'], 'icon'=>$d['icon'], 'loca'=>$localisation, 'afloca'=>$affichagelocalisation, 'obs'=>$obs, 'idobs'=>$n['idobs'], 'flou'=>$n['floutage'], 'com'=>$comobs, 'photo'=>$ouiphoto, 'idm'=>$n['idm'], 'plusfiche'=>$plusfiche, 'locadep'=>$locadep);
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
		foreach ($tabregroup as $r)
		{
			if($regroup == 'espece') 
			{
				$liste .= '<b>'.$r['taxon'].'</b>';
				$r = $r['nom'];
			}
			else
			{
				$liste .= '<b>'.$r.'</b>';
			}		
			$liste .= '<div class="row mb-2">';
			foreach($tabobs as $n)
			{
				if($regroup == 'date') {$listegroup = $n['datefr'];}
				elseif($regroup == 'espece') {$listegroup = $n['nomlat'];}
				elseif($regroup == 'commune') {$listegroup = $n['loca'];}
				elseif($regroup == 'departement') {$listegroup = $n['locadep'];}
				elseif($regroup == 'observateur') {$listegroup = $n['obs'];}
				if($listegroup == $r)
				{
					$liste .= ($sel == 'aucun') ? '<div class="col-sm-1"><i class="'.$n['icon'].' fa-15x"></i>' : '<div class="col-sm-1">';
					$liste .= '&nbsp;<i class="fa fa-check-circle '.$n['vali'].'" data-toggle="tooltip" data-placement="top" title="'.$n['tvali'].'"></i>';
					$liste .= '&nbsp;'.$n['nb'];
					$liste .= '</div>';
					if($regroup == 'date')
					{
						$liste .= '<div class="col-sm-4">'.$n['taxon'].'</div>';						
						$liste .= '<div class="col-sm-3">'.$n['afloca'].'</div>';
						$liste .= '<div class="col-sm-3">'.$n['obs'].'</div>';
						
					}
					elseif($regroup == 'commune')
					{
						$liste .= '<div class="col-sm-2">'.$n['datefr'].'</div>';
						$liste .= '<div class="col-sm-5">'.$n['taxon'].'</div>';
						$liste .= '<div class="col-sm-3">'.$n['obs'].'</div>';
					}
					elseif($regroup == 'departement')
					{
						$liste .= '<div class="col-sm-1">'.$n['datefr'].'</div>';
						$liste .= '<div class="col-sm-4">'.$n['taxon'].'</div>';
						$liste .= '<div class="col-sm-2">'.$n['afloca'].'</div>';
						$liste .= '<div class="col-sm-3">'.$n['obs'].'</div>';
					}
					elseif($regroup == 'espece')
					{
						$liste .= '<div class="col-sm-2">'.$n['datefr'].'</div>';
						$liste .= '<div class="col-sm-4">'.$n['afloca'].'</div>';
						$liste .= '<div class="col-sm-4">'.$n['obs'].'</div>';
					}
					elseif($regroup == 'observateur')
					{
						$liste .= '<div class="col-sm-1">'.$n['datefr'].'</div>';
						$liste .= '<div class="col-sm-5">'.$n['taxon'].'</div>';
						$liste .= '<div class="col-sm-4">'.$n['afloca'].'</div>';
					}
					$liste .= '<div class="col-sm-1">';
					$liste .= '<i class="fa fa-info-circle text-info curseurlien" data-toggle="modal" data-target="#obs" data-nomfr="'.$n['nomfr'].'" data-nomlat="'.$n['nomlat'].'" data-idobs="'.$n['idobs'].'" data-latin="'.$n['latin'].'" data-photo="'.$n['photo'].'" data-idmor="'.$n['idm'].'"></i>';
					if($n['plusfiche'] != 'non')
					{
						$liste .= '&nbsp;<i class="fa fa-list-ol color1 curseurlien" data-toggle="modal" data-target="#fiche" data-idfiche="'.$n['plusfiche'].'"></i>';
					}
					if($n['photo'] == 'oui')
					{
						$liste .= '&nbsp;<i class="fa fa-camera"></i>';
					}
					if($n['com'] == 1)
					{
						$liste .= '&nbsp;<i class="fa fa-comment-o" data-toggle="tooltip" data-placement="top" title="1 commentaire"></i>';
					}
					elseif($n['com'] > 1)
					{
						$liste .= '&nbsp;<i class="fa fa-comments-o" data-toggle="tooltip" data-placement="top" title="Plusieurs commentaires"></i>';
					}
					$liste .= '</div>';
				}			
			}							
			$liste .= '</div>';
		}
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