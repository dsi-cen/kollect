<?php
/*
En cas de modification, il faut également vérifier :
general.php
obser.php
mobser.php
fiche.php
/observatoire/indice.php
*/
if (isset($_POST['titre']) && isset($_POST['biblio']) && isset($_POST['actu']))
{
	$mail = htmlspecialchars($_POST['mail']);
	$titre = htmlspecialchars($_POST['titre']);
	$stitre = $_POST['stitre'];
	$descri = htmlspecialchars($_POST['descri']);
	$metakey = htmlspecialchars($_POST['metakey']);
	$adresse = htmlspecialchars($_POST['adresse']);
	$lien = htmlspecialchars($_POST['lien']);
	$logo = htmlspecialchars($_POST['logo']);
	$lieu = htmlspecialchars($_POST['lieu']);
	$ad1 = htmlspecialchars($_POST['ad1']);
	$ad2 = htmlspecialchars($_POST['ad2']);
	$biblio = $_POST['biblio'];
	$actu = $_POST['actu'];
	$idorg = $_POST['idorg'];
	$org = $_POST['org'];
	
	$lien1 = ($lien == '') ? 'non' : $lien;
	
	if ($logo == '') 
	{
		$logo1 = 'non';		
	} 
	else 
	{
		$logo1 = $logo;
		$file = '../../../../dist/img/'.$logo.'';
		if (file_exists($file))
		{
			$taille = filesize($file);
			$taille = round($taille/1024) . ' Ko'; 
			if ($taille >= 100)
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Votre logo est trop lourd ! ('.$taille.'). - Un logo de - de 25 ko est idéal.</p></div>';
				echo json_encode($retour);
				exit;
			}
			elseif ($taille >= 26 and $taille <=99)
			{
				$retour['statut'] = 'Oui';
				$retour['mes'] = '<div class="alert alert-warning" role="alert"><p>Votre logo est trop lourd ! ('.$taille.'). - Un logo de - de 25 ko est idéal.</p></div>';
			}
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Assurer vous que votre logo '.$logo.' se trouve bien dans le répertoire dist/img.</p></div>';
			echo json_encode($retour);
			exit;
		}
	}
	
	$json_site = file_get_contents('../../../../json/site.json');
	$rjson_site = json_decode($json_site, true);
	
	$filename = '../../../../json/site.json';
	$datajson = array();
	$datajson["email"] = $mail;
	$datajson["titre"] = $titre;
	$datajson["adresse"] = $adresse;
	$datajson["description"] = $descri;
	$datajson["metakey"] = $metakey;
	$datajson["lien"] = $lien1;
	$datajson["logo"] = $logo1;
	if(!empty($idorg)) { $datajson["orga"] = ['id'=>$idorg, 'nom'=>$org]; }
	$datajson["biblio"] = $biblio;
	$datajson["actu"] = $actu;
	if($actu == 'oui') { $datajson["nbactu"] = $_POST['nbactu']; }
	$datajson["lieu"] = $lieu;
	$datajson["ad1"] = $ad1;
	$datajson["ad2"] = $ad2;
	if(isset($rjson_site['observatoire'])) { $datajson["observatoire"] = $rjson_site['observatoire']; }
	if(isset($rjson_site['fiche'])) { $datajson["fiche"] = $rjson_site['fiche']; }
	if(isset($rjson_site['indice'])) { $datajson['indice'] = $rjson_site['indice']; }
	$datajson["stitre"] = $stitre;
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
		$retour['statut'] = 'Oui';
		$retour['mes'] = '<div class="alert alert-success" role="alert">Les changements ont été pris en compte.</div>';
	}	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Tous les champs ne sont pas remplis.</div>';
}
echo json_encode($retour);