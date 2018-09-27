<?php
function cherche_observateur($idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, prenom, idm FROM referentiel.observateur 	WHERE idobser = :idobser ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobser', $idobser, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}

function nbobs($idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(DISTINCT idobs) AS nb, COUNT(DISTINCT cdref) AS nbsp, observa FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						LEFT JOIN obs.plusobser USING(idfiche)
						WHERE fiche.idobser = :idobser or plusobser.idobser = :idobser
						GROUP BY observa ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobser', $idobser, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}