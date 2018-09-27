<?php
if (isset($_SESSION['prenom']))
{
	$json_m = file_get_contents('json/maintenance.json');
	$maintenance = json_decode($json_m, true);
	
	if($maintenance['etat'] == 'Production')
	{
		$titre = 'Ajout de photos';
		$description = 'Ajout de photo par '.$_SESSION['prenom'].' '.$_SESSION['nom'].'.';
		$script = '<script src="dist/js/jquery.js" defer></script>
		<script src="dist/js/bootstrap.min.js" defer></script>
		<script src="dist/js/jquery-saisie.js" defer></script>
		<script src="dist/js/jquery.cropit.js" defer></script>
		<script src="dist/js/photo.js" defer></script>';
		$css = '<link rel="stylesheet" href="dist/css/jquery-ui.css" />';
			
		if(isset($_SESSION['idobs']))
		{
			$getidobs =  $_SESSION['idobs'];
			unset($_SESSION['idobs']);
		}
		else
		{
			$getidobs = '';
		}
			
		$datej = date('Y-m-d');
		
		include CHEMIN_VUE.'ajoutphoto.php';
	}
	else
	{
		header('location:index.php?module=maintenance&action=maintenance');
	}
}
else
{
	header('location:index.php');
}