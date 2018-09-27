<?php
function rechercher_espece($recherche,$nomvar)
{
	$resultat= array();
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT liste.cdnom, nom, liste.nomvern, rang, photo.cdnom AS photo, famille.famille, famille.cdnom AS cdnomf FROM $nomvar.liste
						INNER JOIN obs.obs ON obs.cdref = liste.cdnom
						LEFT JOIN site.photo ON photo.cdnom = obs.cdref
						INNER JOIN $nomvar.famille ON famille.cdnom = liste.famille
						WHERE nom ILIKE :recherche OR liste.nomvern ILIKE :recherche 
						ORDER BY nom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':recherche', '%'.$recherche.'%');
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC); 
	$req->closeCursor();
	return array($nbresultats, $resultat);
}
function rechercher_plte($recherche,$nomvar)
{
	$resultat= array();
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("WITH sel AS (SELECT idobs FROM obs.obs
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							WHERE nom ILIKE :recherche OR nomvern ILIKE :recherche 
						)
						SELECT DISTINCT nom, nomvern, obsplte.cdnom, observatoire, rang FROM sel
						INNER JOIN obs.obsplte ON obsplte.idobs = sel.idobs
						INNER JOIN referentiel.liste ON liste.cdnom = obsplte.cdnom 
						ORDER BY nom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':recherche', '%'.$recherche.'%');
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC); 
	$req->closeCursor();
	return array($nbresultats, $resultat);
}
function rechercher_obsplte($recherche,$nomvar)
{
	$resultat= array();
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("WITH sel AS (SELECT idobs FROM obs.obsplte
							INNER JOIN $nomvar.liste ON liste.cdnom = obsplte.cdnom
							WHERE nom ILIKE :recherche OR nomvern ILIKE :recherche 
						)
						SELECT DISTINCT nom, nomvern, liste.cdnom, observatoire, rang FROM sel
						INNER JOIN obs.obs on obs.idobs = sel.idobs
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref 
						ORDER BY nom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':recherche', '%'.$recherche.'%');
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC); 
	$req->closeCursor();
	return array($nbresultats, $resultat);
}