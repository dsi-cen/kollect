<?php 
$css = '';

if(isset($_GET['idobser'])) 
{
    include CHEMIN_MODELE.'infoobser.php';

    $idobser = htmlspecialchars($_GET['idobser']);
	
	if($idobser == 'na' && isset($_SESSION['idmembre']))
	{
		//si c'est un membre qui arrive via le lien sa page du menu
		$observateur = cherche_observateurmembre($_SESSION['idmembre']);
		$idobser = $observateur['idobser'];
		if($idobser == '') // si le membre n'est pas observateur... il a pas de page
		{
			header('location:index.php');
		}
	}
	elseif($idobser == 'na' && !isset($_SESSION['idmembre']))
	{
		header('location:index.php');//si il se déconnecte
	}
	else
	{
		$observateur = cherche_observateur($idobser);
	}
    	
    $titre = $observateur['prenom'].' '.$observateur['nom'];
    $description = 'Fiche de '.$titre.' du site '.$rjson_site['titre'];
	
	//récupération de l'avatar si existe
	$cheminavatar = 'photo/avatar/'.$observateur['prenom'].''.$observateur['idm'].'.jpg';
	$favatar = (file_exists($cheminavatar)) ? '<img src="'.$cheminavatar.'" width=36 height=36 alt="" class="rounded-circle"/>' : '<img src="photo/avatar/usera.jpg" width=36 height=36 alt="" class="img-circle"/>';	
	
	//Vérification si module social activé ou pas
	if($rjson_site['social'] == 'oui')//si activé
	{
	    $script = '<script src="dist/js/jquery.js" defer></script>
		<script src="dist/js/bootstrap.min.js" defer></script>
		<script type="text/javascript" src="gestion/dist/js/ckeditor/ckeditor.js" defer></script>
		<script src="dist/js/social.js" defer></script>';		
		
		/*if(isset($_SESSION['idmembre']))
		{  
			$idmembre = $_SESSION['idmembre'];
			$idsession = $_SESSION['idmembre'];
		}
		else
		{
			$idsession = null;
		}*/
		$idsession = (isset($_SESSION['idmembre'])) ? $_SESSION['idmembre'] : null;
		//Comparaison d'ID
		$idcompare = chercheid($idobser);
		$idcompare = $idcompare['idm'];

		//STATISTIQUES
		$nbobssp = nbobs($idobser);
		$nbobs = $nbobssp['nb'];
		$nbsp = $nbobssp['nbsp'];
		$nbphoto = nbphoto($idobser);
		/*$nbobs2 = nbobs2($idobser);
		$nbobs = $observateur['nb'] + $nbobs2['nb'];
		$nbphoto = nbphoto($idobser);
		$nbphoto = $nbphoto['nb'];
		$nbsp = nbespece($idobser);
		$nbsp = $nbsp['nb'];*/

		//AVATAR
		/*$favatar = null;
		$prenom = cherche_observateur($idobser);
		$prenom = $prenom['prenom'];

		$favatar = 'photo/avatar/'.$prenom.''.$idcompare.'.jpg';
		if (file_exists($favatar))
		{ 
			$favatar = $favatar;
		}
		else
		{
			$favatar = 'photo/avatar/usera.jpg';
		}*/

		//ABONNEMENT
		// NB DE NOUVEAU ABONNEMENTS
		//$nbabo = nouveauabo($idcompare);
		//$nbabo = $nbabo['nb'] ;

		//recherche  ABONNES à la page
		$abo = chercheabo($idcompare);
		//$abo = $abo['nb'];
		/*if (empty($abo))
		{
			$abo= 0;
		}*/
		// Compte le NOMBRE D'ABONNEMENT de la page
		$folo = cherchefolo($idcompare);
		/*$folo= $folo['nb'];
		if (empty($folo))
		{

			$folo= 0;
		}*/
		//COMMENTAIRES
		$listepost = recherche_post($idcompare);

		/*$mediacom = null;

		if (count($post) >= 1)
		{
			$mediacom = null;		

			$mediacom .= '<div class="col-md-12 col-lg-12">';
			foreach($post as $n)
			{
				$mediacom .= '<div class=row m-b-1">';
				$mediacom .= '<div class="media-left" href="#">';
				$comavatar = 'photo/avatar/'.$n['prenom'].''.$idcompare.'.jpg';
				if (file_exists($comavatar))
				{
					$comavatar = 'photo/avatar/'.$n['prenom'].''.$idcompare.'.jpg';
					$mediacom .= '<img class="media-object img-circle" src="'.$comavatar.'" width=30 height=30 alt=""></div>';
				}
				else
				{
					$mediacom .= '<img class="media-object img-circle" src="photo/avatar/usera.jpg" width=30 height=30 alt=""></div>';
				}
				$mediacom .= '<div class="media-body">';
				$mediacom .= '<p media-heading"> <strong>'.$n['prenom'].' '.$n['nom'].'</strong>, '.$n['datefr'].'</p>';
				$mediacom .= '<p>'.$n['texte'].'</p>';
				$mediacom .= '</div></div>';
			}
			$mediacom .= '</div>';
		}*/

		//S'il est abonné a des pages, alors il peut voir ONGLET ABONEMENTS
		/*if (isset($idsession) && ($idcompare == $idsession)  && ($nbabo > 0 && $folo > 0))
		{
			$tababo = null;
			$tababo .= 
				'<li class="nav-item ">
			<a class="nav-link" data-toggle="tab" href="#comabo" aria-controls="comabo" id="reset"  >
			<h4>Abonnements <span class="tag tag-danger tag-pill" >'.$nbabo.'</span></h4>
			</a>        
			</li>' ;
		}
		elseif (isset ($idsession) and $idcompare == $idsession and $nbabo==0 and $folo>0)
		{
			$tababo = null;
			$tababo .= 
				'<li class="nav-item ">
			<a class="nav-link" data-toggle="tab" href="#comabo" aria-controls="comabo" id="reset"  >
			<h4>Abonnements</h4>
			</a>        
			</li>' ;
		}
		elseif (isset ($idsession) and $idcompare == $idsession and $folo==0 )
		{
			$tababo = null;
			$tababo .= '<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#comabo" aria-controls="comabo"><h4>Aucun abonnements</h4></a>
					</li>' ;
		}
		elseif (isset ($idsession) and $idcompare !== $idsession)
		{
			$tababo = null;

		}
		else
		{
			$tababo = null;
		}*/

		//Com des abonnement
		if(isset($idsession) && ($idcompare == $idsession))
		{
			// NB DE NOUVEAU ABONNEMENTS
			$nbabo = nouveauabo($idcompare);
			//recherche des post
			$post = cherchecomabo($idcompare);
			if(count($post > 0))
			{
				foreach($post as $n)
				{
					$cheminavatar = 'photo/avatar/'.$n['prenom'].''.$n['idmembre'].'.jpg';
					$abavatar = (file_exists($cheminavatar)) ? $cheminavatar : 'photo/avatar/usera.jpg';
					if($n['idtype'] == '') // ancien post
					{
						$tabcomabo[] = array('avatar'=>$abavatar, 'idobser'=>$n['idobser'], 'nom'=>$n['nom'], 'prenom'=>$n['prenom'], 'date'=>$n['datefr'], 'texte'=>$n['texte']);
					}
					elseif($n['idtype'] != '') // nouveau post
					{
						$tabnouvabo[] = array('avatar'=>$abavatar, 'idobser'=>$n['idobser'], 'nom'=>$n['nom'], 'prenom'=>$n['prenom'], 'date'=>$n['datefr'], 'texte'=>$n['texte']);
					}
				}
			}			
		}
		/*$post = cherchecomabo($idcompare);

		$comabo = null;

		if (isset ($idsession) and count($post) >= 1)
		{
			$comabo = null;		

			$comabo .= '<div class="col-md-12 col-lg-12">';
			foreach($post as $n)
			{
				$comabo .= '<div class=row m-b-1">';
				$comabo .= '<div class="media-left" href="#">';
				$abavatar = 'photo/avatar/'.$n['prenom'].''.$n['id_obser'].'.jpg';
				if (file_exists($abavatar))
				{
					$abavatar = 'photo/avatar/'.$n['prenom'].''.$n['id_obser'].'.jpg';
					$comabo .= '<img class="media-object img-circle" src="'.$abavatar.'" width=30 height=30 alt=""></div>';
				}
				else
				{
					$comabo .= '<img class="media-object img-circle" src="photo/avatar/usera.jpg" width=30 height=30 alt=""></div>';
				}
				$comabo .= '<div class="media-body">';
				$comabo .= '<a href="index.php?module=infoobser&action=info&idobser='.$n['id_obser'].'">
				<p media-heading">
				'.$n['prenom'].'
				'.$n['nom'].',
				'.$n['datefr'].'
				</p>
				</a>';
				$comabo .= '<p>'.$n['texte'].'</p>';
				$comabo .= '</div></div>';
			}
			$comabo .= '</div>';
		}*/


		//DERNIERE OBS
		// A revoir inclure floutage espèce sensible (je le ferais)
		$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
		if (isset ($rjson_site['observatoire']))
		{
			$listeobs = listeobs($idobser);
			if (count($listeobs) > 0)
			{
				foreach ($listeobs as $n)
				{
					foreach ($rjson_site['observatoire'] as $d)
					{
						if($d['nomvar'] == $n['observa'])
						{
							if($d['latin'] == 'oui' && $latin == 'oui')
							{
								$tabobs[] = array('latin'=>'oui', 'datefr'=>$n['datefr'], 'nomlat'=>$n['nom'], 'nomfr'=>$n['nomvern'], 'icon'=>$d['icon'], 'nomvar'=>$d['nomvar'], 'commune'=>$n['commune'], 'idobs'=>$n['idobs']);
							}
							elseif($d['latin'] == 'oui' && ($latin == 'defaut' || $latin == ''))
							{
								$tabobs[] = array('latin'=>'oui', 'datefr'=>$n['datefr'], 'nomlat'=>$n['nom'], 'nomfr'=>$n['nomvern'], 'icon'=>$d['icon'], 'nomvar'=>$d['nomvar'], 'commune'=>$n['commune'],'idobs'=>$n['idobs']);
							}
							elseif($d['latin'] == 'non' && $latin == 'oui')
							{
								$tabobs[] = array('latin'=>'oui', 'datefr'=>$n['datefr'], 'nomlat'=>$n['nom'], 'nomfr'=>$n['nomvern'], 'icon'=>$d['icon'], 'nomvar'=>$d['nomvar'], 'commune'=>$n['commune'],'idobs'=>$n['idobs']);
							}
							elseif($d['latin'] == 'non' || $latin == 'non') 
							{
								$tabobs[] = array('latin'=>'non', 'datefr'=>$n['datefr'], 'nomlat'=>$n['nom'], 'nomfr'=>$n['nomvern'], 'icon'=>$d['icon'], 'nomvar'=>$d['nomvar'], 'commune'=>$n['commune'],'idobs'=>$n['idobs']);
							}			
						}
					}	
				}
			}
		}		
		include CHEMIN_VUE.'info.php';
	}
	elseif($rjson_site['social'] == 'non')//si module social non activé. 
	{
		$script = '<script src="dist/js/jquery.js" defer></script>
		<script src="dist/js/bootstrap.min.js" defer></script>';		
		
		//nb observations
		$nbobs = nbobs_observa($idobser);
		$nbobs1 = 0;
		$nbsp = 0;
		foreach($rjson_site['observatoire'] as $n)
		{
			foreach($nbobs as $a)
			{
				if($a['observa'] == $n['nomvar'])
				{
					$tab[] = array('nom'=>$n['nom'],'icon'=>$n['icon'],'nb'=>$a['nb'],'nbsp'=>$a['nbsp']);
					$nbobs1 = $nbobs1 + $a['nb'];
					$nbsp = $nbsp + $a['nbsp'];
				}
			}
		}
		include CHEMIN_VUE.'infona.php';
	}
}
?>