<?php
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function habitat($cdhab)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT description FROM referentiel.eunis WHERE cdhab = :cdhab ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdhab', $cdhab);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['cdhab']))
{
	$cdhab = $_POST['cdhab'];
	
	$habitat = habitat($cdhab);
	
	$retour['descri'] = $habitat['description'];
	$retour['statut'] = 'Oui';	
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour);