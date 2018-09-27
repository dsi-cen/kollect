<?php
$titre = 'Session virtuel';
$description = 'Liste des dernières sesssions virtuel';
$script = '<script type="text/javascript" src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = ''; 

if ($_SESSION['droits'] == 4)
{
	include CHEMIN_MODELE.'modif.php';
	$liste = virtuel();
	include CHEMIN_VUE.'virtuel.php';
}
