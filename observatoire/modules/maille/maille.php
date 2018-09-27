<?php
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

if(isset($_GET['maille']))
{
	include CHEMIN_MODELE.'maille.php';
	$maille = htmlspecialchars($_GET['maille']);
	
	$json_emprise = file_get_contents('../emprise/emprise.json');
	$emprise = json_decode($json_emprise, true);
	
	$titre = $maille.' Liste des '.$nomd;
	$description = 'Liste des espèces de '.$nomd.' de la maille 10 '.$maille;
	
	$droit = (isset($_SESSION['droits']) && $_SESSION['droits'] >= 1) ? 'oui' : 'non';
	
	$liste = ($emprise['utm'] == 'oui') ? listeutm($maille,$droit,$nomvar) : liste($maille,$droit,$nomvar);
	$nbtotal = nbespece($nomvar);
	$nbtaxon = $liste[0];
	$titrep = 'Liste des '.$nomd.' de la maille 10 '.$maille.' <small>('.$nbtaxon.' - '.round($nbtaxon/$nbtotal * 100,1).'%)</small>';
	if($emprise['utm'] == 'oui')
	{
		$lien = '<a href="index.php?module=statut&amp;action=statut&amp;d='.$nomvar.'&amp;utm='.$maille.'">Liste statuts</a>';
	}
	else
	{
		$lien = '<a href="index.php?module=statut&amp;action=statut&amp;d='.$nomvar.'&amp;l93='.$maille.'">Liste statuts</a>';
	}
	
	$listeobser = ($emprise['utm'] == 'oui') ? liste_observateur_utm($maille,$nomvar) : liste_observateur($maille,$nomvar);
	$nblisteobser = count($listeobser);		
	
	if($liste[0] > 0)
	{
		$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
		$tabfam = ($emprise['utm'] == 'oui') ? compte_famille_utm($maille,$droit,$nomvar) : compte_famille($maille,$droit,$nomvar);
		foreach($tabfam as $n)
		{
			$fam[] = ['famille'=>$n['cdnom'], 'nom'=>$n['famille'], 'nb'=>$n['nb']]; 
		}
		if($rjson_obser['latin'] == 'oui' && $latin == 'oui') { $afflatin = 'oui'; }
		elseif($rjson_obser['latin'] == 'oui' && ($latin == 'defaut' || $latin == '')) { $afflatin = 'oui'; }
		elseif($rjson_obser['latin'] == 'non' && $latin == 'oui') { $afflatin = 'oui'; }
		elseif($rjson_obser['latin'] == 'non' || $latin == 'non') { $afflatin = 'non'; } 
		elseif($rjson_obser['latin'] == 'oui' && $latin == 'non') { $afflatin = 'non'; }
		
		foreach($liste[1] as $n)
		{
			if($afflatin == 'oui')
			{	
				$afflatintab = '<a href="index.php?module=fiche&amp;action=fiche&amp;d='.$nomvar.'&amp;id='.$n['cdnom'].'"><i>'.$n['nom'].'</i></a>';
			}
			else
			{
				if($n['nomvern'] != '')
				{
					$afflatintab = '<a href="index.php?module=fiche&amp;action=fiche&amp;d='.$nomvar.'&amp;id='.$n['cdnom'].'">'.$n['nomvern'].' (<i>'.$n['nom'].'</i>)</a>';
				}
				else
				{
					$afflatintab = '<a href="index.php?module=fiche&amp;action=fiche&amp;d='.$nomvar.'&amp;id='.$n['cdnom'].'"><i>'.$n['nom'].'</i></a>';
				}											
			}					
			$taxon[] = ['taxon'=>$afflatintab, 'famille'=>$n['famille']];				
		}
		$nouv = ($emprise['utm'] == 'oui') ? nouvelle_espece_utm($maille,$nomvar) : nouvelle_espece($maille,$nomvar);
		$anneeencours = date('Y');
		foreach($nouv as $n)
		{
			if($n['annee'] == $anneeencours)
			{
				if($afflatin == 'oui')
				{	
					$afflatintabn = '<a href="index.php?module=fiche&amp;action=fiche&amp;d='.$nomvar.'&amp;id='.$n['cdnom'].'"><i>'.$n['nom'].'</i></a>';
				}
				else
				{
					if($n['nomvern'] != '')
					{
						$afflatintabn = '<a href="index.php?module=fiche&amp;action=fiche&amp;d='.$nomvar.'&amp;id='.$n['cdnom'].'">'.$n['nomvern'].' (<i>'.$n['nom'].'</i>)</a>';
					}
					else
					{
						$afflatintabn = '<a href="index.php?module=fiche&amp;action=fiche&amp;d='.$nomvar.'&amp;id='.$n['cdnom'].'"><i>'.$n['nom'].'</i></a>';
					}											
				}				
				$new[] = ['taxon'=>$afflatintabn];
			} 
		}
		if(isset($new))
		{
			$nbnew = count($new);
			$libnouv = ($nbnew > 1) ? 'Nouvelles espèces en '.$anneeencours.' ('.$nbnew.')' : 'Une nouvelle espèce en '.$anneeencours;
		}		
	}
	include CHEMIN_VUE.'maille.php';
}