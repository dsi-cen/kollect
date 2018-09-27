<?php 

if(isset($_POST['sel']))
{	
	$nomvar = $_POST['sel'];
	
	$json = file_get_contents('../../../../json/'.$nomvar.'.json');
	$rjson = json_decode($json, true);
	
	if(isset($rjson['indice']))
	{
		$choix = $rjson['indice']['choix'];
		$retour['obs'] = ($choix == 'obs') ? $rjson['indice']['valchoix'] : '';
		$retour['es'] = ($choix == 'es') ? $rjson['indice']['valchoix'] : '';
		$retour['choix'] = $choix;
		$retour['maillage'] = (isset($rjson['indice']['maillage'])) ? $rjson['indice']['maillage'] : 'l93';
		$retour['date'] = (isset($rjson['indice']['date'])) ? substr($rjson['indice']['date'], 0, 4) : '';		
	}
	else
	{
		$retour['obs'] = '';
		$retour['es'] = '';
		$retour['maillage'] = 'l93';
		$retour['date'] = '';
	}
	
	$retour['statut'] = 'Oui';		
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);	