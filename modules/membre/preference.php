<?php
if (isset($_SESSION['prenom']))
{
	$titre = 'Paramètres de '.$_SESSION['prenom'].'';
	$description = 'Préférences et paramètres de '.$_SESSION['prenom'].' '.$_SESSION['nom'].'.';
	$script = '<script src="dist/js/jquery.js" defer></script>
	<script src="dist/js/bootstrap.min.js" defer></script>
	<script src="dist/js/pref.js?'.filemtime('dist/js/pref.js').'" defer></script>';
	$css = '';
	//<script src="dist/js/pref.js?'.filemtime('dist/js/pref.js').'" defer></script><script src="src/js/pref.js" defer></script>	
	$json_emprise = file_get_contents('emprise/emprise.json');
	$rjson_emprise = json_decode($json_emprise, true);
	
	$idm = $_SESSION['idmembre'];
	$latin = $_SESSION['latin'];
	$obser = (!empty($_SESSION['obser'])) ? $_SESSION['obser'] : 'aucun';
	$flou = (!empty($_SESSION['flou'])) ? $_SESSION['flou'] : 0;
	$typedon = (!empty($_SESSION['typedon'])) ? $_SESSION['typedon'] : 'Pr';
	$couche = (!empty($_SESSION['couche'])) ? $_SESSION['couche'] : $couche = (isset($rjson_emprise['couche'])) ? $rjson_emprise['couche'] : 'osm';
	$favatar = 'photo/avatar/'.$_SESSION['prenom'].''.$_SESSION['idmembre'].'.jpg';
	include CHEMIN_MODELE.'membre.php';
	$membre = cherche_membre($idm);
	$contact = $membre['contact'];
	$mail = $membre['mail'];
	$orga = organisme();
	if(!empty($_SESSION['idorg']))
	{
		$idorg = $_SESSION['idorg'];
	}
	elseif(isset($rjson_site['orga']))
	{
		$idorg = $rjson_site['orga']['id'];
	}
	else
	{
		$idorg = 2;
	}
	
	include CHEMIN_VUE.'preference.php';
}
else
{
	header('location:index.php');
}