<?php
$titre = 'Gestion biblio';
$description = 'Gestion de la biblio';
$script = '<script type="text/javascript" src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>
<script src="../dist/js/jquery-auto.js" defer></script>
<script type="text/javascript" src="dist/js/ckeditor/ckeditor.js" defer></script>
<script src="dist/js/biblio.js" defer></script>';
$css = '';

$json_emprise = file_get_contents('../emprise/emprise.json');
$rjson_emprise = json_decode($json_emprise, true);
$dep = ($rjson_emprise['contour2'] == 'oui' OR $rjson_emprise['emprise'] == 'fr') ? 'oui' : 'non';

if(isset($rjson_site['observatoire']))
{
	foreach($rjson_site['observatoire'] as $n)
	{
		$discipline[] = ['disc'=>$n['nom'],'var'=>$n['nomvar']];
	}
}

$idbiblio = (isset($_GET['id'])) ? $_GET['id'] : 0;

include CHEMIN_VUE.'biblio.php';