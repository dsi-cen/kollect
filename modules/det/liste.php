<?php
$titre = 'Détermination - liste';
$description = 'Liste des demandes de détermination d\'espèces';
$script = '<script type="text/javascript" src="dist/js/jquery.js" defer></script>
<script src="dist/js/bootstrap.min.js" defer></script>
<script type="text/javascript" src="dist/js/jquery.dataTables.min.js" defer></script>
<script type="text/javascript" src="dist/js/datatables/dataTables.scroller.min.js" defer></script>
<script src="dist/js/detliste.js?'.filemtime('dist/js/detliste.js').'" defer></script>';
$css = '<link rel="stylesheet" type="text/css" href="dist/css/dataTables.bootstrap4.css">
<link rel="stylesheet" type="text/css" href="dist/css/scroller.bootstrap4.min.css">';
if(isset($_GET['d'])) 
{
	$observa = htmlspecialchars($_GET['d']);
}
else
{
	$observa = '';
}
if(isset($_GET['f']))
{
	$f = htmlspecialchars($_GET['f']);
}	

include CHEMIN_VUE.'liste.php';	
