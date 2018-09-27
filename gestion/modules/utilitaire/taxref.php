<?php
$titre = 'Taxref';
$description = 'Taxref';
$script = '<script type="text/javascript" src="../dist/js/jquery.js"></script>
<script src="../dist/js/bootstrap.min.js" defer></script>
<script type="text/javascript" src="dist/js/taxref.js" defer></script>';
$css = '';

$filename = '../json/taxref.json';

if(file_exists($filename)) 
{
    $json = file_get_contents($filename);
	$rjson = json_decode($json, true);

	$suiv = $rjson['version'] + 1;

	include CHEMIN_VUE.'taxref.php';
}