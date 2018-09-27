<?php
$description = '';
$script = '<script src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>
<script src="../dist/js/highcharts.js" defer></script>
<script src="../dist/js/modules/map.js" defer></script>
<script src="../dist/js/modules/exportingoff.js" defer></script>
<script src="../dist/js/popup-image.js" defer></script>
<script src="../dist/js/leafletpj4.js" defer></script>
<script src="../dist/js/ficheg.js" defer></script>';
$css = '<link rel="stylesheet" href="../dist/css/popup.css" type="text/css">
<link rel="stylesheet" href="../dist/css/leaflet.css" />';
$menulight = 'oui';

if(isset($_GET['id'])) 
{
	include CHEMIN_MODELE.'ficheg.php';
	$id = htmlspecialchars($_GET['id']);
	//$listerang = rechercher_rang($nomvar);
	
	$taxon = recherche_fichegenre($id,$nomvar);
	$nom = $taxon['nom'];
	$inventeur = $taxon['auteur'];
	$famille = $taxon['famille'];
	
	$titre = $nom;
	
	$espece = recherche_ranginfgenre($id,$nomvar);
	$nbes = count($espece);
	if($nbes >= 1)
	{
		foreach($espece as $n)
		{
			$tmpsensible[] = $n['sensible'];
		}
		$sensible = max($tmpsensible);
		$complexe = complexe($id,$nomvar);
	}
	else
	{
		$sensible = '';
	}
		
	//affichage latin ou non
	$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';	
	if($latin == 'oui')
	{
		$afflatin = 'oui';
	}
	elseif($rjson_obser['latin'] == 'oui' && ($latin == 'defaut' || $latin == ''))
	{
		$afflatin = 'oui';
	}
	elseif($latin == 'non')
	{
		$afflatin = 'non';
	}
	elseif($rjson_obser['latin'] == 'non' && ($latin == 'defaut' || $latin == ''))
	{
		$afflatin = 'non';
	}
	
	//photo
	$photo = photo($id);
	$ouiphoto = count($photo);
	
	//carto et sensible
	if($sensible != '')
	{
		$infosensible = 'Au moins une espèce est jugée "sensible". Par conséquent il est possible de voir les données seulement à l\'échelle';
		if($sensible == 1) { $infosensible .= ' communale, maille 10 x 10, départementale.'; }
		if($sensible == 2) { $infosensible .= ' des mailles 10 x 10 et départementale.'; }
		if($sensible == 3) { $infosensible .= ' départementale.'; }
	}
	$anneeencours = date('Y');
	$json_emprise = file_get_contents('../emprise/emprise.json');
	$emprise = json_decode($json_emprise, true);
	
	$choixcarte = (isset($rjson_site['fiche']['cartefiche'])) ? $rjson_site['fiche']['cartefiche'] : 'commune';
	//$titrecarte = ($emprise['emprise'] != 'fr') ? 'Répartition communale' : 'Répartition départementale';
	if($choixcarte == 'commune') 
	{
		if($emprise['emprise'] != 'fr')
		{
			if($sensible == '' || $sensible == 0)
			{
				$titrecarte = 'Répartition communale';
				$cartemaille5 = ($emprise['lambert5'] == 'oui') ? 'Carte Lambert93 5x5 km' : 'non';
				$cartecom = 'Carte communale';
				$cartemaille = ($emprise['utm'] == 'oui') ? 'Carte maille UTM 10x10 km' : 'Carte Lambert93 10x10 km';
			}
			if($sensible == 1)
			{
				$titrecarte = 'Répartition communale';
				$cartemaille5 = 'non';
				$cartecom = 'Carte communale';
				$cartemaille = ($emprise['utm'] == 'oui') ? 'Carte maille UTM 10x10 km' : 'Carte Lambert93 10x10 km';
			}
			elseif($sensible == 2)
			{
				$choixcarte = 'maille';
				$titrecarte = ($emprise['utm'] == 'oui') ? 'Répartition par maille UTM' : 'Répartition par maille 10 x 10';
				$cartemaille = ($emprise['utm'] == 'oui') ? 'Carte maille UTM 10x10 km' : 'Carte Lambert93 10x10 km';
				$cartemaille5 = 'non';
				$cartecom = 'non';
			}					
		}
		else
		{
			$titrecarte = 'Répartition départementale';
			$cartecom = 'Carte départementale';
			$cartemaille = ($emprise['utm'] == 'oui') ? 'Carte maille UTM 10x10 km' : 'Carte Lambert93 10x10 km';
			$cartemaille5 = 'non';
			if($sensible == 2)
			{
				$cartemaille = 'non';
			}
		}		
	}	
	else
	{
		$titrecarte = ($emprise['utm'] == 'oui') ? 'Répartition par maille UTM' : 'Répartition par maille 10 x 10';
		if($emprise['emprise'] != 'fr')
		{
			if($sensible == '' || $sensible == 0)
			{
				$cartecom = 'Carte communale';
				$cartemaille = ($emprise['utm'] == 'oui') ? 'Carte maille UTM 10x10 km' : 'Carte Lambert93 10x10 km';
				$cartemaille5 = ($emprise['lambert5'] == 'oui') ? 'Carte Lambert93 5x5 km' : 'non';
			}
			if($sensible == 1)
			{
				$cartemaille5 = 'non';
				$cartemaille = ($emprise['utm'] == 'oui') ? 'Carte maille UTM 10x10 km' : 'Carte Lambert93 10x10 km';
				$cartecom = 'Carte communale';
			}
			elseif($sensible == 2)
			{
				$cartemaille = ($emprise['utm'] == 'oui') ? 'Carte maille UTM 10x10 km' : 'Carte Lambert93 10x10 km';
				$cartemaille5 = 'non';
				$cartecom = 'non';
			}
		}
		else
		{
			$cartecom = 'Carte départementale';
			$cartemaille = ($emprise['utm'] == 'oui') ? 'Carte maille UTM 10x10 km' : 'Carte Lambert93 10x10 km';
			$cartemaille5 = 'non';
			if($sensible == 2)
			{
				$choixcarte == 'commune';
				$cartemaille = 'non';
			}
		}		
	}	
	//legende carto
	if (isset($rjson_site['fiche']['classefiche'])) 
	{
		foreach($rjson_site['fiche']['classefiche'] as $n)
		{
			if($n['classe'] == 'classe1')
			{
				$legende[] = array('couleur'=>$n['couleur'],'label'=> 'Observation en '.$anneeencours);
			}
			else
			{
				$legende[] = array('couleur'=>$n['couleur'],'label'=> $n['label']);
			}			
		}
	}
			
	//info nombre et indice
	$nombre = nombre_especegenre($id,$nomvar);
	$nbobs = $nombre['nb'];
	$nbcom = $nombre['nbcom'];
	$nbmaille = $nombre['nbmaille'];
	$mailletotal = $emprise['nbmaille'];
	$couverture = round($nbmaille/$mailletotal*100,2);
	$nbgenresp = nombre_genresp($id);
	include CHEMIN_VUE.'ficheg.php';
}
