<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
function mod_niv6($id,$locale)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("UPDATE referentiel.eunis SET locale = :locale WHERE cdhab = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->bindValue(':locale', $locale);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function mod_niv5($id,$locale)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("UPDATE referentiel.eunis SET locale = :locale WHERE cdhab = :id OR cdhabsup = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->bindValue(':locale', $locale);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function mod_niv4($id,$locale)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("WITH sel AS (
							SELECT cdhab FROM referentiel.eunis WHERE cdhab = :id OR cdhabsup = :id
						)
						UPDATE referentiel.eunis SET locale = :locale FROM sel 
						WHERE sel.cdhab = eunis.cdhab OR sel.cdhab = eunis.cdhabsup ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->bindValue(':locale', $locale);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function mod_niv3($id,$locale)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("WITH sel AS (
							SELECT cdhab FROM referentiel.eunis WHERE cdhab = :id OR cdhabsup = :id
						),
						sel2 AS (
							SELECT eunis.cdhab FROM sel 
							INNER JOIN referentiel.eunis ON eunis.cdhabsup = sel.cdhab OR eunis.cdhab = sel.cdhab
						)
						UPDATE referentiel.eunis SET locale = :locale FROM sel2 
						WHERE sel2.cdhab = eunis.cdhab OR sel2.cdhab = eunis.cdhabsup ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->bindValue(':locale', $locale);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function mod_niv2($id,$locale)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("WITH sel AS (
							SELECT cdhab FROM referentiel.eunis WHERE cdhab = :id OR cdhabsup = :id
						),
						sel2 AS (
							SELECT eunis.cdhab FROM sel 
							INNER JOIN referentiel.eunis ON eunis.cdhabsup = sel.cdhab OR eunis.cdhab = sel.cdhab
						),
						sel3 AS (
							SELECT eunis.cdhab from sel2 
							INNER JOIN referentiel.eunis ON eunis.cdhabsup = sel2.cdhab OR eunis.cdhab = sel2.cdhab
						)
						UPDATE referentiel.eunis SET locale = :locale FROM sel3 
						WHERE sel3.cdhab = eunis.cdhab OR sel3.cdhab = eunis.cdhabsup ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->bindValue(':locale', $locale);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function mod_niv1($id,$locale)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("WITH sel AS (
							SELECT cdhab FROM referentiel.eunis WHERE cdhab = :id OR cdhabsup = :id
						),
						sel2 AS (
							SELECT eunis.cdhab FROM sel 
							INNER JOIN referentiel.eunis ON eunis.cdhabsup = sel.cdhab OR eunis.cdhab = sel.cdhab
						),
						sel3 AS (
							SELECT eunis.cdhab from sel2 
							INNER JOIN referentiel.eunis ON eunis.cdhabsup = sel2.cdhab OR eunis.cdhab = sel2.cdhab
						),
						sel4 AS (
							SELECT eunis.cdhab FROM sel3 
							INNER JOIN referentiel.eunis ON eunis.cdhabsup = sel3.cdhab OR eunis.cdhab = sel3.cdhab
						)
						UPDATE referentiel.eunis SET locale = :locale FROM sel4 
						WHERE sel4.cdhab = eunis.cdhab OR sel4.cdhab = eunis.cdhabsup ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->bindValue(':locale', $locale);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function recherche_sup($id)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("SELECT pathcdhab FROM referentiel.eunis WHERE cdhab = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->execute();
	$liste = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;	
}
function mod_niv($id,$locale)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare('UPDATE referentiel.eunis SET locale = :locale WHERE cdhab IN('.$id.') ') or die(print_r($bdd->errorInfo()));
	$req->bindValue(':locale', $locale);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}

if(isset($_POST['id']) && isset($_POST['niv']))
{	
	$id = $_POST['id'];
	$niv = $_POST['niv'];
	$locale = $_POST['coche'];
	
	if($niv == 'n6')
	{
		if($locale == 'non')
		{
			$vali = mod_niv6($id,$locale);
		}
		else
		{
			$sup = recherche_sup($id);
			$tmp = explode('/', $sup['pathcdhab']);
			foreach($tmp as $n)
			{
				if($n != '')
				{
					$t[] = $n;
				}
			}
			$tmp = implode(',', $t);
			$vali = mod_niv($tmp,$locale);			
		}
	}	
	elseif($niv == 'n5')
	{
		if($locale == 'non')
		{
			$vali = mod_niv5($id,$locale);
		}
		else
		{
			$sup = recherche_sup($id);
			$tmp = explode('/', $sup['pathcdhab']);
			foreach($tmp as $n)
			{
				if($n != '')
				{
					$t[] = $n;
				}
			}
			$tmp = implode(',', $t);
			$vali = mod_niv($tmp,$locale);			
		}
	}	
	elseif($niv == 'n4')
	{
		if($locale == 'non')
		{
			$vali = mod_niv4($id,$locale);
		}
		else
		{
			$sup = recherche_sup($id);
			$tmp = explode('/', $sup['pathcdhab']);
			foreach($tmp as $n)
			{
				if($n != '')
				{
					$t[] = $n;
				}
			}
			$tmp = implode(',', $t);
			$vali = mod_niv($tmp,$locale);			
		}
	}	
	elseif($niv == 'n3')
	{
		if($locale == 'non')
		{
			$vali = mod_niv3($id,$locale);
		}
		else
		{
			$sup = recherche_sup($id);
			$tmp = explode('/', $sup['pathcdhab']);
			foreach($tmp as $n)
			{
				if($n != '')
				{
					$t[] = $n;
				}
			}
			$tmp = implode(',', $t);
			$vali = mod_niv($tmp,$locale);			
		}
	}
	elseif($niv == 'n2')
	{
		if($locale == 'non')
		{
			$vali = mod_niv2($id,$locale);
		}
		else
		{
			$sup = recherche_sup($id);
			$tmp = explode('/', $sup['pathcdhab']);
			foreach($tmp as $n)
			{
				if($n != '')
				{
					$t[] = $n;
				}
			}
			$tmp = implode(',', $t);
			$vali = mod_niv($tmp,$locale);			
		}
	}
	elseif($niv == 'n1')
	{
		$vali = 'oui';
		$id = substr($id, 1);
		if($locale == 'non')
		{
			$vali = mod_niv1($id,$locale);
		}
		else
		{
			$vali = 'oui';						
		}
	}
	if($vali == 'oui')
	{
		$retour['statut'] = 'Oui';	
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! Problème lors de la modification de '.$id.' (niveau = '.$niv.').</div>';
	}	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! Tous les paramètres ne sont pas définit.</div>';
}
echo json_encode($retour);	