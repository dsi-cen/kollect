<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
require_once('export_functions.php');

$bdd = PDO2::getInstance();
$bdd->query("SET NAMES 'UTF8'");

$idmembre = $_SESSION['idmembre'];
$droits = $_SESSION['droits'];

// Same than nflou
$req = $bdd->query("SELECT count(*) FROM obs.synthese_obs_nflou " . query($where='non') );

$rows = $req->fetchColumn();
$req->closeCursor();

$retour['nbobs'] = $rows;
$retour['statut'] = 'Oui';
	
echo json_encode($retour);
