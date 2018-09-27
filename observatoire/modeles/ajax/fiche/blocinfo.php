<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function info($cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT repartition FROM referentiel.infosp WHERE cdnom = :cdnom ") or die(print_r($bdd->errorInfo()));		
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$result = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}

if(isset($_POST['cdnom'])) 
{
	$cdnom = $_POST['cdnom'];
	$info = info($cdnom);
	// faudra faire vérif si bien récupération d'info avant de faire les retours
	
	$retour['repartition'] = $info['repartition'];
	$retour['statut'] = 'Oui';	
		
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour);
