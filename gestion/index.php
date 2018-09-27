<?php
include 'global/config.php';
ob_start();

if (!empty($_GET['module'])) 
{
	$module = dirname(__FILE__).'/modules/'.$_GET['module'].'/';	
	$action = (!empty($_GET['action'])) ? $_GET['action'].'.php' : 'index.php';	
	if (is_file($module.$action)) 
	{
		include $module.$action;
	} 
	else 
	{
		include 'modules/accueil/accueil.php';
	}
} 
else 
{
	include 'modules/accueil/accueil.php';
}
$contenu = ob_get_clean();

include 'global/hdp.php';

echo $contenu;

include 'global/bdp.php';
	