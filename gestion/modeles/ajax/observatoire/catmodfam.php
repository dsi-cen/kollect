<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function modiffamille($nomvar,$id,$vern)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE $nomvar.famille SET nomvern =:vern WHERE cdnom = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->bindValue(':vern', $vern);
	$vali = ($req->execute()) ? 'oui' : '';
	$req->closeCursor();
	return $vali;
}

if (isset($_POST['sel']) && isset($_POST['id']) && isset($_POST['vern']))
{	
	$nomvar = $_POST['sel'];	
	$id = $_POST['id'];
	$vern = $_POST['vern'];

	$vali = modiffamille($nomvar,$id,$vern);
		
	if ($vali == 'oui')
	{
		$retour['statut'] = 'Oui';	
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! Problème lors de la modification du nom vernaculaire '.$vern.' dans table famille du schéma '.$nomvar.'.</p></div>';
	}		
	echo json_encode($retour);	
}