<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function modiftype($nouv,$initial,$idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE obs.fiche SET floutage = :nouv WHERE idobser = :idobser AND floutage = :initial ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':nouv', $nouv);
	$req->bindValue(':initial', $initial);
	$req->bindValue(':idobser', $idobser);
	$ok = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $ok;	
}
if(isset($_POST['sel']) && isset($_POST['initial']) && isset($_POST['idobser']))
{
	$nouv = $_POST['sel'];
	$initial = $_POST['initial'];
	$idobser = $_POST['idobser'];
	
	$ok = modiftype($nouv,$initial,$idobser);
	if($ok == 'oui')
	{
		$retour['statut'] = 'Oui';
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