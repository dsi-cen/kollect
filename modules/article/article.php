<?php
$titre = '';
$description = '';
$script = '<script src="dist/js/jquery.js" defer></script>
<script src="dist/js/bootstrap.min.js" defer></script>';
$css = '';

if(isset($_GET['id']))
{
	include CHEMIN_MODELE.'article.php';
		
	$id = htmlspecialchars($_GET['id']);
	//prevoir largeur dans champ article en admin
	$testl = 2; //en disant 1 = container-fluid, 2 = container
	$classcontainer = ($testl == 1) ? 'container-fluid' : 'container';
	
	$article = article($id);
	$titre = $article['titre'];
	$description = (!empty($article['soustitre'])) ? $article['soustitre'] : $titre.' - sur '.$rjson_site['titre'];
} 
else
{
	header('location:index.php');
}
include CHEMIN_VUE.'article.php';
