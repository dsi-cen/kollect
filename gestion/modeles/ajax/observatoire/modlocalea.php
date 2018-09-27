<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
function rang($cdnom,$nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("SELECT rang, cdtaxsup, famille, genre FROM $nomvar.liste WHERE cdnom = :cdnom") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdnom, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;		
}
function mod_esnon($nomvar,$cdnom,$locale)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("UPDATE $nomvar.liste SET locale =:locale WHERE cdref = :cdnom OR cdsup = :cdnom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdnom, PDO::PARAM_INT);
	$req->bindValue(':locale', $locale);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;
}
function mod_ssesnon($nomvar,$cdnom,$locale)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("UPDATE $nomvar.liste SET locale =:locale WHERE cdref = :cdnom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdnom, PDO::PARAM_INT);
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
function mod_famille($nomvar,$famille,$locale)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("UPDATE $nomvar.famille SET locale =:locale WHERE cdnom = :fam ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':fam', $famille, PDO::PARAM_INT);
	$req->bindValue(':locale', $locale);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function recherche_sup($nomvar,$id)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("SELECT DISTINCT cdtaxsup FROM $nomvar.liste WHERE cdref = :id AND cdsup != 0 ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function recherche_genre($nomvar,$id)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM $nomvar.liste WHERE cdtaxsup = :id AND locale = 'oui' ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function recherche_famille($nomvar,$id)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM $nomvar.liste WHERE famille = :id AND locale = 'oui' ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
/*function recherche_sses($nomvar,$id)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM $nomvar.liste WHERE cdsup = :id AND locale = 'oui' ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}*/
function recherche_sses($nomvar,$id)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("SELECT cdnom FROM $nomvar.liste WHERE cdsup = :id AND locale = 'oui' ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}

if(isset($_POST['sel']) && isset($_POST['cdnom']))
{	
	$nomvar = $_POST['sel'];	
	$cdnom = intval($_POST['cdnom']);
	$locale = $_POST['coche'];
	
	$rang = rang($cdnom,$nomvar);
	
	if($rang['rang'] == 'ES')
	{
		if($locale == 'non')
		{
			$nbsses = recherche_sses($nomvar,$cdnom);
			if($nbsses != false)
			{
				foreach($nbsses as $n)
				{
					$tabmod[] = $n['cdnom'];
				}
				$retour['tabmod'] = $tabmod;
			}
			$vali = mod_esnon($nomvar,$cdnom,$locale);
			$nbgenre = recherche_genre($nomvar,$rang['cdtaxsup']);
			if($nbgenre == 0)
			{
				$vali = mod_genre($nomvar,$rang['cdtaxsup'],$locale);
				$vali = mod_esnon($nomvar,$rang['cdtaxsup'],$locale);
				$nbfam = recherche_famille($nomvar,$rang['famille']);
				if($nbfam == 0)
				{
					$vali = mod_famille($nomvar,$rang['famille'],$locale);
				}
			}
		}
		else
		{
			$tabid = array($cdnom);
			$tabid[] = $rang['cdtaxsup'];
			$id = $rang['cdtaxsup'];
			$vali = mod_genre($nomvar,$id,$locale);			
			$vali = mod_oui($nomvar,$tabid,$locale);
			$vali = mod_famille($nomvar,$rang['famille'],$locale);
		}		
	}
	elseif($rang['rang'] == 'SSES')
	{
		if($locale == 'non')
		{
			$vali = mod_ssesnon($nomvar,$cdnom,$locale);
		}
		else
		{
			$tabid = array($cdnom);
			$tabid[] = $rang['cdtaxsup'];
			$retour['tabsup'] = $rang['cdtaxsup'];
			$cdsup = recherche_sup($nomvar,$rang['cdtaxsup']);
			$tabid[] = $cdsup['cdtaxsup'];
			$id = $cdsup['cdtaxsup'];
			$vali = mod_genre($nomvar,$id,$locale);			
			$vali = mod_oui($nomvar,$tabid,$locale);
			$vali = mod_famille($nomvar,$rang['famille'],$locale);			
		}		
	}
	
	if($vali == 'oui')
	{
		$retour['statut'] = 'Oui';	
	}
	else
	{
		$retour['vali'] = $vali;
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! Problème lors de la modification de '.$cdnom.'.</p></div>';
	}	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! Tous les paramètres ne sont pas définit.</p></div>';
}
echo json_encode($retour);	