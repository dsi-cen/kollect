<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	
function cherche($id)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("SELECT * FROM statut.libelle WHERE cdprotect = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if (isset($_POST['id']))
{
	$id = $_POST['id'];
	
	$statut = cherche($id);
	
	if(!empty($statut['cdprotect']))
	{
		$retour['statut'] = 'Oui';
		$retour['lib'] = $statut;
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! Aucun statut pour cet id !.</div>';
	}		
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun type de choisit.</div>';
}
echo json_encode($retour);