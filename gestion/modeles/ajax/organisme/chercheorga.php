<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
function rechercher($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idorg, organisme, descri FROM referentiel.organisme WHERE idorg = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->execute();
	$obser = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $obser;
}
if(isset($_POST['id']))
{
	$id = $_POST['id'];
	$orga = rechercher($id);
	
	if($orga['idorg'] == $id)
	{	
		$retour['statut'] = 'Ok';
		$retour['info'] = $orga;
	}
	else
	{
		$retour['statut'] = 'Impossible de récupérer les info de cet organisme';
	}	
	echo json_encode($retour);
}