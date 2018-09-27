<?php
$scripthaut = '<script src="dist/js/jquery.js"></script>';
$script = '<script src="dist/js/bootstrap.min.js" defer></script>';
$css = '';

if(isset($_GET['codecom']))
{
	include CHEMIN_MODELE.'commune.php';
	$codecom = htmlspecialchars($_GET['codecom']);
	$obser = (isset($_GET['d'])) ? $_GET['d'] : '';
	
	$com = cherche_commune($codecom);
	
	if($obser != '')
	{
		$titre = 'aaa';
		$description = '';
		
	}
	else
	{
		$titre = $com['commune'].' Liste des espèces';
		$description = 'Liste des espèces de la commune de '.$com['commune'];
		
		$droit = (isset($_SESSION['droits']) && $_SESSION['droits'] >= 1) ? 'oui' : 'non';
		
		$liste = liste($codecom,$droit);
		$nbtotal = nbespece();
		$nbtaxon = $liste[0];
		$titrep = 'Liste des espèces de '.$com['commune'].' <small>('.$nbtaxon.' - '.round($nbtaxon/$nbtotal * 100,1).'%)</small>';
		
		$listeobser = liste_observateur($codecom);
		$nblisteobser = count($listeobser);		
		
		if($liste[0] > 0)
		{
			$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
			$tabobserva = compteobserva($codecom,$droit);
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
	}

	include CHEMIN_VUE.'commune.php';
}