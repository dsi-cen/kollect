<?php
$description = '';
$script = '<script src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>
<script src="../dist/js/highcharts.js" defer></script>
<script src="../dist/js/modules/map.js" defer></script>
<script src="../dist/js/modules/exportingoff.js" defer></script>
<script src="../dist/js/popup-image.js" defer></script>
<script src="../dist/js/leafletpj4.js" defer></script>
<script src="../dist/js/jquery.glossarize.js" defer></script>
<script src="../dist/js/leafletFullScreen.min.js" defer></script>
<script src="../dist/js/leafletMarkerCluster.js" defer></script>
<script src="../dist/js/bootstrap-slider.min.js" defer></script>
<script src="../dist/js/fiche.js?'.filemtime('../dist/js/fiche.js').'" defer></script>';
$css = '<link rel="stylesheet" href="../dist/css/popup.css" type="text/css">
<link rel="stylesheet" href="../dist/css/leaflet.css" />
<link rel="stylesheet" href="../dist/css/leafletFullScreen.css" />
<link rel="stylesheet" href="../dist/css/leafletMarkerCluster.css" />
<link rel="stylesheet" href="../dist/css/bootstrap-slider.min.css" />';
//<script src="../dist/js/fiche.js?'.filemtime('../dist/js/fiche.js').'" defer></script><script src="../src/js/fiche.js" defer></script>
if(isset($_GET['id'])) 
{
	$droit = (isset($_SESSION['droits']) && $_SESSION['droits'] >= 2) ? 'oui' : 'non';
	include CHEMIN_MODELE.'fiche.php';
	$id = htmlspecialchars($_GET['id']);
	$sytema = (isset($rjson_obser['systematique'])) ? 'oui' : 'non';
	
	$taxon = recherche_fiche($id,$nomvar,$sytema);
	if(!empty($taxon['nom']))
	{
		$nom = $taxon['nom'];
		$nomfr = $taxon['nomvern'];
		$inventeur = $taxon['auteur'];
		$famille = $taxon['famille'];
		$genre = $taxon['genre'];
		$espece = $taxon['espece'];
		$rang =  $taxon['rang'];
		//rang sup.
		if($rang == 'ES')
		{
			$cdnomg = $taxon['cdtaxsup'];		
		}
		elseif($rang == 'SSES')
		{
			$cdnomes = $taxon['cdtaxsup'];
			$cdnomg = recherche_sup($cdnomes,$nomvar);
			$taxones = recherche_fiche($cdnomes,$nomvar,$sytema);
			$nomes = $taxones['nom'];
			$rangssses = 'non';
		}
		$nbgenre = nbgenre($cdnomg,$nomvar);
		$listerang = rechercher_rang($nomvar);
		foreach($listerang as $n)
		{
			if($n['idrang'] == 1) // sous espèce
			{
				if($rang == 'ES')
				{
					$soussp = recherche_ranginf($id,$nomvar);
					$nbsses = count($soussp);
					$rangssses = ($nbsses >= 1) ? 'oui' : 'non';
				}
			}
			if($n['idrang'] == 7) { $cherchesfamille = 'oui'; } //sous famille
			if($n['idrang'] == 5) { $stribu = 'oui'; }
			if($n['idrang'] == 6) { $tribu = 'oui'; }
		}	
		if(isset($cherchesfamille))
		{
			if(isset($tribu) && isset($stribu))
			{
				$sfamille = recherche_sfamille_sfst($cdnomg,$nomvar);
			}
			elseif(isset($tribu) && !isset($stribu))
			{
				$sfamille = recherche_sfamille_sft($cdnomg,$nomvar);
			}
			elseif(!isset($tribu) && !isset($stribu))
			{
				$sfamille = recherche_sfamille_sf($cdnomg,$nomvar);
			}
		}		
		
		//syno
		$synonyme = recherche_syno($id,$nomvar);
		$nbsyno = count($synonyme);	
		
		//affichage latin ou non
		$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
		//$titre = ($rjson_obser['latin'] == 'oui') ? $nom : $nomfr;
		$titre = ($rjson_obser['latin'] == 'oui') ? $nom : (empty($nomfr)) ? $nom : $nomfr ;
		$description = ($rjson_obser['latin'] == 'oui') ? 'Fiche de '.$nom.' '.$rjson_site['ad2'].' '.$rjson_site['lieu'] : (empty($nomfr)) ? 'Fiche de '.$nom.' '.$rjson_site['ad2'].' '.$rjson_site['lieu'] : 'Fiche de '.$nomfr.' '.$rjson_site['ad2'].' '.$rjson_site['lieu'];
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
		if($afflatin == 'oui')
		{
			$nomstitre = '<i>'.$nom.'</i>'; 
		}
		else
		{
			$nomstitre = (!empty($nomfr)) ? $nomfr : '<i>'.$nom.'</i>';
		}
		
		//photo
		$photo = photo($id);
		$ouiphoto = count($photo);
		
		//similaire
		$simi = recherche_simi($id,$nomvar);
		
		//systematique gen1 et gen2
		if($sytema == 'oui')
		{
			if(isset($rjson_obser['gen1']))
			{
				$gen = recherche_gen($id,$nomvar);
				$gen1 = ($gen['gen1'] != '') ? ''.$rjson_obser['gen1'].' : '.$gen['gen1'] : '';
				if(isset($rjson_obser['gen2']))
				{
					$gen2 = ($gen['gen2'] != '') ? ''.$rjson_obser['gen2'].' : '.$gen['gen2'] : '';
				}
			}
		}	
		//carto et sensible
		$sensible = $taxon['sensible'];
		if($sensible != '')
		{
			$infosensible = 'Cette espèce est jugée "sensible". Par conséquent il est possible de voir les données seulement à l\'échelle';
			if($sensible == 1) { $infosensible .= ' communale, maille 10 x 10, départementale. <a href="'.$taxon['url'].'"><i class="fa fa-link text-primary"></i></a>'; }
			if($sensible == 2) { $infosensible .= ' des mailles 10 x 10 et départementale. <a href="'.$taxon['url'].'"><i class="fa fa-link text-primary"></i></a>'; }
			if($sensible == 3) { $infosensible .= ' départementale. <a href="'.$taxon['url'].'"><i class="fa fa-link text-primary"></i></a>'; }
		}
		$anneeencours = date('Y');
		$json_emprise = file_get_contents('../emprise/emprise.json');
		$emprise = json_decode($json_emprise, true);
		$ign = (isset($emprise['cleign']) && !empty($emprise['cleign'])) ? $emprise['cleign'] : 'non';
		
		$choixcarte = (isset($rjson_site['fiche']['cartefiche'])) ? $rjson_site['fiche']['cartefiche'] : 'commune';
		if($choixcarte == 'commune') 
		{
			if($emprise['emprise'] != 'fr')
			{
				if($sensible == '' || $sensible == 0 || $droit == 'oui')
				{
					$titrecarte = 'Répartition communale - ';
					$cartemaille5 = ($emprise['lambert5'] == 'oui') ? 'Carte Lambert93 5x5 km' : 'non';
					$cartecom = 'Carte communale';
					$cartemaille = ($emprise['utm'] == 'oui') ? 'Carte maille UTM 10x10 km' : 'Carte Lambert93 10x10 km';
				}
				elseif($sensible == 1)
				{
					$titrecarte = 'Répartition communale - ';
					$cartemaille5 = 'non';
					$cartecom = 'Carte communale';
					$cartemaille = ($emprise['utm'] == 'oui') ? 'Carte maille UTM 10x10 km' : 'Carte Lambert93 10x10 km';
				}
				elseif($sensible == 2)
				{
					$choixcarte = 'maille';
					$titrecarte = ($emprise['utm'] == 'oui') ? 'Répartition par maille UTM - ' : 'Répartition par maille 10 x 10 - ';
					$cartemaille = ($emprise['utm'] == 'oui') ? 'Carte maille UTM 10x10 km' : 'Carte Lambert93 10x10 km';
					$cartemaille5 = 'non';
					$cartecom = 'non';
				}					
			}
			else
			{
				$titrecarte = 'Répartition départementale - ';
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
			$titrecarte = ($emprise['utm'] == 'oui') ? 'Répartition par maille UTM - ' : 'Répartition par maille 10 x 10 - ';
			if($emprise['emprise'] != 'fr')
			{
				if($sensible == '' || $sensible == 0 || $droit == 'oui')
				{
					$cartecom = 'Carte communale';
					$cartemaille = ($emprise['utm'] == 'oui') ? 'Carte maille UTM 10x10 km' : 'Carte Lambert93 10x10 km';
					$cartemaille5 = ($emprise['lambert5'] == 'oui') ? 'Carte Lambert93 5x5 km' : 'non';
				}
				elseif($sensible == 1)
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
		if(isset($rjson_site['fiche']['classefiche'])) 
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
		if($emprise['lambert5'] == 'oui')
		{
			$nombre = nombre_espece5($id,$nomvar,$rang);
			$nbmaille5 = ($nombre['nbmaille5'] > 1) ? '<span class="h5">'.$nombre['nbmaille5'].'</span><br /><span>mailles Lambert93 5 km</span>' : '<span class="h5">'.$nombre['nbmaille5'].'</span><br /><span>maille Lambert93 10 km</span>'; 
			$nb5 = $nombre['nbmaille5'];
			$mailletotal5 = $emprise['nbmaille5'];
			$couverture5 = round($nb5/$mailletotal5 * 100,2);
		}
		else
		{
			$nombre = ($emprise['emprise'] != 'fr') ? nombre_espece($id,$nomvar,$rang) : nombre_especefr($id,$nomvar,$rang);
		}
		$nbobs = ($nombre['nb'] > 1) ? '<span class="h5">'.$nombre['nb'].'</span><br /><span>données</span>' : '<span class="h5">'.$nombre['nb'].'</span><br /><span>donnée</span>';
		$nbcom = ($nombre['nbcom'] > 1) ? '<span class="h5">'.$nombre['nbcom'].'</span><br /><span>communes</span>' : '<span class="h5">'.$nombre['nbcom'].'</span><br /><span>commune</span>';
		$nbmaille10 = ($nombre['nbmaille10'] > 1) ? '<span class="h5">'.$nombre['nbmaille10'].'</span><br /><span>mailles Lambert93 10 km</span>' : '<span class="h5">'.$nombre['nbmaille10'].'</span><br /><span>maille Lambert93 10 km</span>';
		$nb10 = $nombre['nbmaille10'];
		$mailletotal10 = $emprise['nbmaille'];
		$couverture10 = round($nb10/$mailletotal10 * 100,2);
				
		if(($rang == 'ES' || $rang == 'SSES') && isset($rjson_obser['indice']))
		{
			$indice = recherche_indice($id);
			if($indice == 'E') { $libindice = 'Exceptionnelle'; }
			elseif($indice == 'TR') { $libindice = 'Espèce très rare'; }
			elseif($indice == 'R') { $libindice = 'Espèce rare'; }
			elseif($indice == 'AR') { $libindice = 'Espèce assez rare'; }
			elseif($indice == 'PC') { $libindice = 'Espèce peu commune'; }
			elseif($indice == 'AC') { $libindice = 'Espèce assez commune'; }
			elseif($indice == 'C') { $libindice = 'Espèce commune'; }
			elseif($indice == 'CC') { $libindice = 'Espèce très commune'; }
			elseif($indice == 'D?') { $libindice = 'Présumé disparu ?'; }
			elseif($indice == '') { $libindice = 'Non calculé'; }	
		}
		else
		{
			$indice = 'NC';
			$libindice = 'Non calculé';
		}
		//oiseaux nicheur
		$aves = (isset($rjson_obser['saisie']['aves']) && $rjson_obser['saisie']['aves'] == 'oui') ? 'oui' : 'non';
		
		//suivante - precedente
		if($sytema == 'oui' && $taxon['ordre'] != '')
		{
			$precedent = precedent($taxon['ordre'],$nomvar);
			$nomprecedent = $precedent['nom'];
			$esprecedente = $precedent['cdnom'];
			$suivant = suivant($taxon['ordre'],$nomvar);
			$nomsuivant = $suivant['nom'];
			$essuivante = $suivant['cdnom'];
		}
		else
		{
			if($afflatin == 'oui') { $suivprec = suiv_prec($nom,$nomvar); }
			else { $suivprec = (empty($nomfr)) ? suiv_prec($nom,$nomvar) : suiv_precV($nomfr,$nomvar); }
			if(isset($suivprec[0]) && $suivprec[0]['sens'] == 'av') { $nomprecedent = $suivprec[0]['nom']; $esprecedente = $suivprec[0]['cdnom']; }
			if(isset($suivprec[1]) && $suivprec[1]['sens'] == 'av') { $nomprecedent = $suivprec[1]['nom']; $esprecedente = $suivprec[1]['cdnom']; }
			if(isset($suivprec[1]) && $suivprec[1]['sens'] == 'ap') { $nomsuivant = $suivprec[1]['nom']; $essuivante = $suivprec[1]['cdnom']; }
			if(isset($suivprec[0]) && $suivprec[0]['sens'] == 'ap') { $nomsuivant = $suivprec[0]['nom']; $essuivante = $suivprec[0]['cdnom']; }
		}
				
		//habitat
		$habitat = habitat($id);
		
		//statut
		if(isset($rjson_obser['statut']))
		{
			foreach($rjson_obser['statut'] as $cle => $n)
			{
				foreach($n as $a)
				{
					$tabstatut[] = $a;				
				}			
			}
			$cdprotect = implode("','", $tabstatut);
			$cdprotect = "'".$cdprotect."'";
			$statut = recherche_statut($id,$cdprotect,$nomvar);
			if(!empty($statut))
			{
				$nlrf = 0;
				foreach($statut as $n)
				{
					if($n['type'] == 'DH') { $dh = 'oui'; }
					if($n['type'] == 'PN') { $pn = 'oui'; }
					if($n['type'] == 'PR') { $pr = 'oui'; }
					if($n['type'] == 'PD') { $pd = 'oui'; }
					if($n['type'] == 'Z') { $znieff = 'oui'; }
					if($n['type'] == 'LRM') { $lrm = $n['lr']; $lrep = ($lrm == 'CR*') ? 'CR' : $lrm; }
					if($n['type'] == 'LRE') { $lre = $n['lr']; $lrep = ($lre == 'CR*') ? 'CR' : $lre; }
					if($n['type'] == 'LRF') { $nlrf++;
						$lrf = $n['lr']; $lrfp = ($lrf == 'CR*') ? 'CR' : $lrf;
						$tablrfp[] = $lrfp;
					}
					if($n['type'] == 'LRR') { $lrr = $n['lr']; $lrrp = ($lrr == 'CR*') ? 'CR' : $lrr; }
					if($n['type'] == 'LRD') { $lrd = $n['lr']; $lrdp = ($lrd == 'CR*') ? 'CR' : $lrd; }
					if($n['type'] == 'A') { $typea = $n['lr']; $intypea = $n['intitule'];}
					if($n['type'] == 'I') { $typei = $n['lr']; $intypei = $n['intitule'];}	
				}
			}		
		}
		//lien
		if($nomvar == 'lepido')
		{
			//$lien == 'oui';
			$lepinet = lepinet($id);
		}
		//biblio
		if($rjson_site['biblio'] == 'oui')
		{
			$biblio = biblio($id);			
		}
		
		$url = 'https://'.$_SERVER['HTTP_HOST'].''.$_SERVER['REQUEST_URI'];
		$datejour = date('d/m/Y');
		include CHEMIN_VUE.'fiche.php';
	}
	else
	{
		header('location:index.php?d='.$nomvar.'');
	}	
}