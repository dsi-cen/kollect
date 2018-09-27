<?php
$titre = 'Gestion espèce';
$description = 'Gestion des espèces du site';
$script = '<script type="text/javascript" src="../dist/js/jquery.js"></script>
<script src="../dist/js/bootstrap.min.js" defer></script>
<script src="../dist/js/jquery-auto.js" defer></script>
<script src="../dist/js/jquery.glossarize.js" defer></script>
<script src="dist/js/taxon.js" defer></script>';
$css = '';

if ($_SESSION['droits'] >= 3)
{
	include CHEMIN_VUE.'taxon.php';	
}