<?php
include '../global/configbase.php';

$module = empty($module) ? !empty($_GET['module']) ? $_GET['module'] : 'index' : $module;
define('CHEMIN_VUE', 'modules/'.$module.'/vues/');
define('CHEMIN_MODELE', 'modeles/');
define('CHEMIN_LIB', 'lib/');

session_start();

ini_set('magic_quotes_runtime', 0);

if (1 == get_magic_quotes_gpc())
{
	function remove_magic_quotes_gpc(&$value) {
	
		$value = stripslashes($value);
	}
	array_walk_recursive($_GET, 'remove_magic_quotes_gpc');
	array_walk_recursive($_POST, 'remove_magic_quotes_gpc');
	array_walk_recursive($_COOKIE, 'remove_magic_quotes_gpc');
}
$json_site = file_get_contents('../json/site.json');
$rjson_site = json_decode($json_site, true);
$menubiblio = $rjson_site['biblio'];
$menuactu = $rjson_site['actu'];

include CHEMIN_LIB.'pdo2.php';