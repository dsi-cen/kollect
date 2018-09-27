<?php
function liste_obser()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("WITH sel AS (
						SELECT idobser, SUM(nb) AS nb FROM (
							SELECT COUNT(idobs) AS nb, idobser FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							GROUP BY idobser
							UNION
							SELECT COUNT(idobs) AS nb, idobser FROM obs.obs
							INNER JOIN obs.plusobser USING(idfiche)
							GROUP BY idobser
							) x GROUP BY idobser
						)
						SELECT observateur.idobser, nom, prenom, idm, sel.nb, aff FROM referentiel.observateur
						LEFT JOIN sel ON sel.idobser = observateur.idobser 
						ORDER BY nom ");
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);	
}