<?php
$titre = 'Gestion biblio';
$description = 'Gestion de la biblio';
$scripthaut = '<script type="text/javascript" src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

$json_biblio = file_get_contents('../json/biblio.json');
$rjson = json_decode($json_biblio, true);

include CHEMIN_VUE.'general.php';