<?php
function nbobservation($anneeune,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT EXTRACT(YEAR FROM date1) AS annee, COUNT(idobs) AS nb FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						WHERE EXTRACT(YEAR FROM date1) >= :annee AND observa = :nomvar
						GROUP BY annee ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':annee', $anneeune);
	$req->bindValue(':nomvar', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}