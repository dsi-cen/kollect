<?php
if(isset($_POST['version']))
{
	$datajson = array();
	$datajson["version"] = $_POST['version'];
	$datajson["maj"] = 'oui';

	$ajson = json_encode($datajson);
	$filename = '../../../../json/taxref.json';
	
	if (!$fp = @fopen($filename, 'w+')) 
	{
		$retour['statut'] = 'Non';
		echo json_encode($retour);	
		exit;
	} 
	else 
	{
		fwrite($fp, $ajson);
		fclose($fp);
	}
	
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';	
}
echo json_encode($retour);