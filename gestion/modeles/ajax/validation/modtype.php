<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function modvali($cdnom,$type)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE referentiel.liste SET vali = :vali WHERE cdnom = :cdnom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':vali', $type);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;
}

if(isset($_POST['vali']) && isset($_POST['cdnom']))
{	
	$type = $_POST['vali'];
	$cdnom = $_POST['cdnom'];
		
	$vali = modvali($cdnom,$type);
	
	$retour['statut'] = ($vali == 'oui') ? 'Oui' : 'Non';
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);	
?>