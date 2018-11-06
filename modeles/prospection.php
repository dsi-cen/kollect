<?php
function nbobservation($anneeune)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT EXTRACT(YEAR FROM date1) AS annee, COUNT(idobs) AS nb FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						WHERE EXTRACT(YEAR FROM date1) >= :annee
						GROUP BY annee ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':annee', $anneeune);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function graphobserva($anneeune)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT EXTRACT(YEAR FROM date1) AS annee, COUNT(DISTINCT cdref) AS nb, observa FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						WHERE EXTRACT(YEAR FROM date1) >= :annee
						GROUP BY annee, observa ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':annee', $anneeune);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}