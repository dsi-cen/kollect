<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function sup_orga($idorg)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM referentiel.organisme WHERE idorg = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $idorg, PDO::PARAM_INT);
	$req->execute();
	$req->closeCursor();	
}

if(isset($_POST['id']))
{
	$idorg = $_POST['id'];
	
	sup_orga($idorg);
			
	$retour['statut'] = 'Oui';
	
	echo json_encode($retour);
}