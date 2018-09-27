<?php
if(isset($_POST['titre']))
{
	$titre = htmlspecialchars($_POST['titre']);
	$descri = htmlspecialchars($_POST['descri']);
	$metakey = htmlspecialchars($_POST['metakey']);
			
	$json = file_get_contents('../../../../json/biblio.json');
	$rjson = json_decode($json, true);
	
	$filename = '../../../../json/biblio.json';
	$datajson = array();
	$datajson["titre"] = $titre;
	$datajson["description"] = $descri;
	$datajson["metakey"] = $metakey;
	$ajson = json_encode($datajson);
	if (!$fp = @fopen($filename, 'w+')) 
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Impossible de créer ou d\'écrire le fichier biblio.json dans le répertoire json. Assurez vous d\'avoir les droits nécessaires (CHMOD).</div>';
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