<?php
$scripthaut = '<script src="../dist/js/jquery.js"></script>';
$script = '<script src="../dist/js/bootstrap.min.js" defer></script>
<script type="text/javascript" src="../dist/js/jquery.dataTables.min.js" defer></script>
<script type="text/javascript" src="../dist/js/datatables/dataTables.scroller.min.js" defer></script>
<script type="text/javascript" src="../dist/js/datatables/dataTables.buttons.min.js" defer></script>
<script type="text/javascript" src="../dist/js/datatables/jszip.min.js" defer></script>
<script type="text/javascript" src="../dist/js/datatables/buttons.html5.min.js" defer></script>';
$css = '<link rel="stylesheet" type="text/css" href="../dist/css/dataTables.bootstrap4.css">
<link rel="stylesheet" type="text/css" href="../dist/css/scroller.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="../dist/css/buttons.bootstrap4.min.css">';
$titre = 'Téléchargement - '.$nomd;
$description = 'Téléchargement de la liste des '.$nomd.' '.$rjson_site['ad1'].' '.$rjson_site['lieu'].'';



include CHEMIN_VUE.'tele.php';