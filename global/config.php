<?php
include 'global/configbase.php';
$module = empty($module) ? !empty($_GET['module']) ? $_GET['module'] : 'index' : $module;
define('CHEMIN_VUE', 'modules/'.$module.'/vues/');
define('CHEMIN_MODELE', 'modeles/');
define('CHEMIN_LIB', 'lib/');
include CHEMIN_LIB.'pdo2.php';
session_start();

ini_set('magic_quotes_runtime', 0);

if(1 == get_magic_quotes_gpc())
{
	function remove_magic_quotes_gpc(&$value) {
	
		$value = stripslashes($value);
	}
	array_walk_recursive($_GET, 'remove_magic_quotes_gpc');
	array_walk_recursive($_POST, 'remove_magic_quotes_gpc');
	array_walk_recursive($_COOKIE, 'remove_magic_quotes_gpc');
}
$json_site = file_get_contents('json/site.json');
$rjson_site = json_decode($json_site, true);
$metakey = $rjson_site['metakey'];
if(isset($rjson_site['observatoire']))
{
	foreach($rjson_site['observatoire'] as $n)
	{
		$menuobservatoire[] = array('nom'=>$n['nom'], 'icon'=>$n['icon'], 'var'=>$n['nomvar'], 'couleur'=>$n['couleur']);
		$theme[] = $n['nomvar'];
	}
	$nbobservatoire = count($menuobservatoire);
	$libnbobser = ($nbobservatoire >1) ? 'observatoires' : 'observatoire';
}
else
{
	$nbobservatoire = 0;
}

include CHEMIN_MODELE.'utilisateur.php';
if(isset($_COOKIE['idm']) AND !isset($_SESSION['idmembre']))
{
	$connexion = connexionor($_COOKIE['idm'],$_COOKIE['idd'],$_COOKIE['idp']);
	if($connexion['nom'] != '')
	{
		$_SESSION['prenom'] = $connexion['prenom'];
		$_SESSION['nom'] = $connexion['nom'];
		$_SESSION['droits'] = $_COOKIE['idd'];
		$_SESSION['idmembre'] = $_COOKIE['idm'];
		$_SESSION['latin'] = $connexion['latin'];
		$_SESSION['obser'] = $connexion['obser'];
		$_SESSION['flou'] = $connexion['floutage'];
		$_SESSION['couche'] = $connexion['couche'];
		$_SESSION['typedon'] = $connexion['typedon'];
		$_SESSION['idorg'] = $connexion['org'];
	}
}
if(isset($_SESSION['idmembre']) && !isset($_SESSION['virtobs']))
{
	$nbnotif = notif($_SESSION['idmembre']);
} else { $nbnotif = 0; }
if(!isset($_SESSION['virtuel']))
{
	if(isset($_SESSION['idmembre']))
	{
		$date = date("Y-m-d H:i:s");
		$prenom = $_SESSION['prenom'];
		$nom = $_SESSION['nom'];
		mod_membrec($date,$_SESSION['idmembre']);
	}	
	$ip = $_SERVER['REMOTE_ADDR'];
	$agent = $_SERVER['HTTP_USER_AGENT'];
	$referer = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '' ;
	$uri = $_SERVER['REQUEST_URI'];
	$temp = time();
	$nbip = chercheip($ip);
	$idm = (isset($_SESSION['idmembre'])) ? $_SESSION['idmembre'] : 0;
	if($nbip == 0)
	{
		inserip($ip,$idm,$temp,$agent,$referer,$uri);
	}
	else
	{
		modip($ip,$idm,$temp,$agent,$referer,$uri);
	}
	$cinqmin = time() - (60 * 5);
	deleteip($cinqmin);
}