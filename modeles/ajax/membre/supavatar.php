<?php
session_start();

$favatar = '../../../photo/avatar/'.$_SESSION['prenom'].''.$_SESSION['idmembre'].'.jpg';
if(file_exists($favatar))
{
	unlink($favatar);
	$retour['statut'] = 'Oui';
	$retour['mes'] = '<div class="alert alert-success" role="alert">Votre avatar a bien été supprimé.</dv>';
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! Problème lors de la suppression de votre avatar.</dv>';
}

echo json_encode($retour);	