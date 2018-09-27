<?php
if(isset($_SESSION['prenom']) && isset($_SESSION['nom']))
{
	$titre = 'Consultation';
	$description = 'Consultation sur le site '.$rjson_site['titre'];
	$script = '<script src="../dist/js/jquery.js" defer></script>
	<script src="../dist/js/bootstrap.min.js" defer></script>
	<script src="../dist/js/jquery-saisie.js" defer></script>
	<script src="../dist/js/leafletpj4.js"></script>
	<script src="../dist/js/leaflet.draw.js" defer></script>
	<script src="../dist/js/popup-image.js" defer></script>
	<script type="text/javascript" src="../dist/js/jquery.dataTables.min.js" defer></script>
	<script type="text/javascript" src="../dist/js/datatables/dataTables.scroller.min.js" defer></script>
	<script type="text/javascript" src="../dist/js/datatables/dataTables.buttons.min.js" defer></script>
	<script type="text/javascript" src="../dist/js/datatables/jszip.min.js" defer></script>
	<script type="text/javascript" src="../dist/js/datatables/buttons.html5.min.js" defer></script>
	<script type="text/javascript" src="../dist/js/datatables/buttons.colVis.min.js" defer></script>	
	<script src="../dist/js/consultobserva.js?'.filemtime('../dist/js/consultobserva.js').'" defer></script>';
	$css = '<link rel="stylesheet" href="../dist/css/jquery-ui.css" />
	<link rel="stylesheet" href="../dist/css/leaflet.css" />
	<link rel="stylesheet" href="../dist/css/leaflet.draw.css" />
	<link rel="stylesheet" type="text/css" href="../dist/css/dataTables.bootstrap4.css">
	<link rel="stylesheet" type="text/css" href="../dist/css/buttons.bootstrap4.min.css">
	<link rel="stylesheet" href="../dist/css/popup.css" type="text/css">';
	//<script src="../dist/js/consultobserva.js?'.filemtime('../dist/js/consultobserva.js').'" defer></script><script src="../src/js/consultobserva.js" defer></script>
	include CHEMIN_MODELE.'consultation.php';

	if(!isset($_SESSION['virtobs']))
	{
		$tmpobservateur = chercheobmembre($_SESSION['idmembre']);
		$idobser = $tmpobservateur['idobser'];
	}
	else
	{
		$idobser = $_SESSION['idmembre'];			
	}
	
	if($idobser != false)
	{
		$observateur = (!isset($_SESSION['virtobs'])) ? $tmpobservateur['nom'].' '.$tmpobservateur['prenom'] : $_SESSION['nom'].' '.$_SESSION['prenom'];
		$type = recherche_typeobs($idobser);
		$nbtotal = 0;
		foreach($type as $n)
		{
			$nbtotal += $n['nb'];
			if($n['floutage'] == 0)
			{
				$nbnonflou = $n['nb'];
			}
		}
		if($nbtotal >= 100)
		{
			if(isset($nbnonflou))
			{
				$caldroit = round(($nbnonflou / $nbtotal) * 100);
				$voir = ($caldroit >= 85) ? 'oui' : 'non';
				$perso = (isset($_GET['perso']) && $_GET['perso'] == 'oui') ? 'oui' : 'non';
				$voiradmin = (isset($_SESSION['droits']) && $_SESSION['droits'] >= 1) ? 'oui' : 'non';
			}
			else
			{
				$voir = 'non';
				$perso = 'non';
			}
		}
		elseif($nbtotal >= 1 and $nbtotal < 100)
		{
			$voir = 'non';
			$perso = 'oui';
			$voiradmin = (isset($_SESSION['droits']) && $_SESSION['droits'] >= 1) ? 'oui' : 'non';
		}
		else
		{
			$voir = 'non';
			$perso = 'non';
			$voiradmin = 'non';
		}
		$droit = (isset($_SESSION['droits']) && ($_SESSION['droits'] == 1 || $_SESSION['droits'] >= 3)&& $voir == 'oui' && !isset($_SESSION['virtobs'])) ? 'oui' : 'non';
		$voir = ($voir == 'oui' || $voiradmin == 'oui') ? 'oui' : 'non';
	}
	else
	{
		$voir = 'non';
		$pasobs = 'oui';	
	}
	$json_emprise = file_get_contents('../emprise/emprise.json');
	$rjson_emprise = json_decode($json_emprise, true);

	$dep = ($rjson_emprise['emprise'] == 'fr' || $rjson_emprise['contour2'] == 'oui' ) ? 'oui' : 'non';
	$etude = etude();	
	$org = organisme();
	$habitat = habitat();	
	$statut = statut($nomvar);
	$choixmort = (isset($rjson_obser['saisie']['mort']) && $rjson_obser['saisie']['mort'] != null) ? 'oui' : 'non';	
	$stade = (isset($rjson_obser['saisie']['stade'])) ? $rjson_obser['saisie']['stade'] : null;
	$methode = (isset($rjson_obser['saisie']['methode'])) ? $rjson_obser['saisie']['methode'] : null;
	$collecte = (isset($rjson_obser['saisie']['collecte'])) ? $rjson_obser['saisie']['collecte'] : null;
	$statbio = (isset($rjson_obser['saisie']['statutbio'])) ? $rjson_obser['saisie']['statutbio'] : null;
	$protocole = (isset($rjson_obser['saisie']['protocole'])) ? $rjson_obser['saisie']['protocole'] : null;
	
	foreach($statut as $n)
	{
		$lib = (!empty($n['article'])) ? $n['intitule'].' - '.$n['article'] : $n['intitule'];
		$tabstat[] = ['id'=>$n['cdprotect'],'lib'=>$lib,'type'=>$n['type']]; 	
	}
	
	if(isset($tabstat)) { $legstat = 'Statuts'; }
	if(isset($tabstat) && isset($rjson_obser['indice'])) { $legstat = 'Statuts et indices'; }
	if(!isset($tabstat) && isset($rjson_obser['indice'])) { $legstat = 'Indices'; }
	
	$couche = (!empty($_SESSION['couche'])) ? $_SESSION['couche'] : $couche = (isset($rjson_emprise['couche'])) ? $rjson_emprise['couche'] : 'osm';
	
	include CHEMIN_VUE.'consultation.php';
}
else
{
	header('location:../index.php?module=connexion&action=connexion&s=c');
}