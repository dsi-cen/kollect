<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
function rechercher_membre($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT membre.idmembre, nom, prenom, droits, mail, discipline, gestionobs, latin, obser, floutage FROM site.membre 
						LEFT JOIN site.validateur USING (idmembre)
						LEFT JOIN site.prefmembre USING (idmembre)
						WHERE idmembre = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->execute();
	$nbresultats = $req->rowCount();
	$membre = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $membre);
}
if(isset($_POST['id']))
{
	$id = $_POST['id'];
	$membre = rechercher_membre($id);
	if ($membre[0] == 1)
	{	
		$retour['statut'] = 'Ok';
		$retour['info'] = $membre[1];
	}
	else
	{
		$retour['statut'] = 'Impossible de récupérer les info de ce membre';
	}	
	echo json_encode($retour);
}