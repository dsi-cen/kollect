<?php
function cherche_commune($codecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT commune FROM referentiel.commune WHERE codecom = :codecom ");
	$req->bindValue(':codecom', $codecom);
	$req->execute();
	$result = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function cherche_departement($iddep)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT departement FROM referentiel.departement WHERE iddep = :iddep ");
	$req->bindValue(':iddep', $iddep);
	$req->execute();
	$result = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function liste_observateur($codecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT nom, prenom, COUNT(idobs) AS nb FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						LEFT JOIN obs.plusobser USING(idfiche)
						INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser OR observateur.idobser = plusobser.idobser
						WHERE codecom = :codecom
						GROUP BY nom, prenom
						ORDER BY nb DESC ");
	$req->bindValue(':codecom', $codecom);
	$req->execute();
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function liste_observateur_dep($iddep)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT nom, prenom, COUNT(idobs) AS nb FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						LEFT JOIN obs.plusobser USING(idfiche)
						INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser OR observateur.idobser = plusobser.idobser
						WHERE iddep = :iddep
						GROUP BY nom, prenom
						ORDER BY nb DESC ");
	$req->bindValue(':iddep', $iddep);
	$req->execute();
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function compteobserva($codecom,$droit)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($droit == 'oui')
	{
		$req = $bdd->prepare("SELECT COUNT(DISTINCT cdref) AS nb, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE codecom = :codecom AND (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY observa ");
	}
	else
	{
		$req = $bdd->prepare("SELECT COUNT(DISTINCT cdref) AS nb, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
						WHERE codecom = :codecom AND (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2) AND (sensible <= 1 or sensible is null) AND floutage <= 1
						GROUP BY observa ");
	}		
	$req->bindValue(':codecom', $codecom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function compteobservadep($iddep)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(DISTINCT cdref) AS nb, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE iddep = :iddep AND (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY observa ");
	$req->bindValue(':iddep', $iddep);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function liste($codecom,$droit)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($droit == 'oui')
	{
		$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdnom, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE codecom = :codecom AND (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						ORDER BY nom ");	
	}
	else
	{
		$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdnom, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
						WHERE codecom = :codecom AND (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2) AND (sensible <= 1 or sensible is null) AND floutage <= 1
						ORDER BY nom ");	
	}
	$req->bindValue(':codecom', $codecom);
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);
}
function listedepart($iddep)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdnom, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE iddep = :iddep AND (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						ORDER BY nom ");	
	$req->bindValue(':iddep', $iddep);
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);
}
function nbespece()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT COUNT(DISTINCT cdref) AS Nb FROM obs.obs
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE rang = 'ES' ");
	$nbobs = $req->fetchColumn();
	$req->closeCursor();
	return $nbobs;
}