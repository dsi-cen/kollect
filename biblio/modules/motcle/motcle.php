<?php
$titre = 'Recherche par mot clés';
$description = 'Recherche de référence bibliographique par mot clés';
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

include CHEMIN_MODELE.'recherche.php';

$lettre = recherche_mot();

include CHEMIN_VUE.'motcle.php';