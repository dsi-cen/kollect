<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function verif($idm,$pass_hache)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idmembre FROM site.membre WHERE idmembre = :idm AND motpasse = :motpasse ") or die(print_r($bdd->errorInfo()));
	$req->bindParam(':idm', $idm, PDO::PARAM_INT);
	$req->bindParam(':motpasse', $pass_hache, PDO::PARAM_STR);
	$req->execute();
	$verif = $req->fetchColumn();
	$req->closeCursor();
	return $verif;
}
function modif_mdp($idm,$pass_hache)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE site.membre SET motpasse = :mdp WHERE idmembre = :idm ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idm);
	$req->bindValue(':mdp', $pass_hache);
	$ok = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $ok;	
}
if(isset($_POST['mdp']) && !empty($_POST['mdp']) && !empty($_POST['mdpn']))
{
	$idm = $_SESSION['idmembre'];
	$mdp = htmlspecialchars($_POST['mdp']);
	$mdpn = htmlspecialchars($_POST['mdpn']);
	
	$pass_hache = sha1($mdp);
	
	$verif = verif($idm,$pass_hache);
	
	if($verif == $idm)
	{
		$pass_hache = sha1($mdpn);
		$ok = modif_mdp($idm,$pass_hache);
		$retour['statut'] = ($ok == 'oui') ? 'Oui' : 'Non';		
	}
	else
	{
		$retour['statut'] = 'Non';
	}
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);	