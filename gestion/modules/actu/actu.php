<?php
$titre = 'Gestion actualités';
$description = 'Gestion des actualités';
$script = '<script type="text/javascript" src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>
<script type="text/javascript" src="../dist/js/jquery-auto.js" defer></script>
<script type="text/javascript" src="dist/js/ckeditor/ckeditor.js" defer></script>
<script type="text/javascript" src="dist/js/actu.js" defer></script>';
$css = '';

if(isset($rjson_site['observatoire']))
{
	foreach($rjson_site['observatoire'] as $n)
	{
		$discipline[] = array("disc"=>$n['nom'],"var"=>$n['nomvar']);
	}
}
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
$maxup = ini_get('upload_max_filesize');
$maxupbyte = return_bytes($maxup);
include CHEMIN_VUE.'actu.php';
