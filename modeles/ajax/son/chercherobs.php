<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function info($idobs)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT observa, date1, cdref FROM obs.obs 
						INNER JOIN obs.fiche USING(idfiche)
						WHERE idobs = :idobs ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;		
}
function recupidobser($idm)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idobser FROM referentiel.observateur WHERE idm = :idm ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idm);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;		
}
if(!empty($_POST['idobs']))
{
	$idobs = $_POST['idobs'];
	$idm = $_POST['idm'];
	
	$info = info($idobs);
	
	$retour['idobser'] = recupidobser($idm);
	$retour['info'] = $info;
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';	
}
echo json_encode($retour);	