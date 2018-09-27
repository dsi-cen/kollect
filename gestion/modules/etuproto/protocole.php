<?php
$titre = 'Protocoles';
$description = 'Configuration des protocoles du site';
$scripthaut = '<script type="text/javascript" src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

if($_SESSION['droits'] >= 3)
{
	include CHEMIN_MODELE.'etuproto.php';
		
	$protocole = protocole();
	foreach($protocole as $n)
	{
		$tabid[] = $n['idprotocole'];
	}
	$maxid = max($tabid) + 1;
	
	include CHEMIN_VUE.'protocole.php';	
}

