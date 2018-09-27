<?php
$titre = 'Validation - Info';
$description = 'Validation des données du site';
$script = '<script type="text/javascript" src="../dist/js/jquery.js"></script>
<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

if ($_SESSION['droits'] >= 2)
{
	include CHEMIN_MODELE.'validation.php';
	
	
	
	include CHEMIN_VUE.'info.php';	
}