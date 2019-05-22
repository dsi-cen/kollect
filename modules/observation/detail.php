<?php
$script = '<script src="dist/js/jquery.js" defer></script>
<script src="dist/js/bootstrap.min.js" defer></script>
<script src="dist/js/leafletpj4.js" defer></script>
<script src="dist/js/detail.js?'.filemtime('dist/js/detail.js').'" defer></script>
<script src="dist/js/popup-image.js" defer></script>';
$css = '<link rel="stylesheet" href="dist/css/leaflet.css" />
<link rel="stylesheet" href="dist/css/popup.css" type="text/css">';
//<script src="dist/js/detail.js?'.filemtime('dist/js/detail.js').'" defer></script><script src="src/js/detail.js" defer></script>
include CHEMIN_MODELE.'observation.php';

if(isset($_GET['idobs'])) 
{
	$idobs = htmlspecialchars($_GET['idobs']);
	$titre = 'Observation '.$idobs;
	$description = 'Détail de l\'observation '.$idobs.' sur le site '.$rjson_site['titre'];
	
	$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
	$droit = (isset($_SESSION['droits']) && $_SESSION['droits'] >= 2) ? 'oui' : 'non';
	
	$json_emprise = file_get_contents('emprise/emprise.json');
	$rjson_emprise = json_decode($json_emprise, true);
	$color = $rjson_emprise['stylecontour']['color'];
	$weight = $rjson_emprise['stylecontour']['weight'];
	$opacity = $rjson_emprise['stylecontour']['opacity'];
	
	if(isset($_GET['idnotif']) && !isset($_GET['vali'])) { supnotif($idobs,$_SESSION['idmembre']); }
	$rvali = (isset($_GET['vali'])) ? 'oui' : 'non';

	$biblio = ($rjson_site['biblio'] == 'oui') ? 'oui' : 'non';
	
	$obs = recherche_obs($idobs,$biblio);
	$obsexist = (!empty($obs['cdnom'])) ? 'oui' : 'non';
	if($obsexist == 'non')
	{
		$obs = recherche_obs_inv($idobs,$biblio);
		$obsexist = (!empty($obs['cdnom'])) ? 'oui' : 'non';
	}
	if($obsexist == 'oui')
	{
		$idm = $obs['idm'];
		$dateobs = ($obs['datefr'] == $obs['datefr2']) ? $obs['datefr'] : 'Entre le '.$obs['datefr'].' et le '.$obs['datefr2'];
		if(isset($_SESSION['idmembre']))
		{
			$validateur = cherche_vali($_SESSION['idmembre'],$obs['observa']);
			if($validateur !== false)
			{
				supnotif($idobs,$_SESSION['idmembre']);
				$rvali = 'non';				
			}
			if($idm != $obs['idmor'] && $obs['idmor'] != '')
			{
				$idmor = recherche_membre($obs['idmor']);
			}
			if($idm == $_SESSION['idmembre']) { $droit = 'oui'; }
		}
		
		foreach($rjson_site['observatoire'] as $n)
		{
			if($n['nomvar'] == $obs['observa'])
			{
				$configlatin = $n['latin'];
			}		
		}		
		if($latin == 'oui')
		{
			$titrepage = ($obs['rang'] != 'GN') ? '<i>'.$obs['nom'].'</i>' : '<i>'.$obs['nom'].' sp.</i>';
		}
		elseif($configlatin == 'oui' && ($latin == 'defaut' || $latin == ''))
		{
			$titrepage = ($obs['rang'] != 'GN') ? '<i>'.$obs['nom'].'</i>' : '<i>'.$obs['nom'].' sp.</i>';
		}
		elseif($configlatin == 'non' || $latin == 'non') 
		{
			$titrepage = ($obs['rang'] != 'GN') ? $obs['nomvern'].' (<i>'.$obs['nom'].'</i>)' : '<i>'.$obs['nom'].' sp.</i>';
		}
		elseif($latin == 'non') 
		{
			$titrepage = ($obs['rang'] != 'GN') ? $obs['nomvern'].' (<i>'.$obs['nom'].'</i>)' : '<i>'.$obs['nom'].' sp.</i>';
		}
		$favatar = 'photo/avatar/'.$obs['prenom'].''.$obs['idm'].'.jpg';
		$nomobservateur = '<a href="index.php?module=infoobser&amp;action=info&amp;idobser='.$obs['idobser'].'">'.$obs['prenom'].' '.$obs['nomobs'].'</a>';
		if($obs['plusobser'] == 'oui')
		{			
			$obs2[] = (file_exists($favatar)) ? '<img src="'.$favatar.'" width=36 height=36 alt="" class="rounded-circle"/> '.$nomobservateur : '<img src="photo/avatar/usera.jpg" width=36 height=36 alt="" class="img-circle"/> '.$nomobservateur;
			$obsplus = cherche_observateur($obs['idfiche']);
			foreach($obsplus as $o)
			{
				if(isset($_SESSION['idmembre']) && $o['idm'] == $_SESSION['idmembre']) { $adphoto = 'oui'; }
				$nomobservateur2 = '<a href="index.php?module=infoobser&amp;action=info&amp;idobser='.$o['idobser'].'">'.$o['prenom'].' '.$o['nom'].'</a>';
				$favatar = 'photo/avatar/'.$o['prenom'].''.$o['idm'].'.jpg';
				$obs2[] = (file_exists($favatar)) ? '<img src="'.$favatar.'" width=36 height=36 alt="" class="rounded-circle"/> '.$nomobservateur2 : '<img src="photo/avatar/usera.jpg" width=36 height=36 alt="" class="img-circle"/> '.$nomobservateur2;
				$favatar = null;
				$nomobservateur2 = null;
			}
			$observateur = implode('<br />', $obs2);
		}
		else
		{
			$observateur = (file_exists($favatar)) ? '<img src="'.$favatar.'" width=36 height=36 alt="" class="rounded-circle"/> '.$nomobservateur : '<img src="photo/avatar/usera.jpg" width=36 height=36 alt="" class="img-circle"/> '.$nomobservateur;
		}
		
		if($obs['cdnom'] != $obs['cdref'])
		{
			$nomor = recherche_sp($obs['cdnom'],$obs['observa']);
			$diffcdref = $nomor['nom'];
		}
		$det = recherche_det($obs['iddet']);
		$determinateur = $det['prenom'].' '.$det['nom'];
		$pre = $obs['localisation'];
		//sensibilité
		$sensible = sensible($obs['idfiche']);
		$floutage = $obs['floutage'];
		$flousen = ($obs['sensible'] != '') ? $obs['sensible'] : 0;
		foreach($sensible as $n)
		{
			$tabsensible[] = ($n['sensible'] != '') ? $n['sensible'] : 0;
		}
		$flousen = max($tabsensible);
		if($droit == 'oui') { $flousen = 0; $floutage = 0; }
		if ($pre == 3)
		{
			$flou = null;
			$sel = null;
		}
		if($floutage == 0 && $flousen == 0)
		{
			if($obs['localisation'] == 1)
			{
				$localisation = ''.$obs['site'].' - <a href="index.php?module=commune&amp;action=commune&amp;codecom='.$obs['codecom'].'">'.$obs['commune'].'</a> ('.$obs['iddep'].')';
				$flou = 'point';
				$sel = $obs['idcoord'];
			}
			elseif($obs['localisation'] == 2)
			{
				$localisation = 'Localisation à la commune - '.$obs['commune'].' ('.$obs['iddep'].')';
				$flou = 'commune';
				$sel = $obs['codecom'];
			}			
		}
		elseif($floutage == 1 || ($flousen <= 1 && $flousen > 0))
		{
			$localisation = ($obs['sensible'] == 1) ? 'Espèce sensible - localisation à la commune ('.$obs['commune'].')' : ''.$obs['commune'].' ('.$obs['iddep'].')';
			$flou = 'commune';
			$sel = $obs['codecom'];			
		}
		elseif($floutage == 2 || $flousen <= 2 && $flousen > 1)
		{
			$localisation = ($obs['sensible'] == 2) ? 'Espèce sensible - localisation à la maille 10 x 10' : 'Localisation à la maille 10 x 10 soit par décision de l\'observateur ou par manque de précision ou bien du fait de la présence d\'espèces sensibles dans le relevé dont dépend cette observation.';
			$flou = 'maille';
			$sel = $obs['idcoord'];
		}
		elseif($floutage == 3 || $flousen <= 3)
		{
			$localisation = ($obs['sensible'] == 3) ? 'Espèce sensible - localisation au département' : 'Localisation au département ('.$obs['iddep'].') soit par décision de l\'observateur ou par manque de précision.';
			$flou = 'dep';
			$sel = $obs['iddep'];
		}
		else
		{
			$localisation = 'Localisation non diffusable soit par décision de l\'observateur ou par manque de précision.';
			$flou = 'aucun';
			$sel = null;
		}
		$com = recherche_com($idobs);
		if(count($com) >= 1)
		{
			$mediacom = null;		
			$mediacom .= '<div class="border p-2">';
			foreach($com as $n)
			{
				$mediacom .= '<div class="media">';
				$favatar = 'photo/avatar/'.$n['prenom'].''.$n['idm'].'.jpg';
				if (file_exists($favatar))
				{
					$favatar = 'photo/avatar/'.$n['prenom'].''.$n['idm'].'.jpg';
					$mediacom .= '<img class="d-flex mr-3 rounded-circle" src="'.$favatar.'" width=30 height=30 alt="">';
				}
				else
				{
					$mediacom .= '<img class="d-flex mr-3 rounded-circle" src="photo/avatar/usera.jpg" width=30 height=30 alt="">';
				}
				$mediacom .= '<div class="media-body">';
				$mediacom .= '<h5 class="h6 mt-0">Par '.$n['prenom'].' '.$n['nom'].', '.$n['datefr'].'</h6>';
				$mediacom .= '<p>'.$n['commentaire'].'</p>';
				$mediacom .= '</div></div>';
			}
			$mediacom .= '</div>';
		}
		//validation
		$nouv = ($obs['validation'] == 7) ? 'oui' : 'non';
		if($obs['validation'] == 6) { $vali = '<i class="fa fa-check-circle"></i> Donnée en attente de validation'; }
		elseif($obs['validation'] == 7) { $vali = '<i class="fa fa-check-circle"></i> Donnée en attente de validation (Nouvelle espèce)'; }
		elseif($obs['validation'] == 5) { $vali = '<i class="fa fa-check-circle val5"></i> Donnée en attente de validation'; }
		elseif($obs['validation'] == 4) { $vali = '<i class="fa fa-check-circle val4"></i> Donnée invalide'; }
		elseif($obs['validation'] == 3) { $vali = '<i class="fa fa-check-circle val3"></i> Donnée considérée comme peu vraisemblable'; }
		elseif($obs['validation'] == 2) { $vali = '<i class="fa fa-check-circle val2"></i> Donnée considérée comme probable'; }
		elseif($obs['validation'] == 1) { $vali = '<i class="fa fa-check-circle val1"></i> Donnée considérée certaine, très probable'; }
		if($obs['statutobs'] != 'No')
		{
			$ligne = recherche_ligne($idobs);
			foreach($ligne as $n)
			{
				switch($n['idetatbio'])
				{
					case 0:$etat = 'Inconu'; break;
					case 1:$etat = 'Non renseigné'; break;
					case 2:$etat = 'Observé vivant'; break;
					case 3:$etat = 'Trouvé mort'; break;
				}
				$etatbio = '<br /><strong>Etat biologique : </strong>'.$etat;
				$methode = '<br /><strong>Contact : </strong>'.$n['methode'];
				$pros = '<br /><strong>Prospection : </strong>'.$n['prospection'];
				$statutbio = '<br /><strong>Statut biologique : </strong>'.$n['statutbio'];
                $comportement = '<br /><strong>Comportement : </strong>'.$n['libcomp'];
				if($n['denom'] == 'Co' && ($n['tdenom'] == 'IND' || $n['tdenom'] == '' || $n['tdenom'] == 'NSP'))
				{
					if(($n['ndiff'] != 0 && !empty($n['ndiff'])) && ($n['male'] != 0 && !empty($n['male'])) && ($n['femelle'] != 0 && !empty($n['femelle'])))
					{
						$tmpnb = '( '.$n['ndiff'].' non différencié(s) & '.$n['male']. ' <i class="fa fa-mars"></i> & '.$n['femelle']. ' <i class="fa fa-venus"></i> )';
					}
					elseif(($n['ndiff'] == 0 || empty($n['ndiff'])) && ($n['male'] != 0 && !empty($n['male'])) && ($n['femelle'] != 0 && !empty($n['femelle'])))
					{
						$tmpnb = '( '.$n['male']. ' <i class="fa fa-mars"></i> & '.$n['femelle']. ' <i class="fa fa-venus"></i> )';
					}
					elseif(($n['ndiff'] == 0 || empty($n['ndiff'])) && ($n['male'] == 0 || empty($n['male'])) && ($n['femelle'] != 0 && !empty($n['femelle'])))
					{
						$tmpnb = '( '.$n['femelle']. ' <i class="fa fa-venus"></i> )';
					}
					elseif(($n['ndiff'] == 0 || empty($n['ndiff'])) && ($n['male'] != 0 || !empty($n['male'])) && ($n['femelle'] == 0 || !empty($n['femelle'])))
					{
						$tmpnb = '( '.$n['male']. ' <i class="fa fa-mars"></i> )';
					}
					elseif(($n['ndiff'] != 0 && !empty($n['ndiff'])) && ($n['male'] == 0 || empty($n['male'])) && ($n['femelle'] == 0 || empty($n['femelle'])))
					{
						$tmpnb = '( '.$n['ndiff'].' non différencié(s) )';
					}
					elseif(($n['ndiff'] != 0 && !empty($n['ndiff'])) && ($n['male'] != 0 && !empty($n['male'])) && ($n['femelle'] == 0 || empty($n['femelle'])))
					{
						$tmpnb = '( '.$n['ndiff'].' non différencié(s) & '.$n['male']. ' <i class="fa fa-mars"></i> )';
					}
					elseif(($n['ndiff'] != 0 && !empty($n['ndiff'])) && ($n['male'] == 0 || empty($n['male'])) && ($n['femelle'] != 0 && !empty($n['femelle'])))
					{
						$tmpnb = '( '.$n['ndiff'].' non différencié(s) & '.$n['femelle']. ' <i class="fa fa-venus"></i> )';
					}
					elseif(($n['ndiff'] == 0 && empty($n['ndiff'])) && ($n['male'] == 0 && empty($n['male'])) && ($n['femelle'] == 0 && empty($n['femelle'])))
					{
						$tmpnb = '( présent )';
					}					
				}
				elseif($n['denom'] == 'Co' && ($n['tdenom'] != 'IND' || $n['tdenom'] != '' || $n['tdenom'] != 'NSP'))
				{
					if($n['tdenom'] == 'COL') { $denom = ($n['nbmin'] > 1) ? $n['nbmin'].' colonies' : $n['nbmin'].' colonie'; }
					elseif($n['tdenom'] == 'CPL') { $denom = ($n['nbmin'] > 1) ? $n['nbmin'].' couples' : $n['nbmin'].' couple'; }
					elseif($n['tdenom'] == 'HAM') { $denom = ($n['nbmin'] > 1) ? $n['nbmin'].' hampes florales' : $n['nbmin'].' hampe florale'; }
					elseif($n['tdenom'] == 'NID') { $denom = ($n['nbmin'] > 1) ? $n['nbmin'].' nids' : $n['nbmin'].' nid'; }
					elseif($n['tdenom'] == 'PON') { $denom = ($n['nbmin'] > 1) ? $n['nbmin'].' pontes' : $n['nbmin'].' ponte'; }
					elseif($n['tdenom'] == 'SURF') { $denom = ($n['nbmin'] > 1) ? 'Sur '.$n['nbmin'].' mètres carrés' : 'Sur '.$n['nbmin'].' mètre carré'; }
					elseif($n['tdenom'] == 'TIGE') { $denom = ($n['nbmin'] > 1) ? $n['nbmin'].' tiges' : $n['nbmin'].' tige'; }
					elseif($n['tdenom'] == 'TOUF') { $denom = ($n['nbmin'] > 1) ? $n['nbmin'].' touffes' : $n['nbmin'].' touffe'; }
					$tmpnb = '( '.$denom.' )';				
				}
				elseif($n['denom'] == 'Es' && ($n['tdenom'] == 'IND' || $n['tdenom'] == '' || $n['tdenom'] == 'NSP'))
				{
					if($n['nbmin'] == $n['nbmax'])
					{
						if(($n['ndiff'] != 0 && !empty($n['ndiff'])) && ($n['male'] != 0 && !empty($n['male'])) && ($n['femelle'] != 0 && !empty($n['femelle'])))
						{
							$tmpnb = '( environ '.$n['ndiff'].' non différencié(s) & '.$n['male']. ' <i class="fa fa-mars"></i> & '.$n['femelle']. '<i class="fa fa-venus"></i> )';
						}
						elseif(($n['ndiff'] == 0 || empty($n['ndiff'])) && ($n['male'] != 0 && !empty($n['male'])) && ($n['femelle'] != 0 && !empty($n['femelle'])))
						{
							$tmpnb = '( environ '.$n['male']. ' <i class="fa fa-mars"></i> & '.$n['femelle']. ' <i class="fa fa-venus"></i> )';
						}
						elseif(($n['ndiff'] == 0 || empty($n['ndiff'])) && ($n['male'] == 0 || empty($n['male'])) && ($n['femelle'] != 0 && !empty($n['femelle'])))
						{
							$tmpnb = '( environ '.$n['femelle']. ' <i class="fa fa-venus"></i> )';
						}
						elseif(($n['ndiff'] == 0 || empty($n['ndiff'])) && ($n['male'] != 0 || !empty($n['male'])) && ($n['femelle'] == 0 || !empty($n['femelle'])))
						{
							$tmpnb = '( environ '.$n['male']. ' <i class="fa fa-mars"></i> )';
						}
						elseif(($n['ndiff'] != 0 && !empty($n['ndiff'])) && ($n['male'] == 0 || empty($n['male'])) && ($n['femelle'] == 0 || empty($n['femelle'])))
						{
							$tmpnb = '( environ '.$n['ndiff'].' non différencié(s) )';
						}
						elseif(($n['ndiff'] != 0 && !empty($n['ndiff'])) && ($n['male'] != 0 && !empty($n['male'])) && ($n['femelle'] == 0 || empty($n['femelle'])))
						{
							$tmpnb = '( environ '.$n['ndiff'].' non différencié(s) & '.$n['male']. ' <i class="fa fa-mars"></i> )';
						}
						elseif(($n['ndiff'] != 0 && !empty($n['ndiff'])) && ($n['male'] == 0 || empty($n['male'])) && ($n['femelle'] != 0 && !empty($n['femelle'])))
						{
							$tmpnb = '( environ '.$n['ndiff'].' non différencié(s) & '.$n['femelle']. ' <i class="fa fa-venus"></i> )';
						}							
					}
					else
					{
						$tmpnb = '( entre '.$n['nbmin'].' et '.$n['nbmax'].' )';
					}					
				}
				elseif($n['denom'] == 'Es' && ($n['tdenom'] != 'IND' || $n['tdenom'] != '' || $n['tdenom'] != 'NSP'))
				{
					if($n['tdenom'] == 'COL') { $denom = 'colonies'; }
					elseif($n['tdenom'] == 'CPL') { $denom = 'couples'; }
					elseif($n['tdenom'] == 'HAM') { $denom = 'hampes florales'; }
					elseif($n['tdenom'] == 'NID') { $denom = 'nids'; }
					elseif($n['tdenom'] == 'PON') { $denom = 'pontes'; }
					elseif($n['tdenom'] == 'SURF') { $denom = 'mètres carrés'; }
					elseif($n['tdenom'] == 'TIGE') { $denom = 'tiges'; }
					elseif($n['tdenom'] == 'TOUF') { $denom = 'touffes'; }					
					$tmpnb = '( entre '.$n['nbmin'].' et '.$n['nbmax'].' '.$denom.' )';
				}
				elseif($n['denom'] == 'NSP')
				{
					$tmpnb = '( Espèce présente )';
				}
				$tmpligne[] = '&rarr; '.$n['stade'].' '.$tmpnb.$etatbio.$methode.$pros.$statutbio.$comportement.'<br />';
			}
			$ligne = implode('<br /> ', $tmpligne);
			
			$json = file_get_contents('json/'.$obs['observa'].'.json');
			$rjson = json_decode($json, true);
			if(isset($rjson['saisie']['plteh']))
			{
				$tablebota = ($rjson['saisie']['listebota'] == 'aucune') ? 'listebota' : $rjson['saisie']['listebota'];
				$plante = plante($idobs,$tablebota);
				if(count($plante) > 0)
				{
					foreach($plante as $n)
					{
						$tmpbota[] = '- '.$n['stade'].' '.$n['nb'].' sur <i>'.$n['nom'].'</i> '.$n['nomvern'];					
					}
					$pltebota = implode('<br /> ', $tmpbota);
				}
			}
			if(isset($rjson['saisie']['aves']))
			{
				$aves = aves($idobs);
				if(!empty($aves['code']))
				{
					if($aves['code'] <= 3) { $nicheur = 'Nidification possible'; }
					elseif($aves['code'] > 3 && $aves['code'] <= 10) { $nicheur = 'Nidification probable'; }
					elseif($aves['code'] > 10) { $nicheur = 'Nidification certaine'; }
					$piaf = $nicheur.', code : '.$aves['code'];
				}
			}
		}
		else
		{
			$ligne = 'Absence de contact';
		}
		$listephoto = cherche_photo($idobs);
		if(count($listephoto > 0))
		{
			$obsphoto = null;
			foreach($listephoto as $n)
			{
				$obsphoto .= '<div class="col-md-6">';
				$obsphoto .= '<a href="photo/P800/'.$n['observatoire'].'/'.$n['nomphoto'].'.jpg" title="'.$n['auteur'].' - '.$n['stade'].'">';
				$obsphoto .= '<img src="photo/P200/'.$n['observatoire'].'/'.$n['nomphoto'].'.jpg" class="img-thumbnail" alt="'.$obs['nom'].'">';
				$obsphoto .= '</a><br />';
				$obsphoto .= (isset($_SESSION['idmembre']) && $idm == $_SESSION['idmembre']) ? '<span class="xsmall fa fa-copyright"> '.$n['auteur'].' - '.$n['stade'].'</span><i class="ml-2 text-danger fa fa-trash curseurlien" title="Supprimer la photo" onclick="supph('.$n['idphoto'].')"></i>' : '<span class="xsmall fa fa-copyright"> '.$n['auteur'].' - '.$n['stade'].'</span>';
				$obsphoto .= '</div>';
			}
			$photo = $obsphoto;		
		}
		$listeson = cherche_son($idobs);
		if(count($listeson) > 0)
		{
			$obsson = null;
			foreach($listeson as $n)
			{
				$obsson .= '<div>';
				$obsson .= '<p class="mb-0">- '.$n['descri'].', <span class="small">enregistrement de '.$n['auteur'].'</span></p>';
				$obsson .= '<audio controls="controls" preload="none"><source src="son/'.$n['nomson'].'.mp3" type="audio/mp3"/>Votre navigateur n\'est pas compatible</audio>';
                $obsson .= (isset($_SESSION['idmembre']) && $idm == $_SESSION['idmembre']) ? '</span><i class="ml-2 text-danger fa fa-trash curseurlien" title="Supprimer le son" onclick="supson('.$n['idson'].')"></i>' : null;
                $obsson .= '</div>';
			}
			$son = $obsson;
		}
		$url = 'http://'.$_SERVER['HTTP_HOST'].''.$_SERVER['REQUEST_URI'];
	}
	include CHEMIN_VUE.'detail.php';
}