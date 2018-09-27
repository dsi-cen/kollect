<?php
$titre = 'Validation - Info';
$description = 'Information sur le processus de validation des données du site';
$script = '<script type="text/javascript" src="dist/js/jquery.js" defer></script>
<script src="dist/js/bootstrap.min.js" defer></script>';
$css = '';

include CHEMIN_MODELE.'validation.php';
	
$listev = validateur();
if(count($listev) > 0)
{
	foreach($listev as $n)
	{
		$disc = substr($n['discipline'], 0, -2);
		$tab[] = explode(", ", $disc);
	}
	foreach($tab as $cle => $e)
	{
		foreach($e as $n)
		{
			$tab1[] = $n;			
		}
	}
	$tab = array_unique($tab1);
	foreach($tab as $n)
	{
		$r = validateurnom($n);
		foreach($r as $p)
		{
			$tmp[] = $p['prenom'].' '.$p['nom'];
		}
		$t = implode(", ", $tmp);
		$tmp = null;
		$tab3[] = ['dis'=>$n,'nom'=>$t];
	}
	$tab = array_flip($tab);
	foreach($rjson_site['observatoire'] as $n)
	{
		if(isset($tab[$n['nomvar']]))
		{
			foreach($tab3 as $e)
			{
				if($n['nomvar'] == $e['dis'])
				{
					$validateur[] = ['observa'=>$n['nom'],'nom'=>$e['nom'],'icon'=>$n['icon']];				
				}				
			}
		}
		else
		{
			$com = 'Aucun validateur pour le moment';
			$validateur[] = ['observa'=>$n['nom'],'nom'=>$com,'icon'=>$n['icon']];	
		}
	}	
}	
include CHEMIN_VUE.'validation.php';	
