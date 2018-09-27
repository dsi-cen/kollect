<?php
if (isset($_SESSION['prenom']))
{
	$titre = 'Ajout de son';
	$description = 'Ajout de son par '.$_SESSION['prenom'].' '.$_SESSION['nom'].'.';
	$scripthaut = '<script src="dist/js/jquery.js"></script>';
	$script = '<script src="dist/js/bootstrap.min.js" defer></script>';
	$css = '';
		
	if(isset($_SESSION['idobs']))
	{
		$getidobs =  $_SESSION['idobs'];
		unset($_SESSION['idobs']);
	}
	else
	{
		$getidobs = '';
	}
		
	include CHEMIN_VUE.'ajoutson.php';
}
else
{
	header('location:index.php');
}