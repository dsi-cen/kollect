<?php
/*
En cas de modification, il faut également vérifier :
couche.php
*/
if(isset($_POST['ign']))
{
	$ign = htmlspecialchars($_POST['ign']);
	$color = $_POST['color'];
	$weight = $_POST['weight'];
	$opacity = $_POST['opacity'];
	$colorm = $_POST['colorm'];
	$weightm = $_POST['weightm'];
	$opacitym = $_POST['opacitym'];
	$couche = $_POST['couche'];
	$proche = $_POST['proche'];
	$l935 = $_POST['l935'];
		
	$json = file_get_contents('../../../../emprise/emprise.json');
	$rjson = json_decode($json, true);
		
	$filename = '../../../../emprise/emprise.json';
	$datajson = array();
	$datajson['contour2'] = $rjson['contour2'];
	$datajson['emprise'] = $rjson['emprise'];
	$datajson['utm'] = $rjson['utm'];
	$datajson['lambert5'] = $rjson['lambert5'];
	$datajson['biogeo'] = $rjson['biogeo'];
	$datajson['nbmaille'] = $rjson['nbmaille'];
	if($rjson['lambert5'] == 'oui') { $datajson['nbmaille5'] = $l935; }		
	$datajson['lat'] = $rjson['lat'];
	$datajson['lng'] = $rjson['lng'];
	$datajson['sw'] = $rjson['sw'];
	$datajson['ne'] = $rjson['ne'];
	$datajson['couche'] = $couche;
	if($proche != '') { $datajson['proche'] = $proche; }
	$datajson['cleign'] = $ign;
	$datajson['stylecontour'] = array("color"=>$color,"weight"=>$weight,"opacity"=>$opacity);
	if($rjson['emprise'] != 'fr')
	{
		$color2 = $_POST['color2'];
		$weight2 = $_POST['weight2'];
		$datajson["stylecontour2"] = array("color"=>$color2,"weight"=>$weight2);
	}
	$datajson["stylemaille"] = array("color"=>$colorm,"weight"=>$weightm,"opacity"=>$opacitym);
	if(isset($rjson['couchesup']))
	{
		$datajson['couchesup'] = $rjson['couchesup'];
	}
		
	$ajson = json_encode($datajson);
		
	if(!$fp = @fopen($filename, 'w+')) 
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Impossible de créer ou d\'écrire le fichier emprise.json dans le répertoire json. Assurez vous d\'avoir les droits nécessaires (CHMOD).</div>';
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