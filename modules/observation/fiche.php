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

if(isset($_GET['idfiche'])) 
{
	$idfiche = htmlspecialchars($_GET['idfiche']);
	$titre = 'Relévé '.$idfiche;
	$description = 'Détail du relevé '.$idfiche.' sur le site '.$rjson_site['titre'];
	
	$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
	
	$json_emprise = file_get_contents('emprise/emprise.json');
	$rjson_emprise = json_decode($json_emprise, true);
	$color = $rjson_emprise['stylecontour']['color'];
	$weight = $rjson_emprise['stylecontour']['weight'];
	$opacity = $rjson_emprise['stylecontour']['opacity'];
	
	$biblio = ($rjson_site['biblio'] == 'oui') ? 'oui' : 'non';
	
	$info = info_fiche($idfiche,$biblio);
	if(!empty($info))
	{
		$ficheexist = 'oui';
		$datefiche = ($info['datefr'] == $info['datefr2']) ? $info['datefr'] : 'Entre le '.$info['datefr'].' et le '.$info['datefr2'];
		$favatar = 'photo/avatar/'.$info['prenom'].''.$info['idm'].'.jpg';
		$nomobservateur = '<a href="index.php?module=infoobser&amp;action=info&amp;idobser='.$info['idobser'].'">'.$info['prenom'].' '.$info['nom'].'</a>';
		if($info['plusobser'] == 'oui')
		{			
			$obs2[] = (file_exists($favatar)) ? '<img src="'.$favatar.'" width=36 height=36 alt="" class="rounded-circle"/> '.$nomobservateur : '<img src="photo/avatar/usera.jpg" width=36 height=36 alt="" class="img-circle"/> '.$nomobservateur;
			$obsplus = cherche_observateur($idfiche);
			foreach($obsplus as $o)
			{
				$nomobservateur2 = '<a href="index.php?module=infoobser&amp;action=info&amp;idobser='.$o['idobser'].'">'.$o['prenom'].' '.$o['nom'].'</a>';
				$favatar = 'photo/avatar/'.$o['prenom'].''.$o['idm'].'.jpg';
				$obs2[] = (file_exists($favatar)) ? '<img src="'.$favatar.'" width=36 height=36 alt="" class="rounded-circle"/> '.$nomobservateur2 : '<img src="photo/avatar/usera.jpg" width=36 height=36 alt="" class="img-circle"/> '.$nomobservateur2;
				$favatar = null;
				$nomobservateur2 = null;
			}
			$observateur = implode(',  ', $obs2);
		}
		else
		{
			$observateur = (file_exists($favatar)) ? '<img src="'.$favatar.'" width=36 height=36 alt="" class="rounded-circle"/> '.$nomobservateur : '<img src="photo/avatar/usera.jpg" width=36 height=36 alt="" class="img-circle"/> '.$nomobservateur;
		}
		$idm = $info['idm'];
								
		$fiche = recherche_obs_fiche($idfiche);
		
		if(count($fiche) > 0)
		{
			$observa = recherche_observa($idfiche);
			$nbt = 0;
			foreach($observa as $n)
			{
				$tabobserva[$n['observa']] = $n['nb'];
				$nbt += $n['nb'];
			}
			foreach($fiche as $n)
			{
				switch($n['validation'])
				{
					case 1:$clvali = 'val1'; break;
					case 2:$clvali = 'val2'; break;
					case 3:$clvali = 'val3'; break;
					case 4:$clvali = 'val4'; break;
					case 5:$clvali = 'val5'; break;
					case 6:$clvali = ''; break;
					case 7:$clvali = ''; break;
				}
				
				foreach($rjson_site['observatoire'] as $d)
				{
					if($d['nomvar'] == $n['observa'])
					{
						$nobserva = (isset($tabobserva[$n['observa']])) ? $tabobserva[$n['observa']] : '';
						
						$sel[] = ['nom'=>$d['nom'], 'nomvar'=>$d['nomvar'], 'nb'=>$nobserva];
						
						if($d['latin'] == 'oui' && $latin == 'oui')
						{
							$tabobs[] = ['latin'=>'oui', 'nomlat'=>$n['nom'], 'nomfr'=>$n['nomvern'], 'idobs'=>$n['idobs'], 'nomvar'=>$d['nomvar'], 'cdnom'=>$n['cdref'], 'auteur'=>$n['auteur'], 'rang'=>$n['rang'], 'nb'=>$n['nb'], 'vali'=>$clvali];
						}
						elseif($d['latin'] == 'oui' && ($latin == 'defaut' || $latin == ''))
						{
							$tabobs[] = ['latin'=>'oui', 'nomlat'=>$n['nom'], 'nomfr'=>$n['nomvern'], 'idobs'=>$n['idobs'], 'nomvar'=>$d['nomvar'], 'cdnom'=>$n['cdref'], 'auteur'=>$n['auteur'], 'rang'=>$n['rang'], 'nb'=>$n['nb'], 'vali'=>$clvali];
						}
						elseif($d['latin'] == 'non' && $latin == 'oui')
						{
							$tabobs[] = ['latin'=>'oui', 'nomlat'=>$n['nom'], 'nomfr'=>$n['nomvern'], 'idobs'=>$n['idobs'], 'nomvar'=>$d['nomvar'], 'cdnom'=>$n['cdref'], 'auteur'=>$n['auteur'], 'rang'=>$n['rang'], 'nb'=>$n['nb'], 'vali'=>$clvali];
						}
						elseif($d['latin'] == 'non' || $latin == 'non') 
						{
							$tabobs[] = ['latin'=>'non', 'nomlat'=>$n['nom'], 'nomfr'=>$n['nomvern'], 'idobs'=>$n['idobs'], 'nomvar'=>$d['nomvar'], 'cdnom'=>$n['cdref'], 'auteur'=>$n['auteur'], 'rang'=>$n['rang'], 'nb'=>$n['nb'], 'vali'=>$clvali];
						}			
					}
				
				}
				$tabsensible[] = ($n['sensible'] != '') ? $n['sensible'] : 0;
			}
			$tabtmp = array_map( 'serialize' , $sel );
			$tabtmp = array_unique( $tabtmp );
			$sel = array_map( 'unserialize' , $tabtmp );
			$flousen = max($tabsensible);
			
			$photo = recherche_photo_fiche($idfiche);
			if($photo != false)
			{
				$nbphoto = count($photo);
				$libphoto = ($nbphoto > 1) ? $nbphoto.' photos' : $nbphoto.' photo';
			}
		}
		else
		{
			$flousen = $info['floutage'];
		}
		$pre = $info['localisation'];
		$floutage = $info['floutage'];
		if($floutage == 0 && $flousen == 0)
		{
			$localisation = $info['site'].' - '.$info['commune'].' ('.$info['iddep'].')';	
			$flou = 'point';
			$type = $info['idcoord'];
		}
		elseif($floutage == 1 || $flousen == 1)
		{
			$localisation = ($flousen == 1) ? 'Espèce(s) sensible(s) dans ce relevé - localisation à la commune ('.$info['commune'].')' : ''.$info['commune'].' ('.$info['iddep'].')';
			$flou = 'commune';
			$type = $info['codecom'];
		}
		elseif($floutage == 2 || $flousen == 2)
		{
			$localisation = ($flousen == 2) ? 'Espèce(s) sensible(s) dans ce relevé - localisation à la maille 10 x 10' : 'Localisation à la maille 10 x 10 soit par décision de l\'observateur ou par manque de précision.';
			$flou = 'maille';
			$type = $info['idcoord'];
		}
		elseif($floutage == 3 || $flousen == 3)
		{
			$localisation = ($flousen == 3) ? 'Espèce(s) sensible(s) dans ce relevé - localisation au département' : 'Localisation au département ('.$info['iddep'].') soit par décision de l\'observateur ou par manque de précision.';
		}
		else
		{
			$localisation = 'Localisation non diffusable soit par décision de l\'observateur ou par manque de précision.';
			$flou = 'dep';
			$type = $info['iddep'];
		}
	}
	else
	{
		$ficheexist = 'non';
	}
	
	include CHEMIN_VUE.'fiche.php';
}