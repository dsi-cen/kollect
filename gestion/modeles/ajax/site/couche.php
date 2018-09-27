<?php
/*
En cas de modification, il faut également vérifier :
carto.php
*/
if(isset($_POST['titre']))
{
	$titre = htmlspecialchars($_POST['titre']);
	$stitre = $_POST['stitre'];
	$type = $_POST['type'];
	$uni = $_POST['uni'];
	
	if(!empty($titre))
	{
		$json = file_get_contents('../../../../emprise/emprise.json');
		$rjson = json_decode($json, true);
		
		$numcouche = (isset($rjson['couchesup'])) ? count($rjson['couchesup']) + 1 : 1;
		
		if($numcouche == 1)
		{
			$tmp = ($type == 'gen') ? array('id'=>$numcouche,'titre'=>$titre,'type'=>$type) : array('id'=>$numcouche,'titre'=>$titre,'stitre'=>$stitre,'uni'=>$uni,'type'=>$type);
			$couche = array('couche'.$numcouche => $tmp);
		} 
		elseif($numcouche > 1)
		{
			$tmp = ($type == 'gen') ? array('id'=>$numcouche,'titre'=>$titre,'type'=>$type) : array('id'=>$numcouche,'titre'=>$titre,'stitre'=>$stitre,'uni'=>$uni,'type'=>$type);
			$tmp2 = array('couche'.$numcouche => $tmp);
			$tmp1 = $rjson['couchesup'];
			$couche = array_merge($tmp1, $tmp2);
		}
		
		$retour['nb'] = $numcouche;
				
		$filename = '../../../../emprise/emprise.json';
		$datajson = array();
		$datajson['contour2'] = $rjson['contour2'];
		$datajson['emprise'] = $rjson['emprise'];
		$datajson['utm'] = $rjson['utm'];
		$datajson['lambert5'] = $rjson['lambert5'];
		$datajson['biogeo'] = $rjson['biogeo'];
		$datajson['nbmaille'] = $rjson['nbmaille'];
		if($rjson['lambert5'] == 'oui') { $datajson['nbmaille5'] = $rjson['nbmaille5']; }	
		$datajson['lat'] = $rjson['lat'];
		$datajson['lng'] = $rjson['lng'];
		$datajson['sw'] = $rjson['sw'];
		$datajson['ne'] = $rjson['ne'];
		$datajson['couche'] = (isset($rjson['couche'])) ? $rjson['couche'] : 'osm' ;
		if(isset($rjson['proche'])) { $datajson['proche'] = $rjson['proche']; }
		$datajson['cleign'] = (isset($rjson['cleign'])) ? $rjson['cleign'] : '' ;
		$datajson['stylecontour'] = (isset($rjson['stylecontour'])) ? $rjson['stylecontour'] : '' ;
		if($rjson['emprise'] != 'fr')
		{
			$datajson["stylecontour2"] = (isset($rjson['stylecontour2'])) ? $rjson['stylecontour2'] : '' ;
		}
		$datajson["stylemaille"] = (isset($rjson['stylemaille'])) ? $rjson['stylemaille'] : '' ;
		$datajson['couchesup'] = $couche;
					
		$ajson = json_encode($datajson);
			
		if (!$fp = @fopen($filename, 'w+')) 
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
		}	
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun titre pour votre couche !</div>';
	}	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Tous les champs ne sont pas remplis.</p></div>';
}
echo json_encode($retour);