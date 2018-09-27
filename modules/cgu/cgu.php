<?php
$script = '<script src="dist/js/jquery.js" defer></script>
<script src="dist/js/bootstrap.min.js" defer></script>';
$css = '';

include CHEMIN_MODELE.'article.php';

$type = 'cu';
$article = rarticle($type);

$titre = (!empty($article['titre'])) ? $article['titre'] : 'Conditions d\'utilisation';
$description = (!empty($article['soustitre'])) ? $article['soustitre'] : $titre.' - sur '.$rjson_site['titre'];
//prevoir largeur dans champ article en admin
$testl = 2; //en disant 1 = container-fluid, 2 = container
$classcontainer = ($testl == 1) ? 'container-fluid' : 'container';
	
include CHEMIN_VUE.'article.php';
