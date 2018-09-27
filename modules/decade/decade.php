<?php
$scripthaut = '<script src="dist/js/jquery.js"></script>';
$script = '<script src="dist/js/bootstrap.min.js" defer></script>';
$css = '';

//récupération décade
if(isset($_GET['jrs']) && !empty($_GET['jrs']) && !empty($_GET['mois']))
{
	$DMois = htmlspecialchars($_GET['mois']);
	$j = htmlspecialchars($_GET['jrs']);
	if($DMois == 'Ja') { $CMois = 'Janvier'; $m = 1; } 
	elseif($DMois == 'Fe') { $CMois = 'Février'; $m = 2; }
	elseif($DMois == 'Ma') { $CMois = 'Mars'; $m = 3; }
	elseif($DMois == 'Av') { $CMois = 'Avril'; $m = 4; }
	elseif($DMois == 'M') { $CMois = 'Mai'; $m = 5; }
	elseif($DMois == 'Ju') { $CMois = 'Juin'; $m = 6; }
	elseif($DMois == 'Jl') { $CMois = 'Juillet'; $m = 7; }
	elseif($DMois == 'A') { $CMois = 'Août'; $m = 8; }
	elseif($DMois == 'S') { $CMois = 'Septembre'; $m = 9; }
	elseif($DMois == 'O') { $CMois = 'Octobre'; $m = 10; }
	elseif($DMois == 'N') { $CMois = 'Novembre'; $m = 11; }
	elseif($DMois == 'D') { $CMois = 'Décembre'; $m = 12; }
	unset($_GET['jrs']);
	unset($_GET['mois']);
	$a = date('Y');
	$date = $j.'-'.$m.'-'.$a;
}
else
{
	$date = date('d-m-Y');
	list($j,$m,$a) = explode("-",$date);
	switch($m)
	{
		case 1:$DMois = 'Ja'; $CMois = 'Janvier'; break;
		case 2:$DMois = 'Fe'; $CMois = 'Février'; break;
		case 3:$DMois = 'Ma'; $CMois = 'Mars'; break;
		case 4:$DMois = 'Av'; $CMois = 'Avril'; break;
		case 5:$DMois = 'M'; $CMois = 'Mai'; break;
		case 6:$DMois = 'Ju'; $CMois = 'Juin'; break;
		case 7:$DMois = 'Jl'; $CMois = 'Juillet'; break;
		case 8:$DMois = 'A'; $CMois = 'Août'; break;
		case 9:$DMois = 'S'; $CMois = 'Septembre'; break;
		case 10:$DMois = 'O'; $CMois = 'Octobre'; break;
		case 11:$DMois = 'N'; $CMois = 'Novembre'; break;
		case 12:$DMois = 'D'; $CMois = 'Décembre'; break;
	}
}

if($j >= 1 && $j <= 10) { $Djrs = '1'; $dec = 'première'; $dec1 = 'Du 1er au 10'; }
elseif($j >= 11 && $j <= 20) { $Djrs = '2'; $dec = 'deuxième'; $dec1 = 'Du 11 au 20'; }
elseif($j >= 21 && $j <= 31)
{ 
	$datetime1 = new DateTime($date);
	$dernierjrs = $datetime1->format('t');
	$Djrs = '3'; $dec = 'troisième'; $dec1 = 'Du 21 au '.$dernierjrs; 
}
$decade = $DMois . $Djrs;

$titre = $CMois.', '.$dec.' décade';
$description = 'Espèce observées '.$rjson_site['ad2'].' '.$rjson_site['lieu'].' durant la '.$dec.' décade de '.$CMois.'';

include CHEMIN_MODELE.'decade.php';

$latin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';

$liste = liste($decade);
if($liste[0] > 0)
{
	$nbsp = ($liste[0] > 1) ? '<b>'.$liste[0].'</b> espèces ont été observées' : '<b>1</b> espèce a été observée';
	$listeobs = listeobs($decade);
	
	foreach($liste[1] as $n)
	{
		$tabobserva[] = $n['observa'];
	}
	$tabobserva = array_unique($tabobserva);
	$tabobserva = array_flip($tabobserva);
	foreach($rjson_site['observatoire'] as $n)
	{
		if(isset($tabobserva[$n['nomvar']]))
		{
			$observa[] = ['observa'=>$n['nomvar'], 'nom'=>$n['nom']];
		}
	}	
	foreach($liste[1] as $n)
	{
		foreach($rjson_site['observatoire'] as $d)
		{
			if(isset($tabobserva[$d['nomvar']]) && ($d['nomvar'] == $n['observa']))
			{
				if($d['latin'] == 'oui' && $latin == 'oui') { $afflatin = 'oui'; }
				elseif($d['latin'] == 'oui' && ($latin == 'defaut' || $latin == '')) { $afflatin = 'oui'; }
				elseif($d['latin'] == 'non' && $latin == 'oui') { $afflatin = 'oui'; }
				elseif($d['latin'] == 'non' || $latin == 'non') { $afflatin = 'non'; }
				if ($afflatin == 'oui')
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
	foreach($listeobs as $n)
	{
		foreach($rjson_site['observatoire'] as $d)
		{
			if(isset($tabobserva[$d['nomvar']]) && ($d['nomvar'] == $n['observa']))
			{
				$taxonobs[] = ['nom'=>$n['nom'], 'nomvern'=>$n['nomvern'], 'cdnom'=>$n['cdnom'], 'icon'=>$d['icon'], 'observa'=>$n['observa'], 'nb'=>$n['nb']];
			}
		}
	}
}
else
{
	$nbsp = ': aucune espèce.';
}

include CHEMIN_VUE.'decade.php';