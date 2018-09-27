<?php

if(isset($_POST['version']))
{
	$fichiertmp = '../../../taxref/change'.$_POST['version'].'.csv';
	if(file_exists($fichiertmp)) 
	{
		$fichier = 'change'.$_POST['version'].'.csv';
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun fichier nommé change'.$_POST['version'].'.csv.</div>';
		echo json_encode($retour);	
		exit;
	}
	if(isset($fichier))	 
	{
		if(($liste = fopen("../../../taxref/".$fichier, "r")) !== FALSE) 
		{
			$nbligne = 0;
			while (($data = fgetcsv($liste, 1000, ";")) !== FALSE) 
			{
				$nbligne++;				
			}
			unset($data);
			fclose($liste);
			$retour['nb'] = $nbligne;
			$retour['statut'] = 'Oui';			
			$retour['nomfichier'] = $fichier;
		}		
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Problème lecture du fichier.</div>';	
		}				
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun fichier.</div>';
	}	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun fichier.</div>';
}
echo json_encode($retour);