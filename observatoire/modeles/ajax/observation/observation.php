<?php
include '../../../../global/configbase.php';
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
function nbobs($cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM obs.obs WHERE cdref = :cdnom ");		
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$nbobs = $req->fetchColumn();
	$req->closeCursor();	
	return $nbobs;
}
function nbobsperso($cdnom,$idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM obs.obs 
						INNER JOIN obs.fiche USING(idfiche)
						LEFT JOIN obs.plusobser ON plusobser.idfiche = fiche.idfiche
						WHERE cdref = :cdnom and (fiche.idobser = :idobser OR plusobser.idobser = :idobser) ");
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':idobser', $idobser);
	$req->execute();
	$nbobs = $req->fetchColumn();
	$req->closeCursor();	
	return $nbobs;
}
function listeobs($cdnom,$tri,$debut)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$ordre = ($tri == 'dateobs') ? 'date1' : 'datesaisie';
	$req = $bdd->prepare("SELECT fiche.idfiche, obs.idobs, to_char(date1, 'DD/MM/YYYY') AS datefr, site, commune, fiche.iddep, fiche.idobser, validation, nb, localisation, fiche.floutage, nbcom, observateur.nom AS nomobser, prenom, idm, plusobser FROM obs.fiche
						INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
						LEFT JOIN referentiel.commune ON commune.codecom = fiche.codecom
						LEFT JOIN obs.site ON site.idsite = fiche.idsite
						INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
						LEFT JOIN site.liencom ON liencom.idobs = obs.idobs
						WHERE cdref = :cdnom
						ORDER BY $ordre DESC
						LIMIT 100 OFFSET :deb ");
	$req->bindValue(':deb', $debut);
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function listeobsperso($cdnom,$tri,$debut,$idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$ordre = ($tri == 'dateobs') ? 'date1' : 'datesaisie';
	$req = $bdd->prepare("SELECT DISTINCT fiche.idfiche, obs.idobs, date1, to_char(date1, 'DD/MM/YYYY') AS datefr, site, commune, fiche.iddep, fiche.idobser, validation, nb, localisation, fiche.floutage, nbcom, observateur.nom AS nomobser, prenom, idm, plusobser FROM obs.fiche
						INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
						LEFT JOIN referentiel.commune ON commune.codecom = fiche.codecom
						LEFT JOIN obs.site ON site.idsite = fiche.idsite
						INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
						LEFT JOIN site.liencom ON liencom.idobs = obs.idobs
						LEFT JOIN obs.plusobser ON plusobser.idfiche = fiche.idfiche
						WHERE cdref = :cdnom AND (observateur.idobser = :idobser OR plusobser.idobser = :idobser)
						ORDER BY $ordre DESC
						LIMIT 100 OFFSET :deb ");
	$req->bindValue(':deb', $debut);
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':idobser', $idobser);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function listephoto($listefiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT DISTINCT idobs FROM site.photo
						INNER JOIN obs.obs USING(idobs)
						INNER JOIN obs.fiche USING(idfiche)
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

if (isset($_POST['id']) && isset($_POST['regroup']))
{
	$regroup = htmlspecialchars($_POST['regroup']);
	$tri = htmlspecialchars($_POST['tri']);
	$dep = htmlspecialchars($_POST['dep']);
	$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
	$cdnom = htmlspecialchars($_POST['id']);
	$sensible = htmlspecialchars($_POST['sen']);
	$perso = htmlspecialchars($_POST['perso']);
	
	if($perso == 'oui' && isset($_SESSION['idmembre'])) { $idobser = recupidobser($_SESSION['idmembre']); }
	
	//pagination
	$nbobs = ($perso == 'oui' && isset($_SESSION['idmembre'])) ? nbobsperso($cdnom,$idobser) : nbobs($cdnom);
	//$nbobs = nbobs($cdnom);
	$nbpage = ceil($nbobs/100);	
	$page = intval($_POST['page']);
	$pageaffiche = ($page > $nbpage) ? $nbpage : $page;
	$debut = ($pageaffiche * 100 - 100);
	$retour['pagination'] = pagination($nbpage,$pageaffiche);
	
	/*if($perso == 'oui' && isset($_SESSION['idmembre']))
	{
		if(!isset($_SESSION['virtobs'])) { $idobser = recupidobser($_SESSION['idmembre']); }
		else { $idobser = $_SESSION['idmembre']; }		
	}*/
	
	$listeobs = ($perso == 'oui' && isset($_SESSION['idmembre'])) ? listeobsperso($cdnom,$tri,$debut,$idobser) : listeobs($cdnom,$tri,$debut);
	
	if(count($listeobs) > 0)
	{
		foreach($listeobs as $n)
		{
			$tabfichetmp[] = $n['idfiche'];
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
				
		foreach($listeobs as $n)
		{
			//regroupement
			if($regroup == 'date')
			{
				$tabregroup[] = $n['datefr'];
			}
			elseif($regroup == 'commune')
			{
				$tabregroup[] = ($n['floutage'] >= 2 || $sensible == 2) ? $n['iddep'] : $n['commune'];				
			}
			elseif($regroup == 'departement')
			{
				$tabregroup[] = $n['iddep'];				
			}
			$plusfiche = (isset($tabfiche[$n['idfiche']]) && ($tabfiche[$n['idfiche']] > 1)) ? $n['idfiche'] : 'non';			
			$ouiphoto = (isset($photo) && isset($photo[$n['idobs']])) ? 'oui' : 'non';
			if($n['plusobser'] == 'oui')
			{
				$obs2[] = '<a href="../index.php?module=infoobser&amp;action=info&amp;idobser='.$n['idobser'].'">'.$n['prenom'].' '.$n['nomobser'].'</a>';
				$obsplus = cherche_observateur($n['idfiche']);
				foreach($obsplus as $o)
				{
					$obs2[] = '<a href="../index.php?module=infoobser&amp;action=info&amp;idobser='.$o['idobser'].'">'.$o['prenom'].' '.$o['nom'].'</a>'; 
				}
				$obs = implode(', ', $obs2);
				$obs2 = null;
			}
			else
			{
				$obs = '<a href="../index.php?module=infoobser&amp;action=info&amp;idobser='.$n['idobser'].'">'.$n['prenom'].' '.$n['nomobser'].'</a>';
			}
			if($regroup == 'observateur')
			{
				$tabregroup[] = $obs;
			}
			$comobs = ($n['nbcom'] >= 1) ? $n['nbcom'] : 0;
			if($dep == 'oui')
			{
				$localisation = ($n['floutage'] >= 2 || $sensible == 2) ? $n['iddep'] : $n['commune'];
				$locadep = $n['iddep']; 
			}
			else
			{
				$localisation = ($n['floutage'] >= 2 || $sensible == 2) ? $n['iddep'] : $n['commune'];
				$locadep = null;
			}
			if($dep == 'oui')
			{
				$affichagelocalisation = ($n['floutage'] >= 2 || $sensible == 2) ? $n['iddep'] : $n['commune'].' ('.$n['iddep'].')';
			}
			else
			{
				if(($n['floutage'] >= 2 || $sensible == 2))
				{
					$affichagelocalisation = '('.$n['iddep'].')';
				}
				else
				{
					$affichagelocalisation = ($n['floutage'] >= 1 || $sensible == 1) ? $n['commune'] : $n['site'].', '.$n['commune'];
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
			$tabobs[] = ['vali'=>$clvali, 'tvali'=>$tolvali, 'datefr'=>$n['datefr'], 'nb'=>$n['nb'], 'loca'=>$localisation, 'afloca'=>$affichagelocalisation, 'obs'=>$obs, 'idobs'=>$n['idobs'], 'idfiche'=>$n['idfiche'], 'flou'=>$n['floutage'], 'com'=>$comobs, 'photo'=>$ouiphoto, 'idm'=>$n['idm'], 'plusfiche'=>$plusfiche, 'locadep'=>$locadep];
		}
		
		$tabregroup = array_unique($tabregroup);
				
		$liste = null;
		$liste .= '<table class="table table-hover table-sm tblobs"><tbody>';
		foreach($tabregroup as $r)
		{
			$liste .= '<tr>';
			$liste .= '<td colspan="6" class=""><b>'.$r.'</b></td>';
			//$liste .= '<b>'.$r.'</b>';
			//$liste .= '<div class="row mb-2">';
			foreach($tabobs as $n)
			{
				if($regroup == 'date') {$listegroup = $n['datefr'];}
				elseif($regroup == 'commune') {$listegroup = $n['loca'];}
				elseif($regroup == 'departement') {$listegroup = $n['locadep'];}
				elseif($regroup == 'observateur') {$listegroup = $n['obs'];}
				if($listegroup == $r)
				{
					$liste .= '<tr>';
					//$liste .= '<div class="col-sm-1">';
					$liste .= '<td><i class="fa fa-check-circle '.$n['vali'].'" data-toggle="tooltip" data-placement="top" title="'.$n['tvali'].'"></i>';
					$liste .= '&nbsp;'.$n['nb'];
					$liste .= '</td>';
					if($regroup == 'date')
					{
						$liste .= '<td>'.$n['afloca'].'</td>';
						$liste .= '<td>'.$n['obs'].'</td>';
						
					}
					elseif($regroup == 'commune')
					{
						$liste .= '<td>'.$n['datefr'].'</td>';
						$liste .= '<td>'.$n['obs'].'</td>';
					}
					elseif($regroup == 'departement')
					{
						$liste .= '<td>'.$n['datefr'].'</td>';
						$liste .= '<td>'.$n['afloca'].'</td>';
						$liste .= '<td>'.$n['obs'].'</td>';
					}
					elseif($regroup == 'observateur')
					{
						$liste .= '<td>'.$n['datefr'].'</td>';
						$liste .= '<td>'.$n['afloca'].'</td>';
					}
					$liste .= '<td>';
					$liste .= '<i class="fa fa-info-circle text-info curseurlien" data-toggle="modal" data-target="#obs" data-idobs="'.$n['idobs'].'" data-photo="'.$n['photo'].'" data-idmor="'.$n['idm'].'"></i>';
					if($n['plusfiche'] != 'non')
					{
						$liste .= '&nbsp;<i class="fa fa-list-ol color1 curseurlien" data-toggle="modal" data-target="#fiche" data-idfiche="'.$n['plusfiche'].'"></i>';
					}
					if($n['photo'] == 'oui')
					{
						$liste .= '&nbsp;<i class="fa fa-camera"></i>';
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