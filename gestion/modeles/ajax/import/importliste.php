<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function vidertable()
{
	$bdd = PDO2::getInstance();		
	$bdd->exec("DELETE FROM import.verifcdnom");
}
function inser($chemin)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO import.verifcdnom (nom) VALUES(:nom) ") or die(print_r($bdd->errorInfo()));
	$nb = 0;
	$liste = fgetcsv($chemin, 1024, ';'); 
	while (($liste = fgetcsv($chemin, 1024, ';')) !== false)
	{
		$req->execute(array('nom'=>$liste[0]));
		$nb++;
	}
	$req->closeCursor();
	return $nb;		
}
function listeok($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT COUNT(cdnom) FROM $nomvar.liste
						INNER JOIN import.verifcdnom ON verifcdnom.nom = liste.nom 
						WHERE cdnom = cdref") or die(print_r($bdd->errorInfo()));
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function listepr($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT COUNT(cdnom) FROM $nomvar.liste
						INNER JOIN import.verifcdnom ON verifcdnom.nom = liste.nom 
						WHERE cdnom != cdref") or die(print_r($bdd->errorInfo()));
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function listeno($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT COUNT(nom) FROM import.verifcdnom
						WHERE NOT EXISTS (SELECT cdnom FROM $nomvar.liste 
						WHERE (liste.nom = verifcdnom.nom))") or die(print_r($bdd->errorInfo()));
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
if (isset($_POST['sel']) && isset($_FILES['file']['name']))
{
	$nomvar = $_POST['sel'];
	$fichier = $_FILES['file']['name'];
	if ($nomvar != '' && $nomvar != 'NR')
	{	
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
					$chemin = fopen("../../../tmp/".$fichier, "r");
					vidertable();
					$vali = inser($chemin);
					fclose($chemin);
					$retour['nb'] = $vali;
					$listeok = listeok($nomvar);
					$retour['nbok'] = $listeok;
					$listepr = listepr($nomvar);
					$retour['nbpr'] = $listepr;
					$listeno = listeno($nomvar);
					$retour['nbno'] = $listeno;
					$retour['statut'] = 'Oui';									
				}				
			}			
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun fichier importé !</div>';
		}
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun observatoire de choisit.</div>';
	}	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun observatoire de choisit.</div>';
}
echo json_encode($retour);