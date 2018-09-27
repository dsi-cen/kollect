<?php
$titre = 'Recherche par auteur';
$description = 'Recherche de référence bibliographique par auteur';
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

include CHEMIN_MODELE.'recherche.php';

$lettre = recherche_auteur();

$tabaut = [1=>['photo'=>'auteur1','nom'=>'Maurice Sand'],2=>['photo'=>'auteur2','nom'=>'Raymond Rollinat']];
$rand = array_rand($tabaut, 1);

include CHEMIN_VUE.'auteur.php';