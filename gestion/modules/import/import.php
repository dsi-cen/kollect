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
	$maxup = ini_get('upload_max_filesize');
	$maxupbyte = return_bytes($maxup);
	
	$json_emprise = file_get_contents('../emprise/emprise.json');
	$rjson_emprise = json_decode($json_emprise, true);	
	
	include CHEMIN_VUE.'import.php';	
}