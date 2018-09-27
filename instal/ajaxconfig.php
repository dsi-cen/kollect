<?php
include '../global/configbase.php';
include '../lib/pdo2.php';
function schema($sctable)
{
	$bdd = PDO2::getInstanceinstall();		
	$req = $bdd->query("SELECT schema_name FROM information_schema.schemata WHERE schema_name = '$sctable' ");
	$sch = $req->rowCount();
	$req->closeCursor();
	return $sch;		
}
function creerschema($sctable)
{
	$bdd = PDO2::getInstanceinstall();		
	$req = $bdd->query("CREATE SCHEMA $sctable");
	$req->closeCursor();
}
function sql($sctable)
{
	$bdd = PDO2::getInstanceinstall();
	$req = null;
	$filename = $sctable.'.sql';
	$req = file_get_contents($filename);
	$req = str_replace("\n","",$req);
	$req = str_replace("\r","",$req);
	$bdd->exec($req);
}
if (isset($_POST['titre']) && isset($_POST['biblio']) && isset($_POST['actu']))
{
	$mail = htmlspecialchars($_POST['mail']);
	$titre = htmlspecialchars($_POST['titre']);
	$stitre = htmlspecialchars($_POST['stitre']);
	$descri = htmlspecialchars($_POST['descri']);
	$metakey = htmlspecialchars($_POST['metakey']);
	$lien = htmlspecialchars($_POST['lien']);
	$biblio = $_POST['biblio'];
	$actu = $_POST['actu'];
	
	if ($lien == '') {
		$lien1 = 'non';		
	} else {
		$lien1 = $lien;
	}
			
	$filename = '../json/site.json';
	$datajson = array();
	$datajson["config"] = 'non';
	$datajson["email"] = $mail;
	$datajson["titre"] = $titre;
	$datajson["stitre"] = $stitre;
	$datajson["description"] = $descri;
	$datajson["metakey"] = $metakey;
	$datajson["lien"] = $lien1;
	$datajson["logo"] = 'non';
	$datajson["biblio"] = $biblio;
	$datajson["actu"] = $actu;
	if ($actu == 'oui') { $datajson["nbactu"] = 3; }
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
		if ($biblio == 'oui')
		{
			$sctable = 'biblio';
			$sch = schema($sctable);
			if ($sch == 0)
			{
				creerschema($sctable);
				$sql = sql($sctable);
			}
		}
		if ($actu == 'oui')
		{
			$sctable = 'actu';
			$sch = schema($sctable);
			if ($sch == 0)
			{
				creerschema($sctable);
				sql($sctable);
			}
		}
		$retour['statut'] = 'Oui';
	}	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Tous les champs ne sont pas remplis.</div>';
}
echo json_encode($retour);	