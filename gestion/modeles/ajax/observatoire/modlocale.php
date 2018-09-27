<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
function rechercher_rang($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT idrang FROM $nomvar.rang ORDER BY idrang") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function recherche_sup($nomvar,$id)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("SELECT DISTINCT cdtaxsup FROM $nomvar.liste WHERE cdref = :id AND cdsup != 0 ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->execute();
	$cdsup = $req->fetch();
	$req->closeCursor();
	return $cdsup;	
}
function mod_ssesnon($nomvar,$id,$locale)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("UPDATE $nomvar.liste SET locale =:locale WHERE cdref = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->bindValue(':locale', $locale);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function mod_oui($nomvar,$tabid,$locale)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("UPDATE $nomvar.liste SET locale =:locale WHERE cdref = :id ") or die(print_r($bdd->errorInfo()));
	foreach ($tabid as $n)
	{
		$req->bindValue(':id', $n, PDO::PARAM_INT);
		$req->bindValue(':locale', $locale);
		$vali = ($req->execute()) ? 'oui' : 'non';
	}
	$req->closeCursor();
	return $vali;	
}
function mod_genre($nomvar,$id,$locale)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("UPDATE $nomvar.genre SET locale =:locale WHERE cdnom = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->bindValue(':locale', $locale);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function mod_esnon($nomvar,$id,$locale)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("UPDATE $nomvar.liste SET locale =:locale WHERE cdref = :id OR cdsup = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->bindValue(':locale', $locale);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function mod_gn($nomvar,$id,$locale)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("SELECT cdnom FROM $nomvar.liste WHERE cdtaxsup = :id and cdnom = cdref ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	$req = $bdd->prepare("UPDATE $nomvar.liste SET locale =:locale WHERE cdtaxsup = :cdnom ") or die(print_r($bdd->errorInfo()));
	foreach ($liste as $n)
	{
		$req->execute(array('cdnom'=>$n['cdnom'], 'locale'=>$locale));
	}
	$req->closeCursor();
	$req = $bdd->prepare("UPDATE $nomvar.genre SET locale =:locale WHERE cdnom = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->bindValue(':locale', $locale);
	$req->execute();
	$req->closeCursor();
	$req = $bdd->prepare("UPDATE $nomvar.liste SET locale =:locale WHERE cdref = :id OR cdtaxsup = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->bindValue(':locale', $locale);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function recherchesfamille($nomvar,$id)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("SELECT genre.cdnom, genre FROM $nomvar.genre
						INNER JOIN $nomvar.sousfamille ON sousfamille.cdnom = genre.cdsup
						WHERE sousfamille.cdnom = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;
}
function recherchesfamille1($nomvar,$id)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("SELECT genre.cdnom, genre FROM $nomvar.genre
						INNER JOIN $nomvar.tribu ON tribu.cdnom = genre.cdsup 
						INNER JOIN $nomvar.sousfamille ON sousfamille.cdnom = tribu.cdsup
						WHERE sousfamille.cdnom = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;
}
function recherchesfamille2($nomvar,$id)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("SELECT genre.cdnom, genre FROM $nomvar.genre
						INNER JOIN $nomvar.soustribu ON soustribu.cdnom = genre.cdsup
						INNER JOIN $nomvar.tribu ON tribu.cdnom = soustribu.cdsup 
						INNER JOIN $nomvar.sousfamille ON sousfamille.cdnom = tribu.cdsup
						WHERE sousfamille.cdnom = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;
}
function mod_sbfm($nomvar,$id,$liste,$locale)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("SELECT cdnom FROM $nomvar.liste WHERE cdsup = :id and cdnom = cdref ") or die(print_r($bdd->errorInfo()));
	foreach($liste as $n)
	{
		$req->execute(array('id'=>$n['cdnom']));
		$listetmp = $req->fetchAll(PDO::FETCH_ASSOC);
		foreach ($listetmp as $n)
		{
			$listet[] = array('cdnom'=>$n['cdnom']);
		}		
	}
	$req->closeCursor();
	$req = $bdd->prepare("UPDATE $nomvar.liste SET locale =:locale WHERE cdsup = :cdnom ") or die(print_r($bdd->errorInfo()));
	foreach ($listet as $n)
	{
		$req->execute(array('cdnom'=>$n['cdnom'], 'locale'=>$locale));
	}
	$req->closeCursor();
	$req = $bdd->prepare("UPDATE $nomvar.genre SET locale =:locale WHERE cdnom = :id ") or die(print_r($bdd->errorInfo()));
	foreach ($liste as $n)
	{
		$req->execute(array('id'=>$n['cdnom'], 'locale'=>$locale));
	}
	$req->closeCursor();
	$req = $bdd->prepare("UPDATE $nomvar.liste SET locale =:locale WHERE cdref = :id OR cdsup = :id ") or die(print_r($bdd->errorInfo()));
	foreach ($liste as $n)
	{
		$req->execute(array('id'=>$n['cdnom'], 'locale'=>$locale));
	}
	$req->closeCursor();
	$req = $bdd->prepare("UPDATE $nomvar.sousfamille SET locale =:locale WHERE cdnom = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->bindValue(':locale', $locale);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function mod_fm($nomvar,$id,$locale,$sfm)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("UPDATE $nomvar.liste SET locale =:locale WHERE famille = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->bindValue(':locale', $locale);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	if ($vali == 'oui')
	{
		$req = $bdd->prepare("UPDATE $nomvar.famille SET locale =:locale WHERE cdnom = :id ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->bindValue(':locale', $locale);
		$vali = ($req->execute()) ? 'oui' : 'non';
		$req->closeCursor();
		if ($vali == 'oui')
		{
			$req = $bdd->prepare("UPDATE $nomvar.genre SET locale =:locale WHERE cdsup = :id ") or die(print_r($bdd->errorInfo()));
			$req->bindValue(':id', $id, PDO::PARAM_INT);
			$req->bindValue(':locale', $locale);
			$vali = ($req->execute()) ? 'oui' : 'non';
			$req->closeCursor();
			if ($vali == 'oui' && $sfm == 'oui')
			{
				$req = $bdd->prepare("UPDATE $nomvar.sousfamille SET locale =:locale WHERE cdsup = :id ") or die(print_r($bdd->errorInfo()));
				$req->bindValue(':id', $id, PDO::PARAM_INT);
				$req->bindValue(':locale', $locale);
				$vali = ($req->execute()) ? 'oui' : 'non';
				$req->closeCursor();
			}
		}
	}	
	return $vali;	
}
function mod_sbfmcdtaxsup($nomvar,$id,$locale,$rangtr,$rangstr)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("SELECT cdsup FROM $nomvar.genre WHERE cdnom = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->execute();
	$listegn = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	if($rangtr == 'non' && $rangstr == 'non')
	{
		$req = $bdd->prepare("UPDATE $nomvar.sousfamille SET locale =:locale WHERE cdnom = :id ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':id',$listegn['cdsup'], PDO::PARAM_INT);
		$req->bindValue(':locale', $locale);
		$vali = ($req->execute()) ? 'oui' : 'non';
		$req->closeCursor();
	}
	else
	{
		$req = $bdd->prepare("SELECT cdnom FROM $nomvar.sousfamille WHERE cdnom = :id ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':id',$listegn['cdsup'], PDO::PARAM_INT);
		$req->execute();
		$listesbfm = $req->fetch(PDO::FETCH_ASSOC);
		$req->closeCursor();
		if($listesbfm != '')
		{
			$req = $bdd->prepare("UPDATE $nomvar.sousfamille SET locale =:locale WHERE cdnom = :id ") or die(print_r($bdd->errorInfo()));
			$req->bindValue(':id',$listegn['cdsup'], PDO::PARAM_INT);
			$req->bindValue(':locale', $locale);
			$vali = ($req->execute()) ? 'oui' : 'un';
			$req->closeCursor();
		}
		else
		{		
			/*$req = $bdd->prepare("UPDATE $nomvar.sousfamille SET locale =:locale WHERE cdnom = :id ") or die(print_r($bdd->errorInfo()));
			$req->bindValue(':id',$listegn['cdsup'], PDO::PARAM_INT);
			$req->bindValue(':locale', $locale);
			$vali = ($req->execute()) ? 'oui' : 'un';
			$req->closeCursor();	*/		
			
			$req = $bdd->prepare("SELECT cdsup FROM $nomvar.tribu WHERE cdnom = :id ") or die(print_r($bdd->errorInfo()));
			$req->bindValue(':id',$listegn['cdsup'], PDO::PARAM_INT);
			$req->execute();
			$listet = $req->fetch(PDO::FETCH_ASSOC);
			$req->closeCursor();
			if($listet != '')
			{
				$req = $bdd->prepare("UPDATE $nomvar.sousfamille SET locale =:locale WHERE cdnom = :id ") or die(print_r($bdd->errorInfo()));
				$req->bindValue(':id',$listet['cdsup'], PDO::PARAM_INT);
				$req->bindValue(':locale', $locale);
				$vali = ($req->execute()) ? 'oui' : 'deux';
				$req->closeCursor();
			}
			elseif($rangstr == 'oui')
			{
				$req = $bdd->prepare("SELECT cdsup FROM $nomvar.soustribu WHERE cdnom = :id ") or die(print_r($bdd->errorInfo()));
				$req->bindValue(':id',$listegn['cdsup'], PDO::PARAM_INT);
				$req->execute();
				$listest = $req->fetch(PDO::FETCH_ASSOC);
				$req->closeCursor();
				if ($listest != '')
				{
					$req = $bdd->prepare("SELECT cdsup FROM $nomvar.tribu WHERE cdnom = :id ") or die(print_r($bdd->errorInfo()));
					$req->bindValue(':id',$listest['cdsup'], PDO::PARAM_INT);
					$req->execute();
					$listet = $req->fetch(PDO::FETCH_ASSOC);
					$req->closeCursor();
					if ($listet != '')
					{
						$req = $bdd->prepare("UPDATE $nomvar.sousfamille SET locale =:locale WHERE cdnom = :id ") or die(print_r($bdd->errorInfo()));
						$req->bindValue(':id',$listet['cdsup'], PDO::PARAM_INT);
						$req->bindValue(':locale', $locale);
						$vali = ($req->execute()) ? 'oui' : 'trois';
						$req->closeCursor();
					}			
				}
			}
			else
			{
				$vali = 'oui';
			}
		}
	}
	return $vali;
}

if(isset($_POST['sel']) && isset($_POST['id']) && isset($_POST['rang']))
{	
	$nomvar = $_POST['sel'];	
	$id = $_POST['id'];
	$rang = $_POST['rang'];
	$locale = $_POST['coche'];
	
	$listerang = rechercher_rang($nomvar);
	if($rang == 'SSES')
	{
		if($locale == 'non')
		{
			$vali = mod_ssesnon($nomvar,$id,$locale);
		}
		else
		{
			$cdsup = recherche_sup($nomvar,$id);
			$tabid = array($id);
			$tabid[] = $cdsup['cdtaxsup'];
			$id = $cdsup['cdtaxsup'];
			$cdsup = recherche_sup($nomvar,$id);
			$tabid[] = $cdsup['cdtaxsup'];
			$id = $cdsup['cdtaxsup'];
			$vali = mod_genre($nomvar,$id,$locale);
			$vali = mod_oui($nomvar,$tabid,$locale);
			$rangtr = 'non';
			$rangstr = 'non';
			foreach($listerang as $n)
			{
				if($n['idrang'] == 7) {$rangsbfm = 'oui';}
				elseif($n['idrang'] == 6) {$rangtr = 'oui';}
				elseif($n['idrang'] == 5) {$rangstr = 'oui';}
			}
			if(isset($rangsbfm))
			{
				$vali = mod_sbfmcdtaxsup($nomvar,$id,$locale,$rangtr,$rangstr);
			}
		}						
	}
	elseif($rang == 'ES')
	{
		if($locale == 'non')
		{
			$vali = mod_esnon($nomvar,$id,$locale);
		}
		else
		{
			$cdsup = recherche_sup($nomvar,$id);
			$tabid = array($id);
			$tabid[] = $cdsup['cdtaxsup'];
			$id = $cdsup['cdtaxsup'];
			$vali = mod_genre($nomvar,$id,$locale);			
			$vali = mod_oui($nomvar,$tabid,$locale);
			
			$rangtr = 'non';
			$rangstr = 'non';
			foreach($listerang as $n)
			{
				if($n['idrang'] == 7) {$rangsbfm = 'oui';}
				elseif($n['idrang'] == 6) {$rangtr = 'oui';}
				elseif($n['idrang'] == 5) {$rangstr = 'oui';}
			}
			if(isset($rangsbfm))
			{
				$vali = mod_sbfmcdtaxsup($nomvar,$id,$locale,$rangtr,$rangstr);
			}
		}		
	}
	elseif($rang == 'GN')
	{
		$vali = mod_gn($nomvar,$id,$locale);
		$rangtr = 'non';
		$rangstr = 'non';
		foreach($listerang as $n)
		{
			if($n['idrang'] == 7) { $rangsbfm = 'oui'; }
			elseif($n['idrang'] == 6) { $rangtr = 'oui'; }
			elseif($n['idrang'] == 5) { $rangstr = 'oui'; }
		}
		if(isset($rangsbfm))
		{
			$vali = mod_sbfmcdtaxsup($nomvar,$id,$locale,$rangtr,$rangstr);
		}
	}
	elseif($rang == 'SBFM')
	{
		$liste = recherchesfamille($nomvar,$id);
		if(count($liste) > 0)
		{
			$vali = mod_sbfm($nomvar,$id,$liste,$locale);
		}		
		foreach($listerang as $n)
		{
			if($n['idrang'] == 6)
			{
				$liste = recherchesfamille1($nomvar,$id);
				if(count($liste) > 0)
				{
					$vali = mod_sbfm($nomvar,$id,$liste,$locale);
				}
			}	
			elseif($n['idrang'] == 5)
			{
				$liste = recherchesfamille2($nomvar,$id);
				if (count($liste) > 0)
				{
					$vali = mod_sbfm($nomvar,$id,$liste,$locale);
				}
			}
		}		
	}
	elseif($rang == 'FM')
	{
		$sfm = $_POST['sfm'];
		$vali = mod_fm($nomvar,$id,$locale,$sfm);
	}
	if($vali == 'oui')
	{
		$retour['statut'] = 'Oui';	
	}
	else
	{
		$retour['vali'] = $vali;
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! Problème lors de la modification de '.$id.' (rang = '.$rang.').</p></div>';
	}	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! Tous les paramètres ne sont pas définit.</p></div>';
}
echo json_encode($retour);	