<?php
function recup($cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, nomvern, observatoire FROM referentiel.liste WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}