<?php 
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

include CHEMIN_MODELE.'galerie.php';

$choixlatin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';

if($choixlatin == 'oui')
{
	$tri = 'nom';
}
elseif($choixlatin == 'non')
{
	$tri = 'nomvern';
}
else
{
	$tri = ($rjson_obser['latin'] == 'oui') ? 'nom' : 'nomvern';
}

if(isset($_GET['idobser'])) 
{
	$idobser = htmlspecialchars($_GET['idobser']);
	$observateur = cherche_observateur($idobser);
	
	$titre = $observateur['prenom'].' '.$observateur['nom'].' - Photo '.$nomd;
    $description = 'Photos de '.$nomd.' de '.$observateur['prenom'].' '.$observateur['nom'].' du site '.$rjson_site['titre'];
}
else
{
	$idobser = null;
	$titre = 'Galerie Photo '.$nomd;
    $description = 'Galerie Photo de '.$nomd.' du site '.$rjson_site['titre'];	
}

$liste = (!isset($observateur)) ? photo_famille($nomvar) : photo_famille_auteur($nomvar,$idobser);
$nbliste = count($liste);
if($nbliste > 0)
{
	$lettre = (!isset($observateur)) ? recherche_photo_lettre($nomvar,$tri) : recherche_photo_lettre_auteur($nomvar,$tri,$idobser);
}

include CHEMIN_VUE.'galerie.php';
?>