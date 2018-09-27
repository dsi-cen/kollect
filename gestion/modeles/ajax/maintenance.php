<?php
if(isset($_POST['choix']))
{
	$choix = $_POST['choix'];	
	
	$filename = '../../../json/maintenance.json';
	
	$datajson = array();
	$datajson["etat"] = ($choix == 'm') ? 'Maintenance' : 'Production';
	$ajson = json_encode($datajson);
	
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
		$retour['statut'] = 'Oui';
		$retour['etat'] = ($choix == 'm') ? 'Maintenance' : 'Production';
	}	
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);