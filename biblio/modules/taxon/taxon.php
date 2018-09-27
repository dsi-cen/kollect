<?php
$titre = 'Recherche par espèce';
$description = 'Recherche de référence bibliographique par espèces';
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

include CHEMIN_MODELE.'recherche.php';

$lettre = recherche_taxon_latin();
$lettrefr = recherche_taxon_fr();

include CHEMIN_VUE.'taxon.php';