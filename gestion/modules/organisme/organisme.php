<?php
if (($_SESSION['droits'] >= 2) OR isset($_SESSION['virtuel']))
{
	$titre = 'Gestion des structures';
	$description = 'Gestion des structures';
	$scripthaut = '<script type="text/javascript" src="../dist/js/jquery.js"></script>';
	$script = '<script src="../dist/js/bootstrap.min.js" defer></script>
	<script type="text/javascript" src="../dist/js/jquery.dataTables.min.js" defer></script>
	<script type="text/javascript" src="../dist/js/datatables/dataTables.scroller.min.js" defer></script>
	<script type="text/javascript" src="../dist/js/datatables/dataTables.buttons.min.js" defer></script>
	<script type="text/javascript" src="../dist/js/datatables/jszip.min.js" defer></script>
	<script type="text/javascript" src="../dist/js/datatables/buttons.html5.min.js" defer></script>';
	$css = '<link rel="stylesheet" type="text/css" href="../dist/css/dataTables.bootstrap4.css">
	<link rel="stylesheet" type="text/css" href="../dist/css/scroller.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="../dist/css/buttons.bootstrap4.min.css">';
	
	include CHEMIN_VUE.'organisme.php';
}