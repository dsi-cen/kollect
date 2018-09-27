<?php
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>
<script type="text/javascript" src="../dist/js/jquery.dataTables.min.js" defer></script>
<script type="text/javascript" src="../dist/js/datatables/dataTables.scroller.min.js" defer></script>';
$css = '<link rel="stylesheet" type="text/css" href="../dist/css/dataTables.bootstrap4.css">
<link rel="stylesheet" type="text/css" href="../dist/css/scroller.bootstrap4.min.css">';
$titre = 'A retrouver - '.$nomd;
$description = 'Liste des espèces à retrouver de '.$nomd.' '.$rjson_site['ad1'].' '.$rjson_site['lieu'].'';

//include CHEMIN_MODELE.'nouveau.php';

if(isset($rjson_site['fiche']['classefiche'])) 
{
	$tmp = $rjson_site['fiche']['classefiche'];
	array_pop($tmp);
	foreach($tmp as $n)
	{
		if($n['classe'] != 'classe1')
		{
			$taban[] = $n['annee'];			
		}				
	}	
}


include CHEMIN_VUE.'atrouver.php';