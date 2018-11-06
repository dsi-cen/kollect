<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function insere($tab)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO statut.statut (cdnom, cdprotect, lr) VALUES (:cdnom, :cdprotect, :lr) ");
	$nb = 0;
	foreach ($tab as $n)
	{
		$req->execute(array('cdnom'=>$n['cdnom'], 'cdprotect'=>$n['cdprotect'], 'lr'=>$n['lr']));
		$nb++;
	}
	$req->closeCursor();
	return $nb;			
}

if(isset($_FILES['file']['name']))
{
	$fichier = $_FILES['file']['name'];
	if(!empty($fichier)) 
	{
		$extensions_valides = array('csv');
		$extension_upload = substr(strrchr($fichier, '.'), 1);
		if (in_array($extension_upload, $extensions_valides)) 
		{
			$destination = '../../../tmp/'. $fichier;
			$location = $_FILES["file"]["tmp_name"];
			if (!move_uploaded_file($location, $destination)) 
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur. Le fichier ne peux pas être copier dans répertoire gestion/tmp</b>.</div>';
				exit();
			}
			else
			{
				if (($liste = fopen("../../../tmp/".$fichier, "r")) !== FALSE) 
				{
					while (($data = fgetcsv($liste, 1000, ";")) !== FALSE) 
					{
						$num = count($data);
						if($num == 3)
						{
							$tab[] = array('cdnom'=>$data[0],'cdprotect'=>$data[1],'lr'=>$data[2]);	
						}
						else
						{
							$er = 'oui';
						}
					}
					unset($data);
					fclose($liste);
				}
				if(!isset($er))
				{
					$nb = insere($tab);
					
					if($nb > 0)
					{
						$retour['statut'] = 'Oui';
						$retour['mes'] = '<div class="alert alert-success" role="alert">'.$nb.' taxons ont été importés.</div>';
					}
					else
					{
						$retour['statut'] = 'Non';
						$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur !  Aucun taxon importé</div>';
					}
				}
				else
				{
					$retour['statut'] = 'Non';
					$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur !  Votre fichier ne comprends pas 3 colonnes</div>';
				}
			}
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