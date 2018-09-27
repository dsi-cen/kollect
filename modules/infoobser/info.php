<?php
$scripthaut = '<script src="dist/js/jquery.js"></script>'; 
$script = '<script src="dist/js/bootstrap.min.js" defer></script>
<script src="dist/js/highcharts.js" defer></script>
<script src="dist/js/modules/exportingoff.js" defer></script>';
$css = '';

if(isset($_GET['idobser'])) 
{
    include CHEMIN_MODELE.'infoobser.php';

    $idobser = htmlspecialchars($_GET['idobser']);
	
	if($idobser == 'na' && isset($_SESSION['idmembre']))
	{
		//si c'est un membre qui arrive via le lien sa page du menu
		$observateur = cherche_observateurmembre($_SESSION['idmembre']);
		$idobser = $observateur['idobser'];
		if($idobser == '') // si le membre n'est pas observateur... il a pas de page
		{
			header('location:index.php');
		}
	}
	elseif($idobser == 'na' && !isset($_SESSION['idmembre']))
	{
		header('location:index.php');//si il se déconnecte
	}
	else
	{
		$observateur = cherche_observateur($idobser);
	}
    	
    $titre = $observateur['prenom'].' '.$observateur['nom'];
    $description = 'Fiche de '.$titre.' du site '.$rjson_site['titre'];
	
	//récupération de l'avatar si existe
	$cheminavatar = 'photo/avatar/'.$observateur['prenom'].''.$observateur['idm'].'.jpg';
	$favatar = (file_exists($cheminavatar)) ? '<img src="'.$cheminavatar.'" width=36 height=36 alt="" class="rounded-circle"/>' : '<img src="photo/avatar/usera.jpg" width=36 height=36 alt="" class="img-circle"/>';	
			
	//nb observations
	/*$nbobs = nbobs_observa($idobser);
	$nbtotal = nbtotal();
	$nbtotalsp = nbtotal_sp();
	$nbphoto = nbphoto($idobser);
	$nbtaxon = nb_taxons($idobser);
	
	foreach($nbphoto as $n)
	{
		$tmpph[$n['observa']] = $n['nb'];
	}
	foreach($nbtaxon as $n)
	{
		$tmptaxon[$n['observatoire']] = $n['nb'];
	}
	foreach($nbobs as $n)
	{
		$tmpobs[$n['observa']] = $n['nb'];
	}
	foreach($nbtotalsp as $n)
	{
		$tmpnbttaxon[$n['observatoire']] = $n['nbsp'];
	}
	
	foreach($nbtotal as $t)
	{
		$lnbph = (isset($tmpph[$t['observa']])) ? $tmpph[$t['observa']] : 0;
		$lnbtaxon = (isset($tmptaxon[$t['observa']])) ? $tmptaxon[$t['observa']] : 0;
		$lnbobs = (isset($tmpobs[$t['observa']])) ? $tmpobs[$t['observa']] : 0;
		$pnb = round($lnbobs / $t['nb'] * 100,2);
		$pnbsp = round($lnbtaxon / $tmpnbttaxon[$t['observa']] * 100,2);
		$pnbph = ($t['nbphoto'] > 0) ? round($lnbph / $t['nbphoto'] * 100,2) : 0;
		$tabnb[] = ['observa'=>$t['observa'],'nb'=>$lnbobs,'nbsp'=>$lnbtaxon,'nbph'=>$lnbph,'pnb'=>$pnb,'pnbsp'=>$pnbsp,'pnbph'=>$pnbph];				
	}	
		
	$nbobs1 = 0; $nbsp = 0; $nbph = 0;
	foreach($rjson_site['observatoire'] as $n)
	{
		foreach($tabnb as $a)
		{
			if($a['observa'] == $n['nomvar'] && $a['nb'] > 0)
			{
				$tab[] = ['nom'=>$n['nom'],'nb'=>$a['nb'],'nbsp'=>$a['nbsp'],'nbph'=>$a['nbph'],'pnb'=>$a['pnb'],'pnbsp'=>$a['pnbsp'],'pnbph'=>$a['pnbph']];
				$nbobs1 = $nbobs1 + $a['nb'];
				$nbsp = $nbsp + $a['nbsp'];
				$nbph = $nbph + $a['nbph'];				
			}
		}
	}
	if(count($tab) > 1)
	{
		$nbt = 0; $nbspt = 0; $nbpht = 0;
		foreach($nbtotal as $n)
		{
			$nbt = $nbt + $n['nb'];
			$nbspt = $nbspt + $tmpnbttaxon[$n['observa']];
			$nbpht = $nbpht + $n['nbphoto'];
		}
		$pnbt = round($nbobs1 / $nbt * 100,2);
		$pnbspt = round($nbsp / $nbspt * 100,2);
		$pnbpht = round($nbph / $nbpht * 100,2);
	}*/
	
	//graph
	$annéeactuelle = date('Y');
	$graphobs = graphobs($idobser);
	foreach($graphobs as $n)
	{
		$tabannee[] = $n['annee'];
	}
	$anmin = min($tabannee);
	for($i=$anmin; $i <= $annéeactuelle; $i++) 
	{ 
		$annee[] = $i;	 
	} 
	$nbligne = (count($annee) > 25) ? 2 : 1;	
	
	$tabannee = array_flip($tabannee);
	$nbcumul = 0;
	foreach($annee as $a)
	{
		if(isset($tabannee[$a]))
		{
			foreach($graphobs as $n)
			{
				if($n['annee'] == $a)
				{
					$nb[] = $n['nb'];
					$obscumul[] = $nbcumul += $n['nb'];
				}			
			}
		}
		else
		{
			$nb[] = 0;
			$obscumul[] = $nbcumul += 0;
		}		
	}
	
	include CHEMIN_VUE.'info.php';	
}
?>