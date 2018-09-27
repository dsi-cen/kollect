<?php
if (($_SESSION['droits'] == 4) OR isset($_SESSION['virtuel']))
{
	$titre = 'Gestion des membres';
	$description = 'Gestion des membres';
	$scripthaut = '<script type="text/javascript" src="../dist/js/jquery.js"></script>';
	$script = '<script src="../dist/js/bootstrap.min.js" defer></script>
	<script src="../dist/js/jquery-auto.js" defer></script>
	<script type="text/javascript" src="../dist/js/jquery.dataTables.min.js" defer></script>';
	$css = '<link rel="stylesheet" type="text/css" href="../dist/css/dataTables.bootstrap4.css">';
	
	if (isset($rjson_site['observatoire']))
	{
		foreach ($rjson_site['observatoire'] as $n)
		{
			$discipline[] = $n['nomvar'];	
		}
	}
	else
	{
		$discipline = '';
	}
	include CHEMIN_VUE.'membre.php';
}