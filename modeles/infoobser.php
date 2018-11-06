<?php
function cherche_observateur($idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT nom, prenom, idm FROM referentiel.observateur WHERE idobser = :idobser ");
	$req->bindValue(':idobser', $idobser, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function cherche_observateurmembre($idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT nom, prenom, idobser, idm FROM referentiel.observateur WHERE idm = :idm ");
	$req->bindValue(':idm', $idm, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function graphobs($idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("WITH sel AS (
							SELECT date1, idobs FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							LEFT JOIN obs.plusobser USING(idfiche)
							WHERE fiche.idobser = :idobser OR plusobser.idobser = :idobser
						)
						SELECT EXTRACT(YEAR FROM date1) AS annee, COUNT(idobs) AS nb FROM sel
						GROUP BY annee
						ORDER BY annee ");
	$req->bindValue(':idobser', $idobser, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}