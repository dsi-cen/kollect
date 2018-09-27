<?php
$titre = 'Gestion article';
$description = 'Gestion des articles du site';
$script = '<script type="text/javascript" src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>
<script type="text/javascript" src="dist/js/ckeditor/ckeditor.js" defer></script>
<script type="text/javascript" src="dist/js/article.js" defer></script>';
$css = '';

$choix1[] = ['val'=>'ml','nom'=>'Mention légale'];
$choix1[] = ['val'=>'cu','nom'=>'Conditions d\'utilisation'];
$choix1[] = ['val'=>'aidesaisie','nom'=>'Aide saisie'];
if(isset($rjson_site['observatoire']))
{
	foreach($rjson_site['observatoire'] as $n)
	{
		$choix1[] = array("val"=>'ac'.$n['nomvar'],"nom"=>'Accueil - '.$n['nom']);
	}
}
if($rjson_site['biblio'] == 'oui')
{
	$choix2[] = array('val'=>'acbib','nom'=>'Accueil - biblio');
}
if(isset($choix1) and isset($choix2))
{
	$choix = array_merge($choix1, $choix2);
}
elseif(isset($choix1) and !isset($choix2))
{
	$choix = $choix1;
}
elseif(!isset($choix1) and isset($choix2))
{
	$choix = $choix2;
}

include CHEMIN_VUE.'article.php';