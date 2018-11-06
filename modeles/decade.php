<?php
function liste($decade)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdnom, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.ligneobs USING(idobs)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE decade = :dec AND (rang = 'ES' OR rang ='SSES') AND idetatbio = 2
						ORDER BY nom ") or die(print_r($bdd->errorInfo()));	
	$req->bindValue(':dec', $decade);
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);
}
/*function listeobserva($nomvar,$decade)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE decade = 'D3' AND (rang = 'ES' OR rang ='SSES') ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':dec', $decade);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}*/
function listeobs($decade)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdnom, COUNT(idobs) AS nb, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.ligneobs USING(idobs)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE decade = :dec AND (rang = 'ES' OR rang ='SSES') AND idetatbio = 2
						GROUP BY nom, nomvern, liste.cdnom, observa
						ORDER BY nb DESC, nom
						LIMIT 50 ") or die(print_r($bdd->errorInfo()));	
	$req->bindValue(':dec', $decade);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function graph()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT COUNT(DISTINCT cdref) AS nb, iddecade, decade.decade, observa FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.ligneobs USING(idobs)
						INNER JOIN referentiel.decade ON decade.decade = fiche.decade
						WHERE idetatbio = 2
						GROUP BY iddecade, decade.decade, observa ") or die(print_r($bdd->errorInfo()));
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
