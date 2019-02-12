<?php
$titre = 'Liste des stations';
$description = 'Liste des stations';
$script = '<script src="dist/js/jquery.js" defer></script>
           <script src="dist/js/bootstrap.min.js" defer></script>
           <script src="dist/js/leafletpj4.js"></script>
           <script src="dist/js/listestations.js" defer></script>';

$css = '<link rel="stylesheet" href="dist/css/leaflet.css" />
		<link rel="stylesheet" href="dist/css/jquery-ui.css" />';

$sansheader = 'oui';
$pasdebdp = 'oui';
$titrep = 'Liste des stations';

include CHEMIN_MODELE . 'liste.php';
include CHEMIN_VUE . 'liste.php';
