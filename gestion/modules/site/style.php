<?php
$titre = 'Style du site';
$description = 'Style du site';
$scripthaut = '<script type="text/javascript" src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

$json_style = file_get_contents('../json/style.json');
$rjson_style = json_decode($json_style, true);
$style = $rjson_style['choix'];

include CHEMIN_VUE.'style.php';