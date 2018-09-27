<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
function rechercher_rang($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT idrang, rang FROM $nomvar.rang ORDER BY idrang") or die(print_r($bdd->errorInfo()));
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function supprime_sses($nomvar,$id)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("DELETE FROM $nomvar.liste WHERE cdref = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function supprime_es($nomvar,$id)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("DELETE FROM $nomvar.liste WHERE cdref = :id OR cdsup = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function supprime_gn($nomvar,$id)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("SELECT cdnom FROM $nomvar.liste WHERE cdtaxsup = :id and cdnom = cdref ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	$req = $bdd->prepare("DELETE FROM $nomvar.liste WHERE cdtaxsup = :cdnom ") or die(print_r($bdd->errorInfo()));
	foreach ($liste as $n)
	{
		$req->execute(array('cdnom'=>$n['cdnom']));
	}
	$req->closeCursor();
	$req = $bdd->prepare("DELETE FROM $nomvar.genre WHERE cdnom = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->execute();
	$req->closeCursor();
	$req = $bdd->prepare("DELETE FROM $nomvar.liste WHERE cdref = :id OR cdtaxsup = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
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
function supprime_sbfm($nomvar,$id,$liste)
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
	$req = $bdd->prepare("DELETE FROM $nomvar.liste WHERE cdsup = :cdnom ") or die(print_r($bdd->errorInfo()));
	foreach ($listet as $n)
	{
		$req->execute(array('cdnom'=>$n['cdnom']));
	}
	$req->closeCursor();
	$req = $bdd->prepare("DELETE FROM $nomvar.genre WHERE cdnom = :id ") or die(print_r($bdd->errorInfo()));
	foreach ($liste as $n)
	{
		$req->execute(array('id'=>$n['cdnom']));
	}
	$req->closeCursor();
	$req = $bdd->prepare("DELETE FROM $nomvar.liste WHERE cdref = :id OR cdsup = :id ") or die(print_r($bdd->errorInfo()));
	foreach ($liste as $n)
	{
		$req->execute(array('id'=>$n['cdnom']));
	}
	$req->closeCursor();
	$req = $bdd->prepare("DELETE FROM $nomvar.sousfamille WHERE cdnom = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function supprime_fm($nomvar,$id)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("DELETE FROM $nomvar.liste WHERE famille = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
if (isset($_POST['sel']) && isset($_POST['id']) && isset($_POST['rang']))
{	
	$nomvar = $_POST['sel'];	
	$id = $_POST['id'];
	$rang = $_POST['rang'];

	if ($rang == 'SSES')
	{
		$vali = supprime_sses($nomvar,$id);
	}
	elseif ($rang == 'ES')
	{
		$vali = supprime_es($nomvar,$id);
	}
	elseif ($rang == 'GN')
	{
		$vali = supprime_gn($nomvar,$id);
	}
	elseif ($rang == 'SBFM')
	{
		$liste = recherchesfamille($nomvar,$id);
		if (count($liste) > 0)
		{
			$vali = supprime_sbfm($nomvar,$id,$liste);
		}
		$listerang = rechercher_rang($nomvar);
		foreach ($listerang as $n)
		{
			if ($n['idrang'] == 6)
			{
				$liste = recherchesfamille1($nomvar,$id);
				if (count($liste) > 0)
				{
					$vali = supprime_sbfm($nomvar,$id,$liste);
				}		
			}
			if ($n['idrang'] == 5)
			{
				$liste = recherchesfamille2($nomvar,$id);
				if (count($liste) > 0)
				{
					$vali = supprime_sbfm($nomvar,$id,$liste);
				}		
			}
		}	
	}
	elseif ($rang == 'FM')
	{
		$vali = supprime_fm($nomvar,$id);
	}
	if ($vali == 'oui')
	{
		$retour['statut'] = 'Oui';	
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! Problème lors de la suppression de '.$id.' (rang = '.$rang.').</p></div>';
	}	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! Tous les paramètres ne sont pas définit.</p></div>';
}
echo json_encode($retour);	