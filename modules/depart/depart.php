<?php
$scripthaut = '<script src="dist/js/jquery.js"></script>';
$script = '<script src="dist/js/bootstrap.min.js" defer></script>';
$css = '';

if(isset($_GET['iddep']))
{
	include CHEMIN_MODELE.'commune.php';
	$iddep = htmlspecialchars($_GET['iddep']);
		
	$dep = cherche_departement($iddep);
	
	$titre = $dep['departement'].' Liste des espèces';
	$description = 'Liste des espèces du département '.$dep['departement'];
	
	$liste = listedepart($iddep);
	$nbtotal = nbespece();
	$nbtaxon = $liste[0];
	$titrep = 'Liste des espèces de '.$dep['departement'].' <small>('.$nbtaxon.' - '.round($nbtaxon/$nbtotal * 100,1).'%)</small>';
	
	$listeobser = liste_observateur_dep($iddep);
	$nblisteobser = count($listeobser);		
	
	if($liste[0] > 0)
	{
		$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
		$tabobserva = compteobservadep($iddep);
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

	include CHEMIN_VUE.'depart.php';
}