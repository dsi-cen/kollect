<?php 
$titre = 'Liste des contributeurs';
$description = 'Liste des observateurs et photographes du site '.$rjson_site['titre'].'';
$script = '<script src="dist/js/jquery.js" defer></script>
<script src="dist/js/bootstrap.min.js" defer></script>';
$css = '';
include CHEMIN_MODELE.'observateurs.php';

$listeobser = liste_observateur();
$nbobser = $listeobser[0];

$listephoto = liste_photographe();
$nbphoto = $listephoto[0];

include CHEMIN_VUE.'observateurs.php';
?>