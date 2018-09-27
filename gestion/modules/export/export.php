<?php
$titre = 'Gestion actualités';
$description = 'Gestion des actualités';
$script = '<script type="text/javascript" src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>
<script src="../dist/js/jquery-auto.js" defer></script>
<script src="dist/js/export.js" defer></script>';
$css = '';

if ($_SESSION['droits'] >= 1)
{
	if(isset($_SESSION['export']))
	{
		include CHEMIN_MODELE.'export.php';		
		exgeo($_SESSION['export']);			
	}
	include CHEMIN_VUE.'export.php';
}