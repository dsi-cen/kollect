<?php
function validateDate($date, $format = 'Y-m-d')
{
	$d = DateTime::createFromFormat($format, $date);
	return $d && $d->format($format) == $date;
}
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

if(isset($_FILES['file']['name']) || isset($_POST['ftp']))
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
	elseif(isset($_POST['ftp']))
	{
		$fichiertmp = '../../../tmp/'.$_POST['ftp'].'.csv';
		if(file_exists($fichiertmp)) 
		{
			$fichier = $_POST['ftp'].'.csv';
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun fichier nommé '.$_POST['ftp'].'.csv.</div>';
			echo json_encode($retour);	
			exit;
		}
	}		
	if(isset($fichier))	 
	{
		$listevali = [1,2,3,4,5,6,7];
		if (($liste = fopen("../../../tmp/".$fichier, "r")) !== FALSE) 
		{
			$nbligne = 0;
			$nbcolone = 23;
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
				//verification cdnom
				if(preg_match('#[^0-9]#', $data[2]))
				{
					$tabercdnom[] = $nbligne;
				}
				//verification idfiche
				if(preg_match('#[^0-9]#', $data[1]) || $data[1] == '')
				{
					$taberidfiche[] = $nbligne;
				}
				//verification code vali
				if(!in_array($data[7], $listevali)) 
				{
					$tabervali[] = $nbligne;		
				}
				//verification stade
				if(preg_match('#[^0-9]#', $data[11]))
				{
					$taberstade[] = $nbligne;
				}
			}
			unset($data);
			fclose($liste);
		}
		if(isset($taber) || isset($tabercdnom) || isset($tabervali) || isset($taberidfiche) || isset($taberstade))
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">';
			if(isset($taber))
			{
				$listeligne = implode(", ", $taber);
				$retour['mes'] .= '<p><b>Erreur. Votre fichier ne comporte pas '.$nbcolone.' colonnes aux lignes :</b> '.$listeligne.'.</p>';
			}
			if(isset($tabercdnom))
			{
				$listeligne = implode(", ", $tabercdnom);
				$retour['mes'] .= '<p><b>Erreur. cdnom non valide pour les lignes : </b>'.$listeligne.'.</p>';
			}
			if(isset($tabervali))
			{
				$listeligne = implode(", ", $tabervali);
				$retour['mes'] .= '<p><b>Erreur. La colonne 8 n\'est pas bonne. Vérifier les lignes : </b>'.$listeligne.'</p>';
			}
			if(isset($taberidfiche))
			{
				$listeligne = implode(", ", $taberidfiche);
				$retour['mes'] .= '<p><b>Erreur. Problème sur l\'idfiche. Vérifier les lignes : </b>'.$listeligne.'</p>';
			}
			if(isset($taberstade))
			{
				$listeligne = implode(", ", $taberstade);
				$retour['mes'] .= '<p><b>Erreur. Problème sur l\'idstade. Vérifier les lignes : </b>'.$listeligne.'</p>';
			}
			$retour['mes'] .= '</div>';
		}
		else
		{
			$retour['statut'] = 'Oui';
			$retour['nb'] = $nbligne;
			$retour['nomfichier'] = $fichier;			
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