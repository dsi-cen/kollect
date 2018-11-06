<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function aide()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT article FROM site.article WHERE typear = 'aidesaisie' ") or die(print_r($bdd->errorInfo()));
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;		
}	

$aide = aide();
	
echo $aide['article'];
