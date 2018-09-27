<?php
$script = '<script src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>
<script src="../dist/js/leaflet.js" defer></script>
<script src="../dist/js/popup-image.js" defer></script>
<script src="../dist/js/observationobserva.js" defer></script>';
$css = '<link rel="stylesheet" href="../dist/css/leaflet.css" />
<link rel="stylesheet" href="../dist/css/popup.css" type="text/css">';

if(isset($_GET['id'])) 
{
	include CHEMIN_MODELE.'observation.php';
	$id = htmlspecialchars($_GET['id']);

	$taxon = recherche_nom($id,$nomvar);
	if(!empty($taxon['nom']))
	{
		$nom = $taxon['nom'];
		$nomfr = $taxon['nomvern'];
		$inventeur = $taxon['auteur'];
		$sensible = (!empty($taxon['sensible'])) ? $taxon['sensible'] : 'non';
		$rang = $taxon['rang'];
		
		//affichage latin ou non
		$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
		$titre = ($rjson_obser['latin'] == 'oui') ? 'Observations de '.$nom : 'Observations de '.$nomfr;
		$description = ($rjson_obser['latin'] == 'oui') ? 'Liste des observations de '.$nom.' '.$rjson_site['ad2'].' '.$rjson_site['lieu'] : 'Liste des observations de '.$nomfr.' '.$rjson_site['ad2'].' '.$rjson_site['lieu'];
		if($latin == 'oui') { $afflatin = 'oui'; }
		elseif($rjson_obser['latin'] == 'oui' && ($latin == 'defaut' || $latin == '')) { $afflatin = 'oui'; }
		elseif($latin == 'non') { $afflatin = 'non'; }
		elseif($rjson_obser['latin'] == 'non' && ($latin == 'defaut' || $latin == '')) { $afflatin = 'non'; }
		
		$json_emprise = file_get_contents('../emprise/emprise.json');
		$rjson_emprise = json_decode($json_emprise, true);
		$dep = ($rjson_emprise['emprise'] == 'fr' || $rjson_emprise['contour2'] == 'oui' ) ? 'oui' : 'non';
		
		include CHEMIN_VUE.'observation.php';
	}
	else
	{
		header('location:index.php?d='.$nomvar.'');
	}	
}