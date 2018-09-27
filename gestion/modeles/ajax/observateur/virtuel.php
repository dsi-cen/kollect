<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function inser_virtuel($idm,$idses,$prenom,$nom,$datevirt)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO site.virtuel (idmembre, typeid, idsession, nomvirtuel, datevirt)
						VALUES(:idm, :type, :idses, :nom, :datem) ");
	$req->bindValue(':type', 'Observateur');
	$req->bindValue(':idses', $idses);
	$req->bindValue(':datem', $datevirt);
	$req->bindValue(':idm', $idm);
	$req->bindValue(':nom', $prenom.' '.$nom);
	$req->execute();
	$req->closeCursor();
}

if(isset($_POST['nom']) AND isset($_POST['prenom']) AND isset($_POST['idmembre']))
{
	session_start();
	$idm = (isset($_SESSION['idmorigin'])) ? $_SESSION['idmorigin'] : $_SESSION['idmembre'];
	$_SESSION = array();
	session_destroy();	
	
	session_start();
	$_SESSION['prenom'] = $_POST['prenom'];
	$_SESSION['nom'] = $_POST['nom'];
	$_SESSION['droits'] = 0;
	$_SESSION['latin'] = 'defaut';
	$_SESSION['idmembre'] = $_POST['idmembre'];
	$_SESSION['virtuel'] = 'oui';
	$_SESSION['virtobs'] = 'oui';
	$_SESSION['idmorigin'] = $idm;
		
	if($_SESSION['idmembre'] == $_POST['idmembre'])
	{
		$datevirt = date("Y-m-d H:i:s");
		inser_virtuel($idm,$_POST['idmembre'],$_POST['prenom'],$_POST['nom'],$datevirt);
		$retour['statut'] = 'Ok';
	}
	else
	{
		$retour['statut'] = 'probleme lors de la creation des variables de session';
	}
}
else 
{$retour['statut'] = 'Tous les champs ne sont pas parvenus';}
echo json_encode($retour);