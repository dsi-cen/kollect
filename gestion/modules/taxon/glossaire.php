<?php
$titre = 'Gestion du glossaire';
$description = 'Gestion du glossaire du site';
$script = '<script type="text/javascript" src="../js/jquery.js" defer></script>
<script src="../js/bootstrap.min.js" defer></script>';
$css = '';

if ($_SESSION['droits'] >= 3)
{
	
	
	include CHEMIN_VUE.'glossaire.php';	
}