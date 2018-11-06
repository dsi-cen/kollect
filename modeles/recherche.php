<?php
/*
SELECT DISTINCT liste.cdnom, nom, nomvern from obs.obsplte
INNER JOIN referentiel.liste ON liste.cdnom = obsplte.cdnom
INNER JOIN obs.obs USING(idobs)
--WHERE nom ILIKE '%apori%'
WHERE obs.cdnom = 54339
*/

function rechercher_espece($recherche)
{
	$resultat= array();
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT liste.cdnom, nom, nomvern, observatoire, rang FROM referentiel.liste
						INNER JOIN obs.obs ON obs.cdref = liste.cdnom
						WHERE nom ILIKE :recherche OR nomvern ILIKE :recherche 
						ORDER BY nom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':recherche', '%'.$recherche.'%');
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC); 
	$req->closeCursor();
	return array($nbresultats, $resultat);
}
