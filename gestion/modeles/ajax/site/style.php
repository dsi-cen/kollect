<?php
if (isset($_POST['choix']))
{
	$choix = $_POST['choix'];
	if(@copy('../../../../dist/css/'.$choix.'.css','../../../../dist/css/color.css'))
	{
		$filename = '../../../../json/style.json';
		$datajson = array();
		$datajson["choix"] = $choix;
		$ajson = json_encode($datajson);
		if (!$fp = @fopen($filename, 'w+')) 
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger mt-1" role="alert">Impossible de créer ou d\'écrire le fichier style.json dans le répertoire json. Assurez vous d\'avoir les droits nécessaires (CHMOD).</div>';
			echo json_encode($retour);	
			exit;
		}
		else 
		{
			fwrite($fp, $ajson);
			fclose($fp);
			$retour['statut'] = 'Oui';
			$retour['mes'] = '<div class="alert alert-success mt-1" role="alert">Changement effectué.<b> '.$choix.' </b>est votre nouveau thème.</div>';
		}	
	}
	else
	{
		$retour['mes'] = '<div class="alert alert-danger mt-1" role="alert">Erreur ! lors de la copie du fichier '.$choix.'.css</div>';
		$retour['statut'] = 'Non';
	}	
}
echo json_encode($retour);