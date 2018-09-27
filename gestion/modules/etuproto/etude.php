<?php
$titre = 'Etudes';
$description = 'Configuration des études du site';
$scripthaut = '<script type="text/javascript" src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

if($_SESSION['droits'] >= 3)
{
	include CHEMIN_MODELE.'etuproto.php';
		
	$etude = etude();
	if(count($etude) > 0)
	{
		foreach($etude as $n)
		{
			$tabid[] = $n['idetude'];
		}
		$maxid = max($tabid) + 1;
	}
	else
	{
		$maxid = 1;
	}
	
	include CHEMIN_VUE.'etude.php';	
}

