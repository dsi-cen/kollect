<?php
$titre = 'Derniers commentaires';
$description = 'Liste des derniers commentaires de validation';
$script = '<script type="text/javascript" src="../dist/js/jquery.js" defer></script>
<script src="../dist/js/bootstrap.min.js" defer></script>';
$css = ''; 

if($_SESSION['droits'] == 4)
{
	include CHEMIN_MODELE.'validation.php';
	
	$listeidobs = liste_idobs_com();
	
	$liste = liste_com();
		
	include CHEMIN_VUE.'com.php';
}
