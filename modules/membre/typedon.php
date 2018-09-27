<?php
if (isset($_SESSION['prenom']))
{
	$titre = 'Type données de '.$_SESSION['prenom'].'';
	$description = 'Type de données et paramètres de '.$_SESSION['prenom'].' '.$_SESSION['nom'].'.';
	$scripthaut = '<script src="dist/js/jquery.js"></script>';
	$script = '<script src="dist/js/bootstrap.min.js" defer></script>';
	$css = '';
		
	include CHEMIN_MODELE.'membre.php';
	
	$idm = $_SESSION['idmembre'];
	
	$liste = typedon($idm);
	$nbpu = 0; $nbac = 0; $nbpr = 0;
	if(count($liste) > 0)
	{	
		foreach($liste as $n)
		{
			if($n['typedon'] == 'Pu')
			{
				$nbpu += $n['nb'];
			}
			elseif($n['typedon'] == 'Ac')
			{
				$nbac += $n['nb'];
			}
			elseif($n['typedon'] == 'Pr')
			{
				$nbpr += $n['nb'];
			}
			$idobser = $n['idobser'];
		}
		$nbtotal = $nbpu + $nbac + $nbpr;
	}
	else
	{
		$idobser = ''; $nbtotal = 0; $nbpr = 0;
	}

	if($nbpr > 0)
	{
		$nb0 = 0; $nb1 = 0; $nb2 = 0; $nb3 = 0;
		foreach($liste as $n)
		{
			if($n['typedon'] == 'Pr')
			{
				if($n['floutage'] == 0) { $nb0 += $n['nb']; }
				elseif($n['floutage'] == 1) { $nb1 += $n['nb']; }
				elseif($n['floutage'] == 2) { $nb2 += $n['nb']; }
				elseif($n['floutage'] == 3) { $nb3 += $n['nb']; }
			}
		}
		$nbtotalpr = $nb0 + $nb1 + $nb2 + $nb3;
	}
	
	include CHEMIN_VUE.'typedon.php';
}
else
{
	header('location:index.php');
}