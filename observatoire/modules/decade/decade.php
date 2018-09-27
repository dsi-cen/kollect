<?php
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

//récupération décade
if(isset($_GET['jrs']) && !empty($_GET['jrs']) && !empty($_GET['mois'])) 
{
	$mem = 'oui';
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
/*elseif(isset($_GET['decade']) && !empty($_GET['decade']))
{
	$tmpdec = htmlspecialchars($_GET['decade']);
	$j = substr($tmpdec, -1);
	$str = strlen($tmpdec);
	$DMois = ($str == 2) ? substr($tmpdec, 0, 1) : substr($tmpdec, 0, 2);
	if($DMois == 'Ja') { $CMois = 'Janvier'; }
	elseif($DMois == 'Fe') { $CMois = 'Février'; }
	elseif($DMois == 'Ma') { $CMois = 'Mars'; }
	elseif($DMois == 'Av') { $CMois = 'Avril'; }
	elseif($DMois == 'M') { $CMois = 'Mai'; }
	elseif($DMois == 'Ju') { $CMois = 'Juin'; }
	elseif($DMois == 'Jl') { $CMois = 'Juillet'; }
	elseif($DMois == 'A') { $CMois = 'Août'; }
	elseif($DMois == 'S') { $CMois = 'Septembre'; }
	elseif($DMois == 'O') { $CMois = 'Octobre'; }
	elseif($DMois == 'N') { $CMois = 'Novembre'; }
	elseif($DMois == 'D') { $CMois = 'Décembre'; }
}*/
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

$titre = $nomd.' de '.$CMois.', '.$dec.' décade';
$description = 'Les '.$nomd.' '.$rjson_site['ad2'].' '.$rjson_site['lieu'].' durant la '.$dec.' décade de '.$CMois.'';

include CHEMIN_MODELE.'decade.php';

$choixlatin = (isset($_SESSION['latin'])) ? $_SESSION['latin'] : '';
if($rjson_obser['latin'] == 'oui' && $choixlatin == 'oui')
{
	$latin = 'nom';
}
elseif($rjson_obser['latin'] == 'oui' && ($choixlatin == 'defaut' || $choixlatin == ''))
{
	$latin = 'nom';
}
elseif($rjson_obser['latin'] == 'non' && $choixlatin == 'oui')
{
	$latin = 'nom';
}
elseif($rjson_obser['latin'] == 'non' || $choixlatin == 'non') 
{
	$latin = 'nomvern';
}
elseif($rjson_obser['latin'] == 'oui' && $choixlatin == 'non') 
{
	$latin = 'nomvern';
}

$liste = liste($nomvar,$decade,$latin);
if($liste[0] > 0)
{
	$listefam = listefam($nomvar,$decade);
	$listeobs = listeobs($nomvar,$decade,$latin);
	
	foreach($liste[1] as $n)
	{
		$tabfam[] = $n['famille'];
		$tabcdtmp[] = $n['cdref'];
	}
	$tabcdref = array_count_values($tabcdtmp);
	$stadetmp = null;
	$a = 1;
	foreach($liste[1] as $n)
	{
		if($tabcdref[$n['cdref']] > 1)
		{
			if($a == 1)
			{ 
				$stadetmp .= $n['stade']; 
				$b = $n['cdref'];
				$a++;
			}
			elseif(($a > 1 && $a < $tabcdref[$n['cdref']]) && ($b == $n['cdref'])) 
			{ 
				$stadetmp .= ', '.$n['stade'];
				$a++;
			}
			elseif(($a == $tabcdref[$n['cdref']]) && ($b == $n['cdref']))
			{
				$stadetmp .= ', '.$n['stade'];
				$taxon[] = ['nom'=>$n['nom'], 'nomvern'=>$n['nomvern'], 'stade'=>$stadetmp, 'famille'=>$n['famille'], 'cdref'=>$n['cdref']];
				$a = 1;
				$stadetmp = null;
			}		
		}
		else
		{
			$taxon[] = ['nom'=>$n['nom'], 'nomvern'=>$n['nomvern'], 'stade'=>$n['stade'], 'famille'=>$n['famille'], 'cdref'=>$n['cdref']];
		}
	}
	$nbsptmp = count($taxon);
	$nbsp = ($nbsptmp > 1) ? '<b>'.$nbsptmp.'</b> espèces ont été observées' : '<b>1</b> espèce a été observée';
}
else
{
	$nbsp = ': aucune espèce.';
}

include CHEMIN_VUE.'decade.php';