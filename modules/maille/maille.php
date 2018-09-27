<?php
$scripthaut = '<script src="dist/js/jquery.js"></script>';
$script = '<script src="dist/js/bootstrap.min.js" defer></script>';
$css = '';

if(isset($_GET['maille']))
{
	include CHEMIN_MODELE.'maille.php';
	$maille = htmlspecialchars($_GET['maille']);
	
	$json_emprise = file_get_contents('emprise/emprise.json');
	$emprise = json_decode($json_emprise, true);
	
	$titre = $maille.' Liste des espèces';
	$description = 'Liste des espèces de la maille 10 '.$maille;
	
	$droit = (isset($_SESSION['droits']) && $_SESSION['droits'] >= 1) ? 'oui' : 'non';
	
	$liste = ($emprise['utm'] == 'oui') ? listeutm($maille,$droit) : liste($maille,$droit);
	$nbtotal = nbespece();
	$nbtaxon = $liste[0];
	$titrep = 'Liste des espèces de la maille 10 '.$maille.' <small>('.$nbtaxon.' - '.round($nbtaxon/$nbtotal * 100,1).'%)</small>';
	if($emprise['utm'] == 'oui')
	{
		$lien = '<a href="index.php?module=statut&amp;action=statut&amp;utm='.$maille.'">Liste statuts</a>';
	}
	else
	{
		$lien = '<a href="index.php?module=statut&amp;action=statut&amp;l93='.$maille.'">Liste statuts</a>';
	}
	
	$listeobser = ($emprise['utm'] == 'oui') ? liste_observateur_utm($maille) : liste_observateur($maille);
	$nblisteobser = count($listeobser);		
	
	if($liste[0] > 0)
	{
		$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
		$tabobserva = ($emprise['utm'] == 'oui') ? compteobserva_utm($maille,$droit) : compteobserva($maille,$droit);
		foreach($rjson_site['observatoire'] as $n)
		{
			foreach($tabobserva as $o)
			{
				if($o['observa'] == $n['nomvar'])
				{
					$observa[] = ['observa'=>$n['nomvar'], 'nom'=>$n['nom'], 'nb'=>$o['nb']];
				}
			}
		}
		foreach($liste[1] as $n)
		{
			foreach($rjson_site['observatoire'] as $d)
			{
				if($d['nomvar'] == $n['observa'])
				{
					if($d['latin'] == 'oui' && $latin == 'oui') { $afflatin = 'oui'; }
					elseif($d['latin'] == 'oui' && ($latin == 'defaut' || $latin == '')) { $afflatin = 'oui'; }
					elseif($d['latin'] == 'non' && $latin == 'oui') { $afflatin = 'oui'; }
					elseif($d['latin'] == 'non' || $latin == 'non') { $afflatin = 'non'; }
					
					if($afflatin == 'oui')
					{	
						$afflatintab = '<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdnom'].'"><i>'.$n['nom'].'</i></a>';
					}
					else
					{
						if($n['nomvern'] != '')
						{
							$afflatintab = '<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdnom'].'">'.$n['nomvern'].' (<i>'.$n['nom'].'</i>)</a>';
						}
						else
						{
							$afflatintab = '<a href="observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$d['nomvar'].'&amp;id='.$n['cdnom'].'"><i>'.$n['nom'].'</i></a>';
						}											
					}					
					$taxon[] = ['taxon'=>$afflatintab, 'observa'=>$n['observa']];						
				}
			}				
		}			
	}
	include CHEMIN_VUE.'maille.php';
}