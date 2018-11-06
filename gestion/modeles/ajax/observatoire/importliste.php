<?php
/*
	En cas de modification, il faut également vérifier :
	valobservatoir.php
	catinser.php
	importliste.php
	indice.php
	site/mobser.php
*/ 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
function vidertable($nomvar)
{
	$bdd = PDO2::getInstance();		
	$bdd->exec("DELETE FROM $nomvar.systematique");
}
function vidertabletmp()
{
	$bdd = PDO2::getInstance();		
	$bdd->exec("DELETE FROM referentiel.tmpsystem");
}
function vidertabletmpfr()
{
	$bdd = PDO2::getInstance();		
	$bdd->exec("DELETE FROM referentiel.tmpsystemfr");
}
function table($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT table_name FROM information_schema.tables WHERE table_schema='$nomvar' AND table_name='systematique'") or die(print_r($bdd->errorInfo()));
	$table = $req->rowCount();
	$req->closeCursor();
	return $table;		
}
function creersystematique($nomvar)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");	
	$req = $bdd->query("CREATE TABLE $nomvar.systematique (
						cdnom integer NOT NULL,
						ordre smallint,
						gen1 character varying(15),
						gen2 character varying(15),
						rang character varying(5),
						CONSTRAINT systematique_pkey PRIMARY KEY (cdnom))") or die(print_r($bdd->errorInfo()));
	$req->closeCursor();
}
function inser($nomvar,$chemin,$fr)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO $nomvar.systematique (cdnom,ordre,gen1,gen2,rang) VALUES(:cdnom, :ordre, :gen1, :gen2, :rang) ") or die(print_r($bdd->errorInfo()));
	$nb = 0;
	$liste = fgetcsv($chemin, 1024, ';'); 
	while (($liste = fgetcsv($chemin, 1024, ';')) !== false)
	{
		if ($fr == 'non')
		{
			$ordre = ($liste[2] == '' or $liste[2] == 'null') ? null : $liste[2];
			$req->execute(array('cdnom'=>$liste[0], 'ordre'=>$ordre, 'gen1'=>$liste[3], 'gen2'=>$liste[4], 'rang'=>$liste[5]));
		}
		else
		{
			$ordre = ($liste[3] == '' or $liste[3] == 'null') ? null : $liste[3];
			$req->execute(array('cdnom'=>$liste[0], 'ordre'=>$ordre, 'gen1'=>$liste[4], 'gen2'=>$liste[5], 'rang'=>$liste[6]));
		}
		$nb++;
	}
	$req->closeCursor();
	return $nb;		
}
if (isset($_POST['sel']) && isset($_FILES['file']['name']))
{
	$nomvar = $_POST['sel'];
	$fichier = $_FILES['file']['name'];
	$gen1 = $_POST['gen1'];
	$gen2 = $_POST['gen2'];
	
	$nomtmp = 'liste-'.$nomvar.'.csv';
	$nomtmpfr = 'liste-'.$nomvar.'fr.csv';
	if ($nomtmp == $fichier)
	{
		$nomfichier = 'liste-'.$nomvar.'.csv';
		$fr = 'non';
		$ok = 'oui';
	}
	elseif ($nomtmpfr == $fichier)
	{
		$nomfichier = 'liste-'.$nomvar.'fr.csv';
		$fr = 'oui';
		$ok = 'oui';
	}
	else
	{
		$nomfichier = 'liste-'.$nomvar.'.csv ou liste-'.$nomvar.'fr.csv';
		$ok = 'non';
	}
	if ($ok == 'oui')
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
					$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur. Le fichier ne peux pas être copier dans répertoire gestion/fichiers</b>.</p></div>';
					exit();
				}
				else
				{
					$table = table($nomvar);
					if ($table == 0)	
					{
						creersystematique($nomvar);						
					}
					$chemin = fopen("../../../tmp/".$fichier, "r");
					vidertable($nomvar);
					$vali = inser($nomvar,$chemin,$fr);
					fclose($chemin);
					if ($vali > 0)
					{
						$json = file_get_contents('../../../../json/'.$nomvar.'.json');
						$rjson = json_decode($json, true);
						$filename = '../../../../json/'.$nomvar.'.json';
						$datajson = array();
						$datajson['titre'] = $rjson['titre'];
						$datajson['metakey'] = $rjson['metakey'];
						$datajson['icon'] = $rjson['icon'];
						$datajson['couleur'] = $rjson['couleur'];
						$datajson['nomvar'] = $rjson['nomvar'];
						$datajson['nom'] = $rjson['nom'];
						$datajson['nomdeux'] = $rjson['nomdeux'];
						$datajson['latin'] = $rjson['latin'];
						if(isset($rjson['indice'])) { $datajson['indice'] = $rjson['indice']; }
						if(isset($rjson['saisie'])) { $datajson["saisie"] = $rjson['saisie']; }
						if(isset($rjson['categorie'])) { $datajson["categorie"] = $rjson['categorie']; }
						$datajson['systematique'] = 'oui';
						if(!empty($gen1)) { $datajson["gen1"] = $gen1; }
						if(!empty($gen2)) { $datajson["gen2"] = $gen2; }
						if(isset ($rjson['statut'])) { $datajson["statut"] = $rjson['statut']; }
						$datajson['description'] = $rjson['description'];
						$ajson = json_encode($datajson);						
						if (!$fp = @fopen($filename, 'w+')) 
						{
							$retour['statut'] = 'Non';
							$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Impossible d\'écrire dans le fichier '.$nomvar.'.json dans le répertoire json. Assurez vous d\'avoir les droits nécessaires (CHMOD).</p></div>';
							echo json_encode($retour);	
							exit;
						} 
						else 
						{
							fwrite($fp, $ajson);
							fclose($fp);
							$retour['statut'] = 'Oui';							
						}						
					}
					else
					{
						$retour['statut'] = 'Non';
						$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! lors de l\'insertion des lignes dans la table de votre fichiers</p></div>';
					}					
				}				
			}			
		}		
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Vous avez pas téléchargé le fichier <b>'.$nomfichier.'</b> mais <b>'.$fichier.'</b>.</p></div>';
	}	
	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Aucun observatoire de choisit.</p></div>';
}
echo json_encode($retour);