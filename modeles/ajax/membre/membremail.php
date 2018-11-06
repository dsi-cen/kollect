<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function modif_mail($idm,$mail)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE site.membre SET mail = :mail WHERE idmembre = :idm ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idm);
	$req->bindValue(':mail', $mail);
	$ok = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $ok;	
}
if(isset($_POST['mail']) && !empty($_POST['mail']))
{
	$idm = $_SESSION['idmembre'];
	$mail = $_POST['mail'];
	
	$ok = modif_mail($idm,$mail);
	
	$retour['statut'] = ($ok == 'oui') ? 'Oui' : 'Non';
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);	