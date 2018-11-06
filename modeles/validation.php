<?php
function validateur()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT discipline FROM site.validateur ") or die(print_r($bdd->errorInfo()));
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function validateurnom($observa)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT nom, prenom FROM site.validateur
						INNER JOIN site.membre USING(idmembre)
						WHERE discipline ILIKE :observa
						ORDER BY nom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':observa', '%'.$observa.'%');
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}