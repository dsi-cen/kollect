<?php
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>
<script src="../dist/js/popup-image.js" defer></script>
<script src="../dist/js/isotope.js" defer></script>';
$css = '<link rel="stylesheet" href="../dist/css/popup.css" type="text/css">';

if(isset($_GET['id'])) 
{
	include CHEMIN_MODELE.'photo.php';
	
	$id = htmlspecialchars($_GET['id']);
	if(isset($_GET['idobser'])) 
	{
		$idobser = htmlspecialchars($_GET['idobser']);
	}
	$taxon = taxon($id,$nomvar);
	if(!empty($taxon['nom']))
	{
		$nom = $taxon['nom'];
		$nomfr = $taxon['nomvern'];
		
		$liste = (!isset($idobser)) ? phototaxon($id) : phototaxon_idobser($id,$idobser);
		$libt = ($liste[0] > 1) ? 'Photos' : 'Photo';		
				
		//affichage latin ou non
		$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
		$titre = ($rjson_obser['latin'] == 'oui') ? 'Photos de '.$nom : 'Photo de '.$nomfr;
		$description = ($rjson_obser['latin'] == 'oui') ? 'Photo de '.$nom.' '.$rjson_site['ad2'].' '.$rjson_site['lieu'] : 'Photo de '.$nomfr.' '.$rjson_site['ad2'].' '.$rjson_site['lieu'];
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

		$sexe = (!isset($idobser)) ? recherche_sexe($id) : recherche_sexe_idobser($id,$idobser);
		foreach($sexe as $n)
		{
			if($n['sexe'] == 'M') { $male = 'oui'; }
			if($n['sexe'] == 'F') { $femelle = 'oui'; }
			if($n['sexe'] == 'C') { $cple = 'oui'; }
		}
		$stade = (!isset($idobser)) ? recherche_stade($id) : recherche_stade_idobser($id,$idobser);
		
		if(isset($_GET['idobser'])) 
		{
			$observateur = cherche_observateur($idobser);
			$titre = ($rjson_obser['latin'] == 'oui') ? $observateur['prenom'].' '.$observateur['nom'].' - Photos de '.$nom : $observateur['prenom'].' '.$observateur['nom'].' - Photo de '.$nomfr;
			$description = ($rjson_obser['latin'] == 'oui') ? $observateur['prenom'].' '.$observateur['nom'].' - Photo de '.$nom.' '.$rjson_site['ad2'].' '.$rjson_site['lieu'] : $observateur['prenom'].' '.$observateur['nom'].' - Photo de '.$nomfr.' '.$rjson_site['ad2'].' '.$rjson_site['lieu'];
		}
		if(isset($_GET['f']))
		{
			$idfam = htmlspecialchars($_GET['f']);	
			$fam = recherche_famille($idfam,$nomvar);
			if(isset($_GET['sf']))
			{
				$idsfam = htmlspecialchars($_GET['sf']);	
				$sfam = recherche_sous_famille($idsfam,$nomvar);
			}			
		}
		
		include CHEMIN_VUE.'taxon.php';
	}
	else
	{
		header('location:index.php?d='.$nomvar.'');
	}	
}