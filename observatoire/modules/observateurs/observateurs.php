<?php 
$titre = 'Contributeurs - '.$nomd;
$description = 'Liste des observateurs et photographes du site '.$rjson_site['titre'].' - '.$nomd;
$script = '<script src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

include CHEMIN_MODELE.'observateurs.php';

$listeobser = liste_observateur($nomvar);
$nbobser = $listeobser[0];

$listephoto = liste_photographe($nomvar);
$nbphoto = $listephoto[0];

include CHEMIN_VUE.'observateurs.php';
?>