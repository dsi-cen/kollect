<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	

function cherche($t)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("SELECT COUNT(cdprotect) from statut.libelle WHERE cdprotect ILIKE :t ");
	$req->bindValue(':t', ''.$t.'%');
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}

$t = $_POST['t'];

$nb = cherche($t);
$retour['nb'] = $nb + 1;

echo json_encode($retour);