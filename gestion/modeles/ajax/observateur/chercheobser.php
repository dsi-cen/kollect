<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
function rechercher_obser($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idobser, nom, prenom, idm, aff FROM referentiel.observateur WHERE idobser = :id ");
	$req->bindValue(':id', $id);
	$req->execute();
	$obser = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $obser;
}
if(isset($_POST['id']))
{
	$id = $_POST['id'];
	$obser = rechercher_obser($id);
	
	if ($obser['idobser'] == $id)
	{	
		$retour['statut'] = 'Ok';
		$retour['info'] = $obser;
	}
	else
	{
		$retour['statut'] = 'Impossible de récupérer les info de cet observateur';
	}	
	echo json_encode($retour);
}