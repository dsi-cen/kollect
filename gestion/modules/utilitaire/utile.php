<?php
$titre = 'Divers';
$description = 'Divers';
$scripthaut = '<script type="text/javascript" src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

$filename = '../json/maintenance.json';

if(file_exists($filename)) 
{
    $json = file_get_contents($filename);
	$rjson = json_decode($json, true);
	$etat = $rjson['etat'];
} 
else 
{
    $etat = 'Production';
}


include CHEMIN_VUE.'utile.php';