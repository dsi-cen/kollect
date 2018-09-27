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
		if ($_FILES['file']['size'] > $maxupbyte)
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Votre fichier est trop lourd.</div>';
			echo json_encode($retour);	
			exit;
		}
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
	if(isset($_POST['cdate']) && !empty($_POST['cdate']))
	{
		$cdate = $_POST['cdate'];
		if($cdate == 'fr')
		{
			$formatdate = 'd/m/Y';
			$datej = date('d/m/Y');
			$cejour = DateTime::createFromFormat($formatdate, $datej);
		}
		elseif($cdate == 'us')
		{
			$formatdate = 'Y-m-d';
			$datej = date('Y-m-d');
			$cejour = DateTime::createFromFormat($formatdate, $datej);
		}
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun format de date de sélectionné.</div>';
		echo json_encode($retour);	
		exit;
	}
	if(isset($fichier))	 
	{
		if (($liste = fopen("../../../tmp/".$fichier, "r")) !== FALSE) 
		{
			$nbligne = 0;
			$nbcolone = 19;
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
										
				$verifdate = validateDate($data[3], $formatdate);
				if($data[4] != '')
				{
					$verifdate2 = validateDate($data[4], $formatdate);
				}							
				if(isset($verifdate2))
				{
					if($verifdate === false || $verifdate2 === false)
					{
						$taberdate[] = $nbligne;								
					}
				}
				elseif($verifdate === false)
				{
					$taberdate[] = $nbligne;								
				}
				
				if($data[4] != '' && $verifdate === true && $verifdate2 === true)
				{
					$date1 = DateTime::createFromFormat($formatdate, $data[3]);
					$date2 = DateTime::createFromFormat($formatdate, $data[4]);
					if($date1 > $date2 || $date2 > $cejour || $date1 > $cejour )
					{
						$taberdatep[] = $nbligne;
					}
				}
				elseif($verifdate === true)
				{
					$date1 = DateTime::createFromFormat($formatdate, $data[3]);
					if($date1 > $cejour )
					{
						$taberdatep[] = $nbligne;
					}
				}
			}
			unset($data);
			fclose($liste);
		}
		if(isset($taber) || isset($taberdate) || isset($taberdatep))
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">';
			if(isset($taber))
			{
				$listeligne = implode(", ", $taber);
				$retour['mes'] .= '<p><b>Erreur. Votre fichier ne comporte pas '.$nbcolone.' colonnes aux lignes :</b> '.$listeligne.'.</p>';
			}
			if(isset($taberdate))
			{
				$listeligne = implode(", ", $taberdate);
				$retour['mes'] .= '<p><b>Erreur. Le format de date n\'est pas bon (ou la date n\'est pas valide) pour les lignes : </b>'.$listeligne.'.</p>';
			}
			if(isset($taberdatep))
			{
				$listeligne = implode(", ", $taberdatep);
				$retour['mes'] .= '<p><b>Erreur. La date ne peut pas être supèrieure à la date du jour '.$datej.' (ou la date1 ne peut pas être supérieure à la date2) pour les lignes : </b>'.$listeligne.'.</p>';
			}
			$retour['mes'] .= '</div>';
		}
		else
		{
			$retour['statut'] = 'Oui';
			$retour['nb'] = $nbligne;
			$retour['nomfichier'] = $fichier;
			$retour['date'] = $cdate;
		}		
	}
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun fichier.</div>';
}
echo json_encode($retour);