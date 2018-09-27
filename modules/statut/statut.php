<?php
$scripthaut = '<script src="dist/js/jquery.js"></script>';
$script = '<script src="dist/js/bootstrap.min.js" defer></script>
<script type="text/javascript" src="dist/js/jquery.dataTables.min.js" defer></script>
<script type="text/javascript" src="dist/js/datatables/dataTables.buttons.min.js" defer></script>
<script type="text/javascript" src="dist/js/datatables/jszip.min.js" defer></script>
<script type="text/javascript" src="dist/js/datatables/buttons.html5.min.js" defer></script>';
$css = '<link rel="stylesheet" type="text/css" href="dist/css/dataTables.bootstrap4.css">
<link rel="stylesheet" type="text/css" href="dist/css/buttons.bootstrap4.min.css">';

if(isset($_GET['codecom']) || isset($_GET['iddep']) || isset($_GET['l93']) || isset($_GET['l935']) || isset($_GET['utm']))
{
	include CHEMIN_MODELE.'statut.php';
	
	$droit = (isset($_SESSION['droits']) && $_SESSION['droits'] >= 1) ? 'oui' : 'non';
	
	if(isset($_GET['codecom']))
	{
		$codecom = htmlspecialchars($_GET['codecom']);
		$com = cherche_commune($codecom);
		$titre = $com['commune'].' Statut des espèces';
		$description = 'Liste et statut des espèces de la commune de '.$com['commune'];
		$titrep = 'Statuts des espèces de la commune de '.$com['commune'];
		$lien = '<a href="index.php?module=commune&amp;action=commune&amp;codecom='.$codecom.'">'.$com['commune'].'</a>';
		$liste = liste_statut_com($codecom,$droit);		
	}	
	elseif(isset($_GET['iddep']))
	{
		$iddep = htmlspecialchars($_GET['iddep']);
		$dep = cherche_departement($iddep);	
		$titre = $dep['departement'].' Statut des espèces';
		$description = 'Liste et statut des espèces du département '.$dep['departement'];
		$titrep = 'Statuts des espèces du département '.$dep['departement'];
		$lien = '<a href="index.php?module=depart&amp;action=depart&amp;iddep='.$iddep.'">'.$dep['departement'].'</a>';
		$liste = liste_statut_dep($iddep);
	}
	elseif(isset($_GET['l93']))
	{
		$maille = htmlspecialchars($_GET['l93']);
		$titre = $maille.' Statut des espèces';
		$description = 'Liste et statut des espèces de la maille 10 '.$maille;
		$titrep = 'Statuts des espèces de la maille 10 '.$maille;
		$lien = '<a href="index.php?module=maille&amp;action=maille&amp;maille='.$maille.'">'.$maille.'</a>';
		$liste = liste_statut_l93($maille,$droit);
	}
	elseif(isset($_GET['l935']))
	{
		$maille = htmlspecialchars($_GET['l935']);
		$titre = $maille.' Statut des espèces';
		$description = 'Liste et statut des espèces de la maille 5 '.$maille;
		$titrep = 'Statuts des espèces de la maille 5 '.$maille;
		$lien = '<a href="index.php?module=maille&amp;action=maille5&amp;maille='.$maille.'">'.$maille.'</a>';
		$liste = liste_statut_l935($maille,$droit);
	}
	elseif(isset($_GET['utm']))
	{
		$maille = htmlspecialchars($_GET['utm']);
		$titre = $maille.' Statut des espèces';
		$description = 'Liste et statut des espèces de la maille 10 '.$maille;
		$titrep = 'Statuts des espèces de la maille 10 '.$maille;
		$lien = '<a href="index.php?module=maille&amp;action=maille&amp;maille='.$maille.'">'.$maille.'</a>';
		$liste = liste_statut_utm($maille,$droit);
	}
		
	if($liste != false)
	{
		$json_emprise = file_get_contents('emprise/emprise.json');
		$emprise = json_decode($json_emprise, true);
		
		if(isset($_GET['codecom']) && ($emprise['emprise'] == 'dep' || $emprise['emprise'] == 'reg'))
		{
			$statdep = 'oui';
			$dep = cherche_departement_dep($codecom);			
		}
		
		foreach($menuobservatoire as $n)
		{
			$nomobser[$n['var']] = $n['nom'];
		}
		if(isset($_GET['codecom'])) { $statut = statut_commune($codecom); }
		elseif(isset($_GET['iddep'])) { $statut = statut_dep($iddep); }
		elseif(isset($_GET['l93'])) { $statut = statut_l93($maille); }
		elseif(isset($_GET['l935'])) { $statut = statut_l935($maille); }
		elseif(isset($_GET['utm'])) { $statut = statut_utm($maille); }
		
		foreach($statut as $n)
		{
			if($emprise['emprise'] != 'fr')
			{
				if($n['type'] == 'Z') { $znieff[] = $n['cdref']; }	
			}				
			if($n['type'] == 'DH') { $dh[] = $n['cdref']; }	
			if($n['type'] == 'PN') { $pn[] = $n['cdref']; }
			if($emprise['emprise'] != 'fr')
			{
				if($n['type'] == 'LRR') { $lr[$n['cdref']] = $n['lr']; }
			}
			else
			{
				if($n['type'] == 'LRE') { $lre[$n['cdref']] = $n['lr']; }
				if($n['type'] == 'LRF') { $lrf[$n['cdref']] = $n['lr']; }
			}	
		}
		$znieff = (isset($znieff)) ? array_flip($znieff) : '';
		$dh = (isset($dh)) ? array_flip($dh) : '';
		$pn = (isset($pn)) ? array_flip($pn) : '';
				
		foreach($liste as $n)
		{
			$pourcent = round($n['nb']/$n['nbt'] * 100,1);
			if($emprise['emprise'] != 'fr') { $lznieff = (isset($znieff[$n['cdref']])) ? 'Oui' : ''; }			
			$ldh = (isset($dh[$n['cdref']])) ? 'Oui' : '';
			$lpn = (isset($pn[$n['cdref']])) ? 'Oui' : '';
			if($emprise['emprise'] != 'fr')
			{
				$llr = (isset($lr[$n['cdref']])) ? $lr[$n['cdref']] : '';
			}
			else
			{
				$llre = (isset($lre[$n['cdref']])) ? $lre[$n['cdref']] : '';
				$llrf = (isset($lrf[$n['cdref']])) ? $lrf[$n['cdref']] : '';
			}
			$ico = (isset($nomobser[$n['observa']])) ? $nomobser[$n['observa']] : '';
			if($emprise['emprise'] != 'fr')
			{
				$tab[] = ['icon'=>$ico, 'cdref'=>$n['cdref'], 'nom'=>$n['nom'], 'observa'=>$n['observa'], 'nomvern'=>$n['nomvern'], 'ir'=>$n['ir'], 'nb'=>$n['nb'], 'pourcent'=>$pourcent, 'znieff'=>$lznieff, 'dh'=>$ldh, 'pn'=>$lpn, 'lr'=>$llr];
			}
			else
			{
				$tab[] = ['icon'=>$ico, 'cdref'=>$n['cdref'], 'nom'=>$n['nom'], 'observa'=>$n['observa'], 'nomvern'=>$n['nomvern'], 'ir'=>$n['ir'], 'nb'=>$n['nb'], 'pourcent'=>$pourcent, 'dh'=>$ldh, 'pn'=>$lpn, 'lre'=>$llre, 'lrf'=>$llrf];
			}
		}
	}

	include CHEMIN_VUE.'statut.php';
}