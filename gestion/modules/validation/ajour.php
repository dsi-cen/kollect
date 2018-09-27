<?php
$titre = 'Validation mise à jour';
$description = 'Validation mise à jour';
$scripthaut = '<script type="text/javascript" src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

if($_SESSION['droits'] == 4)
{
	
	include CHEMIN_VUE.'ajour.php';	
}