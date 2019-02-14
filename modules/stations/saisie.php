<?php
if(isset($_SESSION['prenom']) && isset($_SESSION['nom']))
{
	$json_m = file_get_contents('json/maintenance.json');
	$maintenance = json_decode($json_m, true);
	
	if($maintenance['etat'] == 'Production')
	{
		$titre = 'Gestion des stations';
		$description = 'Gestion des stations.';
		$script = '<script src="dist/js/jquery.js" defer></script>
		<script src="dist/js/bootstrap.min.js" defer></script>
		<script src="dist/js/jquery-saisie.js" defer></script>
		<script src="dist/js/leafletpj4.js"></script>
		<script src="dist/js/leaflet.draw.js" defer></script>
		<script src="dist/js/jquery.cropit.js" defer></script>
		<script src="dist/js/stations.js?'.filemtime('dist/js/stations.js').'" defer></script>
		<script src="dist/js/bootstrap-toggle.min.js" defer></script>';
		//<script src="dist/js/saisie.js?'.filemtime('dist/js/saisie.js').'" defer></script><script src="src/js/saisie.js" defer></script>
		$css = '<link rel="stylesheet" href="dist/css/leaflet.css" />
		<link rel="stylesheet" href="dist/css/leaflet.draw.css" />
		<link rel="stylesheet" href="dist/css/jquery-ui.css" />
		<link rel="stylesheet" href="dist/css/bootstrap-toggle.min.css" />';
		$sansheader = 'oui';
		$pasdebdp = 'oui';

		$json_emprise = file_get_contents('emprise/emprise.json');
		$rjson_emprise = json_decode($json_emprise, true);
		$dep = ($rjson_emprise['emprise'] == 'fr' || $rjson_emprise['contour2'] == 'oui') ? 'oui' : 'non';
		$biogeo = $rjson_emprise['biogeo'];
		$utm = ($rjson_emprise['utm'] == 'oui') ? 'oui' : 'non';
		$dist = ($rjson_emprise['proche'] != '') ? $rjson_emprise['proche'] : 'non'; 
		include CHEMIN_MODELE.'stations.php';
		$idm = $_SESSION['idmembre'];
		$nom = $_SESSION['nom'];
		$prenom = $_SESSION['prenom'];
		if(!isset($_SESSION['virtobs']))
		{
			$cherchereobseridm = rechercheobservateurid($idm);
			$idobser = ($cherchereobseridm['idobser'] != '') ? $cherchereobseridm['idobser'] : 0;
		}
		else
		{
			$idobser = $_SESSION['idmembre'];
		}
		$obser = (!empty($_SESSION['obser'])) ? $_SESSION['obser'] : 'aucun';
		$flou = (!empty($_SESSION['flou'])) ? $_SESSION['flou'] : 0;
		$couche = (!empty($_SESSION['couche'])) ? $_SESSION['couche'] : $couche = (isset($rjson_emprise['couche'])) ? $rjson_emprise['couche'] : 'osm';
		// $typedon = (!empty($_SESSION['typedon'])) ? $_SESSION['typedon'] : 'Pr';
		if(isset($_SESSION['idfiche']))
		{
			$getidfiche = $_SESSION['idfiche'];
			unset($_SESSION['idfiche']);
		}
		else
		{
			$getidfiche = '';
		}	
		$datej = date('Y-m-d');
		$habitat = habitat();
        $org = organisme();
        $precision = liste_precision();
        $typestation = liste_typestation();
        $typemare = liste_typemare();
        $environnement = liste_environnement();
   
        $menaces = liste_menaces();
       
        $eaulibre = eaulibre();
       
        $vegaquatique = liste_vegaquatique();
        $vegsemiaquatique = liste_vegsemiaquatique();
        $vegrivulaire = liste_vegrivulaire();
        $typeexutoire = liste_typeexutoire();
        $taillemare = liste_taillemare();
        $couleureau = liste_couleureau();
        $naturefond = liste_naturefond();
        $recouvrberge = liste_recouvrberge();
        $profondeureau = liste_profondeureau();
        $alimeau = liste_alimeau();

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
		
		include CHEMIN_VUE.'saisie.php';
	}
	else
	{
		header('location:index.php?module=maintenance&action=maintenance');
	}
}
else
{
	header('location:index.php?module=connexion&action=connexion&s=o');
}