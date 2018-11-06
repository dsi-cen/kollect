<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function rechercher_det($iddet)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(idobs) AS nb FROM obs.obs WHERE iddet = :id	");
	$req->bindValue(':id', $iddet);
	$req->execute();
	$obser = $req->fetchColumn();
	$req->closeCursor();
	return $obser;
}
function rechercher_coobser($iddet)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(DISTINCT idobs) AS nb FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						LEFT JOIN obs.plusobser USING(idfiche)
						WHERE plusobser.idobser = :id	");
	$req->bindValue(':id', $iddet);
	$req->execute();
	$obser = $req->fetchColumn();
	$req->closeCursor();
	return $obser;
}
function sup_observateur($iddet)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM referentiel.observateur WHERE idobser = :id ");
	$req->bindValue(':id', $iddet, PDO::PARAM_INT);
	$req->execute();
	$req->closeCursor();	
}

if(isset($_POST['id']))
{
	$iddet = $_POST['id'];
	$nbdt = rechercher_det($iddet);
	$nbco = rechercher_coobser($iddet);
	
	if($nbdt == 0 && $nbco == 0)
	{
		sup_observateur($iddet);		
	}
		
	$retour['statut'] = 'Oui';
	$retour['nbdt'] = $nbdt;
	$retour['nbco'] = $nbco;
	echo json_encode($retour);
}