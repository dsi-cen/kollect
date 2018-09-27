<?php
function liste_observateur($maille)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT nom, prenom, COUNT(idobs) AS nb FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						LEFT JOIN obs.plusobser USING(idfiche)
						INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser OR observateur.idobser = plusobser.idobser
						WHERE codel93 = :l93
						GROUP BY nom, prenom
						ORDER BY nb DESC ");
	$req->bindValue(':l93', $maille);
	$req->execute();
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function liste_observateur_utm($maille)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT nom, prenom, COUNT(idobs) AS nb FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						LEFT JOIN obs.plusobser USING(idfiche)
						INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser OR observateur.idobser = plusobser.idobser
						WHERE utm = :utm
						GROUP BY nom, prenom
						ORDER BY nb DESC ");
	$req->bindValue(':utm', $maille);
	$req->execute();
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function liste_observateur5($maille)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT nom, prenom, COUNT(idobs) AS nb FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						LEFT JOIN obs.plusobser USING(idfiche)
						INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser OR observateur.idobser = plusobser.idobser
						WHERE codel935 = :l93
						GROUP BY nom, prenom
						ORDER BY nb DESC ");
	$req->bindValue(':l93', $maille);
	$req->execute();
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function compteobserva($maille,$droit)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($droit == 'oui')
	{
		$req = $bdd->prepare("SELECT COUNT(DISTINCT cdref) AS nb, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE codel93 = :l93 AND (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY observa ");
	}
	else
	{
		$req = $bdd->prepare("SELECT COUNT(DISTINCT cdref) AS nb, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
						WHERE codel93 = :l93 AND (rang = 'ES' OR rang ='SSES')  AND statutobs != 'No' AND (validation = 1 OR validation = 2) AND (sensible <= 2 OR sensible IS NULL) AND floutage <= 2
						GROUP BY observa ");
	}		
	$req->bindValue(':l93', $maille);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function compteobserva_utm($maille,$droit)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($droit == 'oui')
	{
		$req = $bdd->prepare("SELECT COUNT(DISTINCT cdref) AS nb, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE utm = :utm AND (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY observa ");
	}
	else
	{
		$req = $bdd->prepare("SELECT COUNT(DISTINCT cdref) AS nb, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
						WHERE utm = :utm AND (rang = 'ES' OR rang ='SSES')  AND statutobs != 'No' AND (validation = 1 OR validation = 2) AND (sensible <= 2 OR sensible IS NULL) AND floutage <= 2
						GROUP BY observa ");
	}		
	$req->bindValue(':utm', $maille);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function compteobserva5($maille,$droit)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($droit == 'oui')
	{
		$req = $bdd->prepare("SELECT COUNT(DISTINCT cdref) AS nb, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE codel935 = :l93 AND (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY observa ");
	}
	else
	{
		$req = $bdd->prepare("SELECT COUNT(DISTINCT cdref) AS nb, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
						WHERE codel935 = :l93 AND (rang = 'ES' OR rang ='SSES')  AND statutobs != 'No' AND (validation = 1 OR validation = 2) AND sensible IS NULL AND floutage = 0
						GROUP BY observa ");
	}		
	$req->bindValue(':l93', $maille);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function liste($maille,$droit)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($droit == 'oui')
	{
		$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdnom, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE codel93 = :l93 AND (rang = 'ES' OR rang ='SSES')  AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						ORDER BY nom ");
	}
	else
	{
		$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdnom, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
						WHERE codel93 = :l93 AND (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2) AND (sensible <= 2 OR sensible IS NULL) AND floutage <= 2
						ORDER BY nom ");
	}
	$req->bindValue(':l93', $maille);
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);
}
function listeutm($maille,$droit)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($droit == 'oui')
	{
		$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdnom, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE utm = :utm AND (rang = 'ES' OR rang ='SSES')  AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						ORDER BY nom ");
	}
	else
	{
		$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdnom, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
						WHERE utm = :utm AND (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2) AND (sensible <= 2 OR sensible IS NULL) AND floutage <= 2
						ORDER BY nom ");
	}
	$req->bindValue(':utm', $maille);
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);
}
function liste5($maille,$droit)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($droit == 'oui')
	{
		$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdnom, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE codel935 = :l93 AND (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						ORDER BY nom ");
	}
	else
	{
		$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdnom, observa FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
						WHERE codel935 = :l93 AND (rang = 'ES' OR rang ='SSES')  AND statutobs != 'No' AND (validation = 1 OR validation = 2) AND sensible IS NULL AND floutage = 0
						ORDER BY nom ");
	}
	$req->bindValue(':l93', $maille);
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