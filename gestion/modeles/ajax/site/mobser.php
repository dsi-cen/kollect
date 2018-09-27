<?php
/*
En cas de modification, il faut également vérifier :
general.php
obser.php
mobser.php
fiche.php
/observatoire/indice.php
-> si modif rjson_obs
/observatoire/valobservatoire.php
/observatoire/statutval.php
/observatoire/catinser.php
/observatoire/importliste.php
/observatoire/indice.php
*/
$functionSansAccent = function ($chaine) {
	if (version_compare(PHP_VERSION, '5.2.3', '>='))
	{
		$str = htmlentities($chaine, ENT_NOQUOTES, "UTF-8", false);
	}
	else
	{
		$str = htmlentities($chaine, ENT_NOQUOTES, "UTF-8");
	}
	$str = preg_replace('#\&([A-za-z])(?:acute|cedil|circ|grave|ring|tilde|uml)\;#', '\1', $str);
	return $str;
};
if (isset($_POST['disc']) && isset($_POST['nom']) && isset($_POST['nomvar']))
{
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
	$datajson["lieu"] = $rjson_site['lieu'];
	$datajson["ad1"] = $rjson_site['ad1'];
	$datajson["ad2"] = $rjson_site['ad2'];
	$nbobser = count($rjson_site['observatoire']);
	if($nbobser > 1)
	{
		foreach($rjson_site['observatoire'] as $n)
		{
			if($n['nomvar'] == $nomvar)
			{
				$tmp2 = array(array("nom"=>$nom,"nomvar"=>$nomvar,"discipline"=>$disc,"icon"=>$icon,"couleur"=>$couleur,"latin"=>$latin));			
			}
			else
			{
				$tmp1[] = array("nom"=>$n['nom'],"nomvar"=>$n['nomvar'],"discipline"=>$n['discipline'],"icon"=>$n['icon'],"couleur"=>$n['couleur'],"latin"=>$n['latin']);
			}		
		}
		$triobser = array_merge($tmp1, $tmp2);
		foreach ($triobser as $key => $row) 
		{
			$nomtri[$key]  = $row['nom'];
		}
		$array_sans_accent = array_map($functionSansAccent , $nomtri);		
		array_multisort($array_sans_accent, SORT_ASC, $triobser);
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
		$json_obs = file_get_contents('../../../../json/'.$nomvar.'.json');
		$rjson_obs = json_decode($json_obs, true);
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
		if(isset($rjson_obs['indice'])) { $datajson1['indice'] = $rjson_obs['indice']; }
		if(isset($rjson_obs['saisie'])) { $datajson1["saisie"] = $rjson_obs['saisie']; }
		if(isset($rjson_obs['categorie'])) { $datajson1["categorie"] = $rjson_obs['categorie']; }
		if(isset($rjson_obs['systematique'])) { $datajson1["systematique"] = $rjson_obs['systematique']; }
		if(isset($rjson_obs['gen1'])) { $datajson1["gen1"] = $rjson_obs['gen1']; }
		if(isset($rjson_obs['gen2'])) { $datajson1["gen2"] = $rjson_obs['gen2']; }
		if(isset($rjson_obs['statut'])) { $datajson1["statut"] = $rjson_obs['statut']; }
		$datajson1["description"] = $descri;
		$ajson1 = json_encode($datajson1);
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
			$retour['statut'] = 'Oui';
			$retour['mes'] = '<div class="alert alert-success" role="alert">L\'observatoire '.$nom.' a bien été modifié.</div>';
		}
	}	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Tous les champs ne sont pas remplis.</div>';
}
echo json_encode($retour);