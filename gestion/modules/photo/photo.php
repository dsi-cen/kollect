<?php
$titre = 'Gestion Photo';
$description = 'Gestion des photos du site';
$script = '<script type="text/javascript" src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>
<script src="../dist/js/jquery-auto.js" defer></script>
<script src="../dist/js/popup-image.js" defer></script>
<script src="dist/js/photo.js" defer></script>';
$css = '<link rel="stylesheet" href="../dist/css/popup.css" type="text/css">';

if($_SESSION['droits'] >= 3)
{
	if(isset($_GET['cdnom']))
	{
		$cdnom = $_GET['cdnom'];
		$getcdnom = 'oui';
		include CHEMIN_MODELE.'photo.php';
		$r = recup($cdnom);
	}
	else
	{
		$cdnom = '';
		$getcdnom = 'non';
	}
	
	include CHEMIN_VUE.'photo.php';
}
