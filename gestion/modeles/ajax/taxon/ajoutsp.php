<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	


if(isset($_POST['cdnom']))
{
	$cdnom = $_POST['cdnom'];
	$genre = $_POST['genre'];
	$espece = $_POST['espece'];
	$auteur = $_POST['auteur'];
	$observa = $_POST['observa'];
	
	//verif si present taxref à faire 
		
	$info = recherche_genre($genre);
	
	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun observatoire de choisit.</div>';
}
echo json_encode($retour);