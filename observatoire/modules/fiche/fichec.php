<?php
if(isset($_GET['id'])) 
{
	include CHEMIN_MODELE.'ficheg.php';
	$id = htmlspecialchars($_GET['id']);
	$cdnom = recup_genrecomplexe($id,$nomvar);
	header('location:index.php?module=fiche&action=ficheg&d='.$nomvar.'&id='.$cdnom);
}