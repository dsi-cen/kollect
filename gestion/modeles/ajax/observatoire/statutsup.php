<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	

function sup_lib($id)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("DELETE FROM statut.libelle WHERE cdprotect = :id ");
	$req->bindValue(':id', $id);
	$req->execute();
	$req->closeCursor();
}
function sup_statut($id)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("DELETE FROM statut.statut WHERE cdprotect = :id ");
	$req->bindValue(':id', $id);
	$req->execute();
	$req->closeCursor();
}

if(isset($_POST['id']))
{
	$id = $_POST['id'];
	
	sup_lib($id);
	sup_statut($id);
	
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun type de choisit.</div>';
}
echo json_encode($retour);