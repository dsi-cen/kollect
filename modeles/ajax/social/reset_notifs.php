<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function supnotif($idm)
{
    $bdd = PDO2::getInstance();
    $req = $bdd->prepare("DELETE FROM site.notif WHERE idm = :idm AND type = 'abo' ") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':idm', $idm);
	$ok = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $ok;
}

if(isset($_POST['idm']))
{
    $idm = htmlspecialchars($_POST['idm']);
	
	$ok = supnotif($idm);
	    
    $retour['statut'] = ($ok == 'oui') ? 'Oui' : 'Non';
}	
else
{
	 $retour['statut'] = 'Non';
}
echo json_encode($retour);