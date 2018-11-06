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
function tablecat($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT table_name FROM information_schema.tables WHERE table_schema='$nomvar' AND table_name='categorie'") or die(print_r($bdd->errorInfo()));
	$table = $req->rowCount();
	$req->closeCursor();
	return $table;		
}
function creecategorie($nomvar)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");	
	$req = $bdd->query("CREATE TABLE $nomvar.categorie (famille integer NOT NULL,cat character varying(3),CONSTRAINT categorie_pkey PRIMARY KEY (famille))") or die(print_r($bdd->errorInfo()));
	$req->closeCursor();
}	
if (isset($_POST['sel']) && isset($_POST['id']) && isset($_POST['lib']))
{	
	$nomvar = $_POST['sel'];	
	$idcat = $_POST['id'];
	$libcat = $_POST['lib'];
	$mod = $_POST['mod'];
	
	if ($mod == 'non')
	{
		$tablecat = tablecat($nomvar);
		if ($tablecat == 0)
		{
			creecategorie($nomvar);			
		}		
	}	
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
	if(isset ($rjson['indice'])) { $datajson['indice'] = $rjson['indice']; }
	if(isset ($rjson['saisie'])) { $datajson['saisie'] = $rjson['saisie']; }
	if(isset($rjson['categorie']))
	{
		$nbcat = count($rjson['categorie']);
		if($mod == 'oui')
		{
			if($nbcat > 1)
			{ 
				foreach($rjson['categorie'] as $n)
				{
					if($n['id'] == $idcat)
					{
						$tmp2 = array(array("id"=>$n['id'],"cat"=>$libcat));		
					}
					else
					{
						$tmp1[] = array("id"=>$n['id'],"cat"=>$n['cat']);
					}		
				}
				$datajson["categorie"] = array_merge($tmp1, $tmp2);	
			}
			else
			{
				$datajson["categorie"] = array(array("id"=>$idcat,"cat"=>$libcat));
			}
		}
		else
		{
			$tmp1 = $rjson['categorie'];
			$tmp2 = array(array("id"=>$idcat,"cat"=>$libcat));			
			$datajson["categorie"] = array_merge($tmp1, $tmp2);	
		}		
	}
	else
	{
		$datajson["categorie"] = array(array("id"=>$idcat,"cat"=>$libcat));
	}
	if(isset ($rjson['systematique'])) { $datajson["systematique"] = $rjson['systematique']; }
	if(isset ($rjson['gen1'])) { $datajson["gen1"] = $rjson['gen1']; }
	if(isset ($rjson['gen2'])) { $datajson["gen2"] = $rjson['gen2']; }
	if(isset ($rjson['statut'])) { $datajson["statut"] = $rjson['statut']; }
	$datajson['description'] = $rjson['description'];
	$ajson = json_encode($datajson);
	if (!$fp = @fopen($filename, 'w+')) 
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Impossible de créer ou d\'écrire le fichier '.$nomvar.'.json dans le répertoire json. Assurez vous d\'avoir les droits nécessaires (CHMOD).</p></div>';
		echo json_encode($retour);	
		exit;
	} 
	else 
	{
		fwrite($fp, $ajson);
		fclose($fp);
		$retour['statut'] = 'Oui';		
	}
	
	echo json_encode($retour);	
}