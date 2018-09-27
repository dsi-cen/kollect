<?php
$titre = 'Validation - liste';
$description = 'Type de validation et processus de validation des espèces du site';
$script = '<script type="text/javascript" src="dist/js/jquery.js" defer></script>
<script src="dist/js/bootstrap.min.js" defer></script>
<script type="text/javascript" src="dist/js/jquery.dataTables.min.js" defer></script>
<script type="text/javascript" src="dist/js/datatables/dataTables.scroller.min.js" defer></script>
<script src="dist/js/valitype.js" defer></script>';
$css = '<link rel="stylesheet" type="text/css" href="dist/css/dataTables.bootstrap4.css">
<link rel="stylesheet" type="text/css" href="dist/css/scroller.bootstrap4.min.css">';

if(isset($_GET['d']))
{
	$obser = $_GET['d'];
}
else
{
	$obser = (!empty($_SESSION['obser'])) ? $_SESSION['obser'] : 'aucun';
}

include CHEMIN_VUE.'liste.php';	
