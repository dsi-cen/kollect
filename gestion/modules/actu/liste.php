<?php
$titre = 'Gestion actualités';
$description = 'Gestion des actualités';
$scripthaut = '<script type="text/javascript" src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>
<script type="text/javascript" src="../dist/js/jquery.dataTables.min.js" defer></script>';
$css = '<link rel="stylesheet" type="text/css" href="../dist/css/dataTables.bootstrap4.css">';

include CHEMIN_MODELE.'actu.php';

$liste = listeactu();

include CHEMIN_VUE.'liste.php';
