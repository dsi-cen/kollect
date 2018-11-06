<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function rechercher_membre()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT membre.idmembre, nom, prenom, droits, mail, discipline, gestionobs, latin, obser, floutage FROM site.membre 
						LEFT JOIN site.validateur USING (idmembre)
						LEFT JOIN site.prefmembre USING (idmembre)
						WHERE idmembre = 1 ") or die(print_r($bdd->errorInfo()));
	$req->execute();
	$nbresultats = $req->rowCount();
	$membre = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $membre;
}

session_start();
$_SESSION = array();
session_destroy();	

$m = rechercher_membre();

session_start();
$_SESSION['prenom'] = $m['prenom'];
$_SESSION['nom'] = $m['nom'];
$_SESSION['latin'] = $m['latin'];
$_SESSION['obser'] = $m['obser'];
$_SESSION['droits'] = 4;
$_SESSION['idmembre'] = 1;
$_SESSION['virtuel'] = 'oui';
