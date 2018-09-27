<?php
$titre = 'Utilisateur';
$description = 'Utilisateur actuel';
$script = '<script type="text/javascript" src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = '';

include CHEMIN_MODELE.'accueil.php';

$utilisateur = utilisateur();

include CHEMIN_VUE.'utilisateur.php';