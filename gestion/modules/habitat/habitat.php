<?php
$titre = 'Gestion des habitats';
$description = 'Configuration des habitats';
$script = '<script type="text/javascript" src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>
<script type="text/javascript" src="dist/js/confighabitat.js" defer></script>';
$css = '';
if ($_SESSION['droits'] >= 3)
{
	include CHEMIN_MODELE.'habitat.php';
	
	$niv1 = niveau1();
	$niv2 = niveau2();
	$niv3 = niveau3();
	$niv4 = niveau4();
	$niv5 = niveau5();
	$niv6 = niveau6();
	
	include CHEMIN_VUE.'habitat.php';	
}

