<?php
$titre = 'Doublon fiche';
$description = 'Gestion des doublon de fiches';
$scripthaut = '<script type="text/javascript" src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

include CHEMIN_MODELE.'doublon.php';

$dble = doublon_fiche();

foreach($dble as $n)
{
	$tab[] = [$n['commune'],$n['site'],$n['date1'],$n['observateur']];	
}
$tabtmp = array_map( 'serialize' , $tab );
$tabtmp = array_unique($tabtmp);
$tab = array_map( 'unserialize' , $tabtmp );

include CHEMIN_VUE.'dblefiche.php';