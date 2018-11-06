<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
function rechercher_membre($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT membre.idmembre, nom, prenom, droits, mail, discipline, gestionobs, latin, obser, floutage, string_agg(organisme.organisme, ', ') as organisme FROM site.membre 
						LEFT JOIN site.validateur USING (idmembre)
						LEFT JOIN site.prefmembre USING (idmembre)
						LEFT JOIN referentiel.observateur_organisme ON membre.idmembre = observateur_organisme.idobser
						LEFT JOIN referentiel.organisme USING (idorg)
						WHERE idmembre = :id
						group by membre.idmembre, nom, prenom, droits, mail, discipline, gestionobs, latin, obser, floutage") or die(print_r($bdd->errorInfo()));
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