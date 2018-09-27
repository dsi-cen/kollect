<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function return_bytes($FormattedSize)
{
   $FormattedSize = trim($FormattedSize);
   $Size = floatval($FormattedSize);
   $MultipSize = strtoupper(substr($FormattedSize, -1));
 
   if($MultipSize == "G") $Size *= pow(1024, 3);
   else if($MultipSize == "M") $Size *= pow(1024, 2);
   else if($MultipSize == "K") $Size *= 1024;
 
   return $Size;
}
$maxup = ini_get('upload_max_filesize');
$maxupbyte = return_bytes($maxup);

if(isset($_FILES['file']['name']))
{
	if(isset($_FILES['file']['name']))
	{
		$retour['taille'] = $maxupbyte;
		if($_FILES['file']['size'] > $maxupbyte || $_FILES['file']['size'] == 0)
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Votre fichier est trop lourd</div>';
			echo json_encode($retour);	
			exit;
		}
		$fichier = $_FILES['file']['name'];	
		if(!empty($fichier)) 
		{
			$extensions_valides = array('csv');
			$extension_upload = substr(strrchr($fichier, '.'), 1);
			if(in_array($extension_upload, $extensions_valides)) 
			{
				$destination = '../../../tmp/'. $fichier;
				$location = $_FILES["file"]["tmp_name"];
				if (!move_uploaded_file($location, $destination)) 
				{
					$retour['statut'] = 'Non';
					$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur. Le fichier ne peux pas être copier dans répertoire gestion/tmp</b>.</p></div>';
					exit();
				}				
			}
			else
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert">Votre fichier doit-être au format csv.</div>';
				echo json_encode($retour);	
				exit;
			}
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun fichier ou fichier vide.</div>';
			echo json_encode($retour);	
			exit;
		}	
	}
	if(isset($fichier))	 
	{
		if(($liste = fopen("../../../tmp/".$fichier, "r")) !== FALSE) 
		{
			$nbligne = 0;
			if($_POST['choix'] == 'plte') { $nbcolone = 4; }
			elseif($_POST['choix'] == 'coll') { $nbcolone = 8; }
			elseif($_POST['choix'] == 'hab') { $nbcolone = 2; }
			elseif($_POST['choix'] == 'mort') { $nbcolone = 3; }
			elseif($_POST['choix'] == 'piaf') { $nbcolone = 3; }
			while(($data = fgetcsv($liste, 1000, ";")) !== FALSE) 
			{
				$num = count($data);
				
				if($num != $nbcolone)
				{
					$nbligne++;
					$taber[] = $nbligne;
				}
				else
				{
					$nbligne++;
				}						
			}
			unset($data);
			fclose($liste);
		}
		if(isset($taber))
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">';
			if(isset($taber))
			{
				$listeligne = implode(", ", $taber);
				$retour['mes'] .= '<p><b>Erreur. Votre fichier ne comporte pas '.$nbcolone.' colonnes aux lignes :</b> '.$listeligne.'.</p>';
			}
			$retour['mes'] .= '</div>';
		}
		else
		{
			$retour['statut'] = 'Oui';
			$retour['nb'] = $nbligne;
			$retour['nomfichier'] = $fichier;
			$retour['choix'] = $_POST['choix'];
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