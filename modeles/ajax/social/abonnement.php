<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function abonnement($idm,$idabo)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO social.abonements_membre (id_membre, id_abonnement, date_abo) VALUES(:idmembre, :idabonnement, :date) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idmembre', $idm);
	$req->bindValue(':idabonnement', $idabo);
	$req->bindValue(':date', date("Y-m-d H:i:s"));
	$ok = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $ok;
}

if (isset($_POST['idsession']))
{
	$idm = htmlspecialchars($_POST['idsession']);
	$idabo = htmlspecialchars($_POST['idcompare']);
	
	$ok = abonnement($idm,$idabo);
	$retour['statut'] = ($ok == 'oui') ? 'Oui' : 'Non';	
}
else
{
	$retour['statut'] = 'Non';	
}
echo json_encode($retour);	
?>