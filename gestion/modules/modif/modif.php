<?php
$titre = 'Dernières modification';
$description = 'Liste des dernières modifications';
$script = '<script type="text/javascript" src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = ''; 

if ($_SESSION['droits'] == 4)
{
	include CHEMIN_MODELE.'modif.php';
	$liste = modif();
	include CHEMIN_VUE.'modif.php';
}
