<?php
if (isset($_FILES['file']['name']))
{
	$fichier = $_FILES['file']['name'];
	if (!empty($fichier)) 
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
				$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur. Le fichier ne peux pas être copier dans répertoire gestion/tmp</b>.</p></div>';
				exit();
			}
			else
			{
				if (($liste = fopen("../../../tmp/".$fichier, "r")) !== FALSE) 
				{
					$nbligne = 0;
					$nbcolone = 4;
					while (($data = fgetcsv($liste, 1000, ";")) !== FALSE) 
					{
						$num = count($data);
						if($num == $nbcolone)
						{
							$nbligne++;
						}
						else
						{
							$nbligne++;
							$taber[] = $nbligne;
						}						
					}
					unset($data);
					fclose($liste);
				}
				if(isset($taber))
				{
					$retour['statut'] = 'Non';
					$listeligne = implode(", ", $taber);
					$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur. Votre fichier ne comporte pas '.$nbcolone.' colonnes aux lignes : '.$listeligne.'</b>.</p></div>';
				}
				else
				{
					$retour['statut'] = 'Oui';
					$retour['nb'] = $nbligne;
					$retour['nomfichier'] = $fichier;
				}				
			}
		}
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Aucun fichier.</p></div>';
	}
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Aucun fichier.</p></div>';
}
echo json_encode($retour);