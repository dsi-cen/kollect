<?php
/*
En cas de modification, il faut également vérifier :
general.php
obser.php
mobser.php
fiche.php
/observatoire/indice.php
*/
if (isset($_POST['nban']) && isset($_POST['cartefiche']))
{
	if($_POST['nban'] == 6)
	{
		$c6 = array('classe'=>'classe6','annee'=>$_POST['a6'],'label'=>$_POST['la6'],'couleur'=>$_POST['ca6']);
		$c5 = array('classe'=>'classe5','annee'=>$_POST['a5'],'label'=>$_POST['la5'],'couleur'=>$_POST['ca5']);
		$c4 = array('classe'=>'classe4','annee'=>$_POST['a4'],'label'=>$_POST['la4'],'couleur'=>$_POST['ca4']);
		$c3 = array('classe'=>'classe3','annee'=>$_POST['a3'],'label'=>$_POST['la3'],'couleur'=>$_POST['ca3']);
		$c2 = array('classe'=>'classe2','annee'=>$_POST['a2'],'label'=>$_POST['la2'],'couleur'=>$_POST['ca2']);
		$c1 = array('classe'=>'classe1','couleur'=>$_POST['ca1']);
		$classe = array($c1,$c2,$c3,$c4,$c5,$c6);
	}
	if($_POST['nban'] == 5)
	{
		$c5 = array('classe'=>'classe5','annee'=>$_POST['a5'],'label'=>$_POST['la5'],'couleur'=>$_POST['ca5']);
		$c4 = array('classe'=>'classe4','annee'=>$_POST['a4'],'label'=>$_POST['la4'],'couleur'=>$_POST['ca4']);
		$c3 = array('classe'=>'classe3','annee'=>$_POST['a3'],'label'=>$_POST['la3'],'couleur'=>$_POST['ca3']);
		$c2 = array('classe'=>'classe2','annee'=>$_POST['a2'],'label'=>$_POST['la2'],'couleur'=>$_POST['ca2']);
		$c1 = array('classe'=>'classe1','couleur'=>$_POST['ca1']);
		$classe = array($c1,$c2,$c3,$c4,$c5);
	}
	elseif($_POST['nban'] == 4)
	{
		$c4 = array('classe'=>'classe4','annee'=>$_POST['a4'],'label'=>$_POST['la4'],'couleur'=>$_POST['ca4']);
		$c3 = array('classe'=>'classe3','annee'=>$_POST['a3'],'label'=>$_POST['la3'],'couleur'=>$_POST['ca3']);
		$c2 = array('classe'=>'classe2','annee'=>$_POST['a2'],'label'=>$_POST['la2'],'couleur'=>$_POST['ca2']);
		$c1 = array('classe'=>'classe1','couleur'=>$_POST['ca1']);
		$classe = array($c1,$c2,$c3,$c4);
	}
	elseif($_POST['nban'] == 3)
	{
		$c3 = array('classe'=>'classe3','annee'=>$_POST['a3'],'label'=>$_POST['la3'],'couleur'=>$_POST['ca3']);
		$c2 = array('classe'=>'classe2','annee'=>$_POST['a2'],'label'=>$_POST['la2'],'couleur'=>$_POST['ca2']);
		$c1 = array('classe'=>'classe1','couleur'=>$_POST['ca1']);
		$classe = array($c1,$c2,$c3);
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
	$datajson["lieu"] = $rjson_site['lieu'];
	$datajson["ad1"] = $rjson_site['ad1'];
	$datajson["ad2"] = $rjson_site['ad2'];
	if(isset($rjson_site['observatoire'])) { $datajson["observatoire"] = $rjson_site['observatoire']; }
	if(isset($_POST['new']) && isset($_POST['alt']))
	{
		$datajson['fiche'] = array('cartefiche'=>$_POST['cartefiche'], 'cartebilancouleur'=>$_POST['bilan'], 'graphdebut'=> $_POST['graphdebut'], 'classefiche'=> $classe, 'legendenouv'=> $_POST['canew'], 'alt'=> $_POST['alt']);
	}
	elseif(isset($_POST['new']) && !isset($_POST['alt']))
	{
		$datajson['fiche'] = array('cartefiche'=>$_POST['cartefiche'], 'cartebilancouleur'=>$_POST['bilan'], 'graphdebut'=> $_POST['graphdebut'], 'classefiche'=> $classe, 'legendenouv'=> $_POST['canew']);
	}
	elseif(!isset($_POST['new']) && isset($_POST['alt']))
	{
		$datajson['fiche'] = array('cartefiche'=>$_POST['cartefiche'], 'cartebilancouleur'=>$_POST['bilan'], 'graphdebut'=> $_POST['graphdebut'], 'classefiche'=> $classe, 'alt'=> $_POST['alt']);
	}
	else
	{
		$datajson['fiche'] = array('cartefiche'=>$_POST['cartefiche'], 'cartebilancouleur'=>$_POST['bilan'], 'graphdebut'=> $_POST['graphdebut'], 'classefiche'=> $classe);
	}
	/*$datajson["cartefiche"] = $_POST['cartefiche'];
	$datajson["cartebilancouleur"] = $_POST['bilan'];
	$datajson["graphdebut"] = $_POST['graphdebut'];
	$datajson["classefiche"] = $classe;
	if(isset($_POST['new'])) { $datajson["legendenouv"] = $_POST['canew']; }
	if(isset($_POST['alt'])) { $datajson["alt"] = $_POST['alt']; }*/
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
		$retour['statut'] = 'Oui';
		$retour['mes'] = '<div class="alert alert-success" role="alert">Les changements ont été pris en compte.</div>';
	}		
}
else
{
	$retour['statut'] = 'Non';
	//$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Tous les champs ne sont pas remplis.</p></div>';
}
echo json_encode($retour);