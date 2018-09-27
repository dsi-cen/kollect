<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
	
function recupdecade($nomvar,$stade)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT cdref, array_agg(DISTINCT iddecade) AS decade FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.ligneobs USING(idobs)
						LEFT JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
						LEFT JOIN referentiel.decade ON decade.decade = fiche.decade
						WHERE observa = :observa AND stade = :stade AND (validation = 1 OR validation = 2)
						GROUP BY cdref ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':observa', $nomvar);
	$req->bindValue(':stade', $stade);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recupobs($cdref,$iddecade,$stade)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idobs, nom, idfiche, cdref, to_char(date1, 'DD/MM/YYYY') AS datefr FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.ligneobs USING(idobs)
						INNER JOIN referentiel.decade ON decade.decade = fiche.decade
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE cdref = :cdref AND iddecade = :dec AND stade = :stade AND (validation = 1 OR validation = 2) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdref', $cdref, PDO::PARAM_INT);
	$req->bindValue(':dec', $iddecade, PDO::PARAM_INT);
	$req->bindValue(':stade', $stade);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['sel']) && isset($_POST['stade']))
{
	$nomvar = $_POST['sel'];
	$stade = $_POST['stade'];
	$dec = $_POST['dec'];
	
	$liste = recupdecade($nomvar,$stade);
	foreach($liste as $n)
	{
		$decade = substr($n['decade'],1,-1);
		$listedec[$n['cdref']] = explode(',', $decade);		
	}
	foreach($listedec as $cle => $e)
	{
		if(count($e) > 1)
		{
			$val = $e[0];
			foreach($e as $n)
			{
				if($n > ($val + $dec))
				{
					//$tab1[$cle][] = $val;
					//$tab[$cle][] = $n;
					$tab1[$cle] = $val;
					$tab[$cle] = $n;
				}
				$val = $n;				
			}			
		}		
	}
	if(isset($tab))
	{	
		$nb = count($tab);
		$l = ($nb > 1) ? $nb.' observations pouvant être érronés :' : $nb.' observation pouvant être érroné :'; 
		foreach($tab1 as $cle => $e)
		{
			$tabresult1[] = recupobs($cle,$e,$stade);			
		}
		foreach($tab as $cle => $e)
		{
			$tabcdref[] = $cle;
			/*foreach($e as $n)
			{
				$tabceref[] = $cle;
				$tabresult[] = recupobs($cle,$n,$stade);
			}*/
			$tabresult[] = recupobs($cle,$e,$stade);
		}
		$tabcdref =	array_flip($tabcdref);
		
		$l .= '<ul>';
		foreach($tabresult1 as $n)
		{
			if(isset($tabcdref[$n['cdref']]))
			{
				$l .= '<li><a href="../observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$nomvar.'&amp;id='.$n['cdref'].'">'.$n['nom'].'</a>';
			}
			$l .= ' observation n° <a href="../index.php?module=observation&amp;action=detail&amp;idobs='.$n['idobs'].'">'.$n['idobs'].'</a> du '.$n['datefr'].' relevé <a href="../index.php?module=observation&amp;action=fiche&amp;idfiche='.$n['idfiche'].'">'.$n['idfiche'].'</a>';
			foreach($tabresult as $r)
			{
				if($r['cdref'] == $n['cdref'])
				{
					$l .= ' - espacée de plus de '.$dec.' decades';
					$l .= ' de observation n° <a href="../index.php?module=observation&amp;action=detail&amp;idobs='.$r['idobs'].'">'.$r['idobs'].'</a> du '.$r['datefr'].' relevé <a href="../index.php?module=observation&amp;action=fiche&amp;idfiche='.$r['idfiche'].'">'.$r['idfiche'].'</a></li>';
				}
			}			
			//$l .= '<li>observation n° <a href="../index.php?module=observation&amp;action=detail&amp;idobs='.$n['idobs'].'">'.$n['idobs'].'</a> de <a href="../observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$nomvar.'&amp;id='.$n['cdref'].'">'.$n['nom'].'</a> sur le relevé <a href="../index.php?module=observation&amp;action=fiche&amp;idfiche='.$n['idfiche'].'">'.$n['idfiche'].'</a></li>';
		}
		$l .= '</ul>';		
	}
	else
	{
		$l = 'Aucun résultat';
	}
	
	$retour['liste'] = $l;
	$retour['statut'] = 'Oui';	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun observatoire de choisit.</div>';
}
echo json_encode($retour);