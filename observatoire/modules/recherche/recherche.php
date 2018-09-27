<?php 
$script = '<script src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>
<script src="../dist/js/jquery-auto.js" defer></script>
<script src="../dist/js/recherche.js" defer></script>';
$css = '';

$json_emprise = file_get_contents('../emprise/emprise.json');
$rjson_emprise = json_decode($json_emprise, true);
$dep = ($rjson_emprise['emprise'] == 'fr' || $rjson_emprise['contour2'] == 'oui') ? 'oui' : 'non';

if(isset($_GET['recherche'])) 
{
	$recherche = htmlspecialchars($_GET['recherche']);
	
	if($recherche == '')
	{
		$titre = 'Recherche '.$nomd;
		$description = 'Recherche de '.$nomd.' sur le site';		
		include CHEMIN_VUE.'recherche.php';
	}
	else
	{
		include CHEMIN_MODELE.'recherche.php';
		
		$titre = $nomd.' Resultat';
		$description = 'Resultat de la recherche de '.$nomd;
		
		$result = rechercher_espece($recherche,$nomvar);
		
		if($result[0] >= 1)
		{
			foreach($result[1] as $n)
			{
				if($n['rang'] == 'COM')
				{
					$taxon = '<a href="index.php?module=fiche&amp;action=fichec&amp;d='.$nomvar.'&amp;id='.$n['cdnom'].'"><i>'.$n['nom'].'</i></a>';
				}			
				elseif($n['rang'] == 'GN')
				{
					$taxon = '<a href="index.php?module=fiche&amp;action=ficheg&amp;d='.$nomvar.'&amp;id='.$n['cdnom'].'">Genre <i>'.$n['nom'].'</i></a>';
					if(!empty($n['photo']))
					{
						$photo[] = (!empty($n['nomvern'])) ? '<a href="index.php?module=photo&amp;action=taxon&amp;d='.$nomvar.'&amp;id='.$n['cdnom'].'">Photos de <i>'.$n['nom'].'</i> - '.$n['nomvern'].'</a>' : '<a href="index.php?module=photo&amp;action=taxon&amp;d='.$nomvar.'&amp;id='.$n['cdnom'].'">Photos de <i>'.$n['nom'].'</i></a>';
					}
				}
				else
				{
					$taxon = (!empty($n['nomvern'])) ? '<a href="index.php?module=fiche&amp;action=fiche&amp;d='.$nomvar.'&amp;id='.$n['cdnom'].'"><i>'.$n['nom'].'</i> - '.$n['nomvern'].'</a>' : '<a href="index.php?module=fiche&amp;action=fiche&amp;d='.$nomvar.'&amp;id='.$n['cdnom'].'"><i>'.$n['nom'].'</i></a>';			
					if(!empty($n['photo']))
					{
						$photo[] = (!empty($n['nomvern'])) ? '<a href="index.php?module=photo&amp;action=taxon&amp;d='.$nomvar.'&amp;id='.$n['cdnom'].'">Photos de <i>'.$n['nom'].'</i> - '.$n['nomvern'].'</a>' : '<a href="index.php?module=photo&amp;action=taxon&amp;d='.$nomvar.'&amp;id='.$n['cdnom'].'">Photos de <i>'.$n['nom'].'</i></a>';
					}
				}
				$fam[$n['cdnomf']] = '<a href="index.php?module=famille&amp;action=famille&amp;d='.$nomvar.'&amp;id='.$n['cdnomf'].'">Famille des '.$n['famille'].'</a>'; 
				$tabr[] = $taxon;				
			}			
		}
		$nbphoto = (isset($photo)) ? count($photo) : 0;
		$nbfam = (isset($fam)) ? count($fam) : 0;
		
		if($result[0] >= 0)
		{
			$nbt = $result[0] + $nbphoto + $nbfam;
			$librech = ($nbt > 1) ? $nbt.' occurences pour <b>'.$recherche.'</b>' : $nbt.' occurence pour <b>'.$recherche.'</b>' ;
		}
		
		if(isset($rjson_obser['saisie']['plteh']) && $rjson_obser['saisie']['listebota'] != 'aucune')
		{
			$bota = rechercher_plte($recherche,$nomvar);
			if($bota[0] >= 1)
			{
				foreach($bota[1] as $n)
				{
					if($n['rang'] == 'GN')
					{
						$taxon = '<a href="index.php?module=fiche&amp;action=ficheg&amp;d='.$n['observatoire'].'&amp;id='.$n['cdnom'].'">Genre <i>'.$n['nom'].'</i></a>';
					}
					else
					{
						$taxon = (!empty($n['nomvern'])) ? '<a href="index.php?module=fiche&amp;action=fiche&amp;d='.$n['observatoire'].'&amp;id='.$n['cdnom'].'"><i>'.$n['nom'].'</i> - '.$n['nomvern'].'</a>' : '<a href="index.php?module=fiche&amp;action=fiche&amp;d='.$n['observatoire'].'&amp;id='.$n['cdnom'].'"><i>'.$n['nom'].'</i></a>';			
					}
					$tabbota[] = $taxon;
				}
			}
		}
		if(isset($rjson_obser['saisie']['bota']))
		{
			$bota = rechercher_obsplte($recherche,$nomvar);
			if($bota[0] >= 1)
			{
				foreach($bota[1] as $n)
				{
					if($n['rang'] == 'GN')
					{
						$taxon = '<a href="index.php?module=fiche&amp;action=ficheg&amp;d='.$n['observatoire'].'&amp;id='.$n['cdnom'].'">Genre <i>'.$n['nom'].'</i></a>';
					}
					else
					{
						$taxon = (!empty($n['nomvern'])) ? '<a href="index.php?module=fiche&amp;action=fiche&amp;d='.$n['observatoire'].'&amp;id='.$n['cdnom'].'"><i>'.$n['nom'].'</i> - '.$n['nomvern'].'</a>' : '<a href="index.php?module=fiche&amp;action=fiche&amp;d='.$n['observatoire'].'&amp;id='.$n['cdnom'].'"><i>'.$n['nom'].'</i></a>';			
					}
					$tabobsbota[] = $taxon;
				}
			}
		}
		
		include CHEMIN_VUE.'resultat.php';
	}	
}
else
{
	$titre = 'Recherche '.$nomd;
	$description = 'Recherche de '.$nomd.' sur le site';
	include CHEMIN_VUE.'recherche.php';
}
