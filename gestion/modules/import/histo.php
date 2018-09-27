<?php
$titre = 'Historique Import';
$description = 'Historique des import de données';
$scripthaut = '<script type="text/javascript" src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

include CHEMIN_MODELE.'import.php';

$liste = liste_import();

$nbimport = count($liste);
if($nbimport > 0)
{
	if($nbimport == 1)
	{
		$libimport = $nbimport.' import réalisé';
	}
	else
	{
		$libimport = $nbimport.' imports réalisés';
	}
}
	
include CHEMIN_VUE.'histo.php';	
