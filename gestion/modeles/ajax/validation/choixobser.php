<?php

if(isset($_POST['sel']))
{
	$nomvar = $_POST['sel'];
	$json = file_get_contents('../../../../json/'.$nomvar.'.json');
	$rjson = json_decode($json, true);
	if(isset($rjson['saisie']['stade']))
	{
		$retour['stade'] = $rjson['saisie']['stade'];
		$retour['statut'] = 'Oui';
	}
	else
	{
		$retour['statut'] = 'Non';
	}
}
else
{
	$retour['statut'] = 'Non';	
}
echo json_encode($retour);