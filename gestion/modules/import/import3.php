<?php
$titre = 'Import';
$description = 'Import de données';
$script = '<script type="text/javascript" src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>
<script type="text/javascript" src="dist/js/import.js" defer></script>';
$css = '';

function return_bytes($FormattedSize)
{
   $FormattedSize = trim($FormattedSize);
   $Size = floatval($FormattedSize);
   $MultipSize = strtoupper(substr($FormattedSize, -1));
 
   if($MultipSize == "G") $Size *= pow(1024, 3);
   else if($MultipSize == "M") $Size *= pow(1024, 2);
   else if($MultipSize == "K") $Size *= 1024;
 
   return $Size;
}
if ($_SESSION['droits'] >= 3)
{
	$id = $_SESSION['idmembre'];
	
	$maxup = ini_get('upload_max_filesize');
	$maxupbyte = return_bytes($maxup);
	
	include CHEMIN_MODELE.'import.php';
	
	$liste = liste_import();
	
	include CHEMIN_VUE.'import3.php';	
}