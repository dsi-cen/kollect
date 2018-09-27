<?php
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

if(isset($_GET['maille']))
{
	include CHEMIN_MODELE.'maille.php';
	$maille = htmlspecialchars($_GET['maille']);
		
	$titre = $maille.' Liste des '.$nomd;
	$description = 'Liste des espèces de '.$nomd.' de la maille 5 '.$maille;
	
	$droit = (isset($_SESSION['droits']) && $_SESSION['droits'] >= 1) ? 'oui' : 'non';
	
	$liste = liste5($maille,$droit,$nomvar);
	$nbtotal = nbespece($nomvar);
	$nbtaxon = $liste[0];
	$titrep = 'Liste des '.$nomd.' de la maille 5 '.$maille.' <small>('.$nbtaxon.' - '.round($nbtaxon/$nbtotal * 100,1).'%)</small>';
	$lien = '<a href="index.php?module=statut&amp;action=statut&amp;d='.$nomvar.'&amp;l935='.$maille.'">Liste statuts</a>';
		
	$listeobser = liste_observateur5($maille,$nomvar);
	$nblisteobser = count($listeobser);		
	
	if($liste[0] > 0)
	{
		$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
		$tabfam = compte_famille_maille5($maille,$droit,$nomvar);
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
		$nouv = nouvelle_espece_maille5($maille,$nomvar);
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