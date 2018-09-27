<?php
$titre = 'Gestion du site';
$description = 'Gestion du site';
$scripthaut = '<script type="text/javascript" src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>
<script type="text/javascript" src="dist/js/ckeditor/ckeditor.js" defer></script>';
$css = '';

$logosite = ($rjson_site['logo'] == 'non') ? '' : $rjson_site['logo'];
$adresse = (isset($rjson_site['adresse'])) ? $rjson_site['adresse'] :'';
$liensite = ($rjson_site['lien'] == 'non') ? '' : $rjson_site['lien'];
$idorg = (isset($rjson_site['orga'])) ? $rjson_site['orga']['id'] : '';
$org = (isset($rjson_site['orga'])) ? $rjson_site['orga']['nom'] : '';

include CHEMIN_VUE.'site.php';