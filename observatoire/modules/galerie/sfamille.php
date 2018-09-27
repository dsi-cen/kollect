<?php 
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>
<script src="../dist/js/masonry.js" defer></script>';
$css = '';

if(isset($_GET['id']))
{
	include CHEMIN_MODELE.'galerie.php';
	
	$sfam = htmlspecialchars($_GET['id']);
	
	$sfamille = recherche_sous_famille($sfam,$nomvar);

	$listerang = rechercher_rang($nomvar);
	foreach($listerang as $n)
	{
		if($n['idrang'] == 5) { $tstribu = 'oui'; }
		if($n['idrang'] == 6) { $ttribu = 'oui'; }
	}
	$stribu = (isset($tstribu)) ? 'oui' : 'non';
	$tribu = (isset($ttribu)) ? 'oui' : 'non';
		
	if(isset($_GET['idobser'])) 
	{
		$idobser = htmlspecialchars($_GET['idobser']);
		$observateur = cherche_observateur($idobser);
		
		$titre = $observateur['prenom'].' '.$observateur['nom'].' - Photo '.$sfamille['sousfamille'];
		$description = 'Photos de '.$sfamille['sousfamille'].' de '.$observateur['prenom'].' '.$observateur['nom'].' du site '.$rjson_site['titre'];		
	}
	else
	{
		$idobser = null;
		$titre = 'Galerie Photo '.$sfamille['sousfamille'];
		$description = 'Galerie Photo des '.$sfamille['sousfamille'].' du site '.$rjson_site['titre'];
	}
	
	$liste = photo_sousfamille_espece($nomvar,$stribu,$tribu,$sfam,$idobser);
	
	include CHEMIN_VUE.'sfamille.php';
}
?>