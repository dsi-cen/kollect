<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function get_db_password($idm){ // Récupère le hash de la db pour vérification
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("SELECT motpasse FROM site.membre WHERE idmembre = :idm ");
    $req->bindParam(':idm', $idm, PDO::PARAM_STR);
    $req->execute();
    $hash = $req->fetch();
    $req->closeCursor();
    return $hash ;
}

function modif_mdp($idm,$newhash)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE site.membre SET motpasse = :mdp WHERE idmembre = :idm ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idm);
	$req->bindValue(':mdp', $newhash);
	$ok = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $ok;	
}

if(isset($_POST['mdp']) && !empty($_POST['mdp']) && !empty($_POST['mdpn'])) {
	$idm = $_SESSION['idmembre'];
	$mdp = htmlspecialchars($_POST['mdp']);
	$mdpn = htmlspecialchars($_POST['mdpn']);
	$oldhash = get_db_password($idm);

    if (password_verify($mdp, $oldhash[0])) {
        $newhash = password_hash($mdpn, PASSWORD_BCRYPT);
		$ok = modif_mdp($idm,$newhash);
		$retour['statut'] = ($ok == 'oui') ? 'Oui' : 'Non';		
	}

	else {
		$retour['statut'] = 'Non';
	}
}
else {
	$retour['statut'] = 'Non';
}
echo json_encode($retour);