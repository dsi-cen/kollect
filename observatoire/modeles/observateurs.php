<?php
function liste_observateur($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("WITH sel AS (
							SELECT DISTINCT idobser FROM obs.fiche 
							INNER JOIN obs.obs USING(idfiche)
							WHERE observa = :observa
							UNION 
							SELECT DISTINCT idobser FROM obs.obs						
							LEFT JOIN obs.plusobser on plusobser.idfiche = obs.idfiche
							WHERE observa = :observa
						)
						SELECT nom, prenom, idobser FROM sel
						INNER JOIN referentiel.observateur USING(idobser)
						ORDER BY nom ");
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);	
}
function liste_photographe($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT nom, prenom, idobser, COUNT(idphoto) AS nb FROM site.photo
						INNER JOIN referentiel.observateur USING(idobser)
						WHERE observatoire = :observa
						GROUP BY nom, prenom, idobser
						ORDER BY nom ");
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);	
}