<?php
$titre = 'Aide prospection - '.$nomd;
$description = 'Aide prospection - '.$nomd.' '.$rjson_site['ad2'].' '.$rjson_site['lieu'];
$script = '<script src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>
<script src="../dist/js/highcharts.js" defer></script>
<script src="../dist/js/modules/map.js" defer></script>
<script src="../dist/js/leafletpj4.js" defer></script>
<script src="../dist/js/bootstrap-slider.min.js" defer></script>
<script src="../dist/js/jQuery.print.js" defer></script>
<script src="../dist/js/prospection.js" defer></script>';
$css = '<link rel="stylesheet" href="../dist/css/bootstrap-slider.min.css" />
<link rel="stylesheet" href="../dist/css/leaflet.css" />';

$json_emprise = file_get_contents('../emprise/emprise.json');
$emprise = json_decode($json_emprise, true);

$maxcolor = (isset($rjson_site['fiche']['cartebilancouleur'])) ? $rjson_site['fiche']['cartebilancouleur'] : '#3A9D23';
$choixcarte = ($emprise['utm'] == 'oui') ? 'utm' : 'l93';
$cartemaille = ($emprise['utm'] == 'oui') ? 'Carte maille UTM 10x10 km' : 'Carte Lambert93 10x10 km';
$titrecarte = ($emprise['utm'] == 'oui') ? 'Nombre d\'espèces par maille UTM' : 'Nombre d\'espèces par maille 10 x 10';

include CHEMIN_VUE.'prospection.php';