<?php
$titre = 'Export liste';
$description = 'Export liste';
$scripthaut = '<script type="text/javascript" src="../dist/js/jquery.js"></script>
<script type="text/javascript" src="dist/js/tableExport.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>
<script type="text/javascript" src="dist/js/filesaver.js"></script>';
$css = '<link rel="stylesheet" href="dist/css/tableexport.css" />';
if ($_SESSION['droits'] >= 3)
{
	include CHEMIN_MODELE.'import.php';
	$sel = $_GET['d'];
	$listeno = listeno($sel);
	$listeok = listeok($sel);
	$listepr = listepr($sel);
	
	include CHEMIN_VUE.'export.php';	
}