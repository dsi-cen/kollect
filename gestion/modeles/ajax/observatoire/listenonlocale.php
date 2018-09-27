<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	

function mod_fam($nomvar,$locale)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("UPDATE $nomvar.famille SET locale =:locale ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':locale', $locale);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function mod_gn($nomvar,$locale)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("UPDATE $nomvar.genre SET locale =:locale ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':locale', $locale);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function mod_liste($nomvar,$locale)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("UPDATE $nomvar.liste SET locale =:locale ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':locale', $locale);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}

if(isset($_POST['sel']))
{
	$nomvar = $_POST['sel'];
	$locale = 'non';
	
	$vali = mod_fam($nomvar,$locale);
	if($vali == 'oui')
	{
		$vali = mod_gn($nomvar,$locale);
		if($vali == 'oui')
		{
			$vali = mod_liste($nomvar,$locale);
			if($vali == 'oui')
			{
				$retour['statut'] = 'Oui';
			}
			else
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur mod liste !</div>';
			}			
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur mod genre !</div>';
		}		
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur mod famille!</div>';
	}
	
		
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun observatoire de choisit.</div>';
}
echo json_encode($retour);