<?php 
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>
<script src="../dist/js/masonry.js" defer></script>';
$css = '';

if(isset($_GET['id']))
{
	include CHEMIN_MODELE.'galerie.php';
	
	$fam = htmlspecialchars($_GET['id']);
	
	$famille = recherche_famille($fam,$nomvar);

	$listerang = rechercher_rang($nomvar);
	foreach($listerang as $n)
	{
		if($n['idrang'] == 5) { $tstribu = 'oui'; }
		if($n['idrang'] == 6) { $ttribu = 'oui'; }
		if($n['idrang'] == 7) { $tsfam = 'oui'; }
	}
	$stribu = (isset($tstribu)) ? 'oui' : 'non';
	$tribu = (isset($ttribu)) ? 'oui' : 'non';
	$sfam = (isset($tsfam)) ? 'oui' : 'non';
	
	if(isset($_GET['idobser'])) 
	{
		$idobser = htmlspecialchars($_GET['idobser']);
		$observateur = cherche_observateur($idobser);
							
		$titre = $observateur['prenom'].' '.$observateur['nom'].' - Photo '.$famille['famille'];
		$description = 'Photos de '.$famille['famille'].' de '.$observateur['prenom'].' '.$observateur['nom'].' du site '.$rjson_site['titre'];
	}
	else
	{
		$idobser = null;
		$titre = 'Galerie Photo '.$famille['famille'];
		$description = 'Galerie Photo des '.$famille['famille'].' du site '.$rjson_site['titre'];
	}
	
	if($sfam == 'oui')
	{
		$listesfam = liste_sousfamille($nomvar,$stribu,$tribu,$fam,$idobser);
		foreach($listesfam as $n)
		{
			if(empty($n['cdnom'])) { $autre = 'oui'; }
			if(!empty($n['cdnom'])) { $oksfam = 'oui'; }				
		}
		if(isset($autre))
		{
			$autre = photo_sousfamille_autre($nomvar,$stribu,$tribu,$fam,$idobser);
		}
		if(isset($oksfam))
		{
			$liste = photo_sousfamille($nomvar,$stribu,$tribu,$fam,$idobser);
		}			
	}
	else
	{
		$autre = photo_sans_sousfamille($nomvar,$fam,$idobser);
	}
	
	include CHEMIN_VUE.'famille.php';
}
?>