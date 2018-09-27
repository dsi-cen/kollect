<?php
$titre = 'Recherche par publication';
$description = 'Recherche de référence bibliographique par publication';
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

include CHEMIN_MODELE.'recherche.php';

$lettre = recherche_publi();

include CHEMIN_VUE.'publi.php';