<?php 
include '../global/configbase.php';
include '../lib/pdo2.php';

function supmaille($maille)
{
	$bdd = PDO2::getInstanceinstall();		
	$nbsup = $bdd->exec("DELETE FROM referentiel.maillel93 WHERE codel93 IN($maille)");
	return $nbsup;
}
function supmgrs($maille)
{
	$bdd = PDO2::getInstanceinstall();		
	$nbsup = $bdd->exec("DELETE FROM referentiel.mgrs10 WHERE mgrs IN($maille)");
	return $nbsup;
}
function carto93()
{
	$bdd = PDO2::getInstanceinstall();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT codel93 FROM referentiel.maillel93 ");
	$carto93 = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto93;
}
function mgrs()
{
	$bdd = PDO2::getInstanceinstall();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT mgrs, geo FROM referentiel.mgrs10 ");
	$cartomgrs = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $cartomgrs;
}
if (isset($_POST['maille']) && isset($_POST['utm']))
{
	$utm = $_POST['utm'];	
	$maille = $_POST['maille'];
	if($utm == 'oui')
	{
		$nbsup = supmgrs($maille);
		$cartomgrs = mgrs();
		$nbmaille = count($cartomgrs);
		if($nbmaille > 1)
		{
			foreach ($cartomgrs as $n)
			{
				$feature = array('type' => 'feature', 'properties' => Null, 'geometry' => Null);
				$feature['properties']['id'] = $n['mgrs'];
				$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => $n['geo']);
				$Resultats['features'][] = $feature;
				$cartoid[] = array("id"=>$n['mgrs'], "color"=>'#fff');
			}
			$tmpjson = json_encode($Resultats, JSON_NUMERIC_CHECK);
			$tmpjson = str_replace('"[','[',$tmpjson);
			$tmpjson = str_replace(']"',']',$tmpjson);
			$Resultats = json_decode($tmpjson);
			$retour['statut'] = 'Oui';
			$retour['mes'] = '<div class="alert alert-success" role="alert">'.$nbsup.' de supprimer. '.$nbmaille.' mailles sur la carte.</div>';
			$retour['carto'] = $Resultats;
			$retour['cartoid'] = $cartoid;
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Problème dans la suppression des mailles.</div>';		
		}	
	}
	else
	{
		$nbsup = supmaille($maille);
	
		$carto93 = carto93();
		$nbmaille = count($carto93);
		
		if($nbmaille > 1)
		{
			$retour['statut'] = 'Oui';
			$retour['mes'] = '<div class="alert alert-success" role="alert">'.$nbsup.' de supprimer. '.$nbmaille.' mailles 10 x 10 sur la carte.</div>';		
			foreach ($carto93 as $n)
			{
				$xg = substr($n['codel93'], 1, -4)*10000;
				$yb = substr($n['codel93'], 5)*10000;
				$xd = $xg + 10000;
				$yh = $yb + 10000;
							
				$feature = array('type' => 'feature', 'properties' => Null, 'geometry' => Null);
				$feature['properties']['id'] = $n['codel93'];
				$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
				$Resultats['features'][] = $feature;
				$cartoid[] = array("id"=>$n['codel93'], "color"=>'#fff');
			}
			$retour['carto'] = $Resultats;
			$retour['cartoid'] = $cartoid;
		
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Problème dans la suppression des mailles.</div>';		
		}
	}
	$json = file_get_contents('../emprise/emprise.json');
	$rjson = json_decode($json, true);
	$emp = $rjson['emprise'];
	$filename = '../emprise/emprise.json';
	$datajson = array();
	$datajson["contour2"] = $rjson['contour2'];
	$datajson["emprise"] = $rjson['emprise'];
	$datajson["utm"] = $rjson['utm'];
	$datajson["lambert5"] = $rjson['lambert5'];
	$datajson["biogeo"] = $rjson['biogeo'];
	$datajson["nbmaille"] = $nbmaille;
	if($rjson['lambert5'] == 'oui')
	{
		$datajson["nbmaille5"] = $nbmaille * 4;
	}	
	$datajson["lat"] = $rjson['lat'];
	$datajson["lng"] = $rjson['lng'];
	$datajson["sw"] = $rjson['sw'];
	$datajson["ne"] = $rjson['ne'];
	$datajson["stylecontour"] = $rjson['stylecontour'];
	$datajson["stylecontour2"] = $rjson['stylecontour2'];
	$datajson["stylemaille"] = $rjson['stylemaille'];
	$ajson = json_encode($datajson);
	if (!$fp = @fopen($filename, 'w+')) 
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Impossible de créer ou d\'écrire le fichier emprise.json dans le répertoire emprise. Assurez vous d\'avoir les droits nécessaires (CHMOD).</div>';
		echo json_encode($retour);	
		exit;
	} 
	else 
	{
		fwrite($fp, $ajson);
		fclose($fp);
		//$retour['statut'] = 'Oui';
	}	
	echo json_encode($retour, JSON_NUMERIC_CHECK);	
}