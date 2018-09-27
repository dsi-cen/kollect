<?php
/*
En cas de modification, il faut également vérifier :
general.php
obser.php
mobser.php
fiche.php
/observatoire/indice.php
*/
function schema($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT schema_name FROM information_schema.schemata WHERE schema_name = '$nomvar' ");
	$sch = $req->rowCount();
	$req->closeCursor();
	return $sch;		
}
function creerschema($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("CREATE SCHEMA $nomvar");
	$req->closeCursor();
}
function modif($idmembre,$type,$modif,$datem)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO site.modif (typemodif, modif, datemodif, idmembre)
						VALUES(:type, :modif, :datem, :idm) ");
	$req->bindValue(':type', $type);
	$req->bindValue(':modif', $modif);
	$req->bindValue(':datem', $datem);
	$req->bindValue(':idm', $idmembre);
	$req->execute();
	$req->closeCursor();
}
if (isset($_POST['disc']) && isset($_POST['nom']) && isset($_POST['nomvar']))
{
	include '../../../../global/configbase.php';
	include '../../../../lib/pdo2.php';	
	
	$disc = htmlspecialchars($_POST['disc']);
	$nom = htmlspecialchars($_POST['nom']);
	$nomvar = htmlspecialchars($_POST['nomvar']);
	$icon = htmlspecialchars($_POST['icon']);
	$couleur = htmlspecialchars($_POST['couleur']);
	$titre = htmlspecialchars($_POST['titre']);
	$descri = $_POST['descri'];
	$metakey = htmlspecialchars($_POST['metakey']);
	$nomc = htmlspecialchars($_POST['nomc']);
	$nomdeux = htmlspecialchars($_POST['nomdeux']);
	$latin = htmlspecialchars($_POST['latin']);
	
	$file = '../../../../json/'.$nomvar.'.json';
	if (file_exists($file))
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! Il existe déjà un observatoire avec cet identifiant : <b>'.$nomvar.'</b>.</p></div>';
		echo json_encode($retour);	
		exit;
	}
	$json_site = file_get_contents('../../../../json/site.json');
	$rjson_site = json_decode($json_site, true);
		
	$filename = '../../../../json/site.json';
	$datajson = array();
	$datajson["email"] = $rjson_site['email'];
	$datajson["titre"] = $rjson_site['titre'];
	$datajson["adresse"] = $rjson_site['adresse'];
	$datajson["description"] = $rjson_site['description'];
	$datajson["metakey"] = $rjson_site['metakey'];
	$datajson["lien"] = $rjson_site['lien'];
	$datajson["logo"] = $rjson_site['logo'];
	if(isset($rjson_site['orga'])) { $datajson["orga"] = $rjson_site['orga']; }
	$datajson["biblio"] = $rjson_site['biblio'];
	$datajson["actu"] = $rjson_site['actu'];
	if($rjson_site['actu'] == 'oui') { $datajson["nbactu"] = $rjson_site['nbactu']; }
	if(isset($rjson_site["lieu"]))
	{
		$datajson["lieu"] = $rjson_site['lieu'];
		$datajson["ad1"] = $rjson_site['ad1'];
		$datajson["ad2"] = $rjson_site['ad2'];
	}
	else
	{
		$datajson["lieu"] = '';
		$datajson["ad1"] = '';
		$datajson["ad2"] = '';
	}	
	if(isset($rjson_site['observatoire']))
	{
		$tmp1 = $rjson_site['observatoire'];
		$tmp2 = array(array("nom"=>$nom,"nomvar"=>$nomvar,"discipline"=>$disc,"icon"=>$icon,"couleur"=>$couleur,"latin"=>$latin));
		//$datajson["observatoire"] = array_merge($tmp1, $tmp2);
		$triobser = array_merge($tmp1, $tmp2);
		foreach($triobser as $key => $row) 
		{
			$nomtri[$key]  = $row['nom'];
		}			
		array_multisort($nomtri, SORT_ASC, $triobser);
		$datajson["observatoire"] = $triobser;		
	}
	else
	{
		$datajson["observatoire"] = array(array("nom"=>$nom,"nomvar"=>$nomvar,"discipline"=>$disc,"icon"=>$icon,"couleur"=>$couleur,"latin"=>$latin));
	}
	if(isset($rjson_site['fiche'])) { $datajson["fiche"] = $rjson_site['fiche']; }
	if(isset($rjson_site['indice'])) { $datajson['indice'] = $rjson_site['indice']; }
	$datajson["stitre"] = $rjson_site['stitre'];
	$ajson = json_encode($datajson);
	
	$filename1 = '../../../../json/'.$nomvar.'.json';
	$datajson1 = array();
	$datajson1["titre"] = $titre;
	$datajson1["metakey"] = $metakey;
	$datajson1["icon"] = $icon;
	$datajson1["couleur"] = $couleur;
	$datajson1["nomvar"] = $nomvar;
	$datajson1["nom"] = $nomc;
	$datajson1["nomdeux"] = $nomdeux;
	$datajson1["latin"] = $latin;
	$datajson1["description"] = $descri;
	$ajson1 = json_encode($datajson1);
	
	if (!$fp = @fopen($filename, 'w+')) 
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Impossible de créer ou d\'écrire le fichier site.json dans le répertoire json. Assurez vous d\'avoir les droits nécessaires (CHMOD).</div>';
		echo json_encode($retour);	
		exit;
	} 
	else 
	{
		fwrite($fp, $ajson);
		fclose($fp);
		if (!$fp = @fopen($filename1, 'w+')) 
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Impossible de créer ou d\'écrire le fichier '.$nomvar.'.json dans le répertoire json. Assurez vous d\'avoir les droits nécessaires (CHMOD).</div>';
			echo json_encode($retour);	
			exit;
		} 
		else 
		{
			fwrite($fp, $ajson1);
			fclose($fp);		
			$sch = schema($nomvar);
			if ($sch == 0)
			{
				$schc = creerschema($nomvar);
			}
			$datem = date("Y-m-d H:i:s");
			$idmembre = $_POST['idm'];
			$type = 'Ajout observatoire';
			$modif = 'Ajout de l\'observatoire '.$nom.'';
			modif($idmembre,$type,$modif,$datem);
			$photo200 = '../../../../photo/P200/'.$nomvar;
			$photo400 = '../../../../photo/P400/'.$nomvar;
			$photo800 = '../../../../photo/P800/'.$nomvar;
			if(!is_dir($photo200)){mkdir($photo200);}
			if(!is_dir($photo400)){mkdir($photo400);}
			if(!is_dir($photo800)){mkdir($photo800);}
			$retour['statut'] = 'Oui';
			$retour['mes'] = '<div class="alert alert-success" role="alert">L\'observatoire '.$nom.' a bien été rajouté.</div>';
		}
	}	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Tous les champs ne sont pas remplis.</div>';
}
echo json_encode($retour);