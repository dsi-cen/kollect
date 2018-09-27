<?php
function liste_observateur($maille,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT nom, prenom, COUNT(idobs) AS nb FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						LEFT JOIN obs.plusobser USING(idfiche)
						INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser OR observateur.idobser = plusobser.idobser
						WHERE codel93 = :l93 AND observa = :observa
						GROUP BY nom, prenom
						ORDER BY nb DESC ");
	$req->bindValue(':l93', $maille);
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function liste_observateur_utm($maille,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT nom, prenom, COUNT(idobs) AS nb FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						LEFT JOIN obs.plusobser USING(idfiche)
						INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser OR observateur.idobser = plusobser.idobser
						WHERE utm = :utm AND observa = :observa
						GROUP BY nom, prenom
						ORDER BY nb DESC ");
	$req->bindValue(':utm', $maille);
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function liste_observateur5($maille,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT nom, prenom, COUNT(idobs) AS nb FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						LEFT JOIN obs.plusobser USING(idfiche)
						INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser OR observateur.idobser = plusobser.idobser
						WHERE codel935 = :l93 AND observa = :observa
						GROUP BY nom, prenom
						ORDER BY nb DESC ");
	$req->bindValue(':l93', $maille);
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function compte_famille($maille,$droit,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($droit == 'oui')
	{
		$req = $bdd->prepare("SELECT COUNT(DISTINCT obs.cdref) AS nb, famille.famille, famille.cdnom FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						INNER JOIN $nomvar.famille ON famille.cdnom = liste.famille
						WHERE codel93 = :maille AND (rang = 'ES' OR rang ='SSES')  AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY famille.famille, famille.cdnom ");
	}
	else
	{
		$req = $bdd->prepare("SELECT COUNT(DISTINCT obs.cdref) AS nb, famille.famille, famille.cdnom FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						INNER JOIN $nomvar.famille ON famille.cdnom = liste.famille
						LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
						WHERE codel93 = :maille AND (rang = 'ES' OR rang ='SSES')  AND statutobs != 'No' AND (validation = 1 OR validation = 2) AND (sensible <= 1 or sensible is null) AND floutage <= 1
						GROUP BY famille.famille, famille.cdnom ");
	}		
	$req->bindValue(':maille', $maille);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function compte_famille_utm($maille,$droit,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($droit == 'oui')
	{
		$req = $bdd->prepare("SELECT COUNT(DISTINCT obs.cdref) AS nb, famille.famille, famille.cdnom FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						INNER JOIN $nomvar.famille ON famille.cdnom = liste.famille
						WHERE utm = :maille AND (rang = 'ES' OR rang ='SSES')  AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY famille.famille, famille.cdnom ");
	}
	else
	{
		$req = $bdd->prepare("SELECT COUNT(DISTINCT obs.cdref) AS nb, famille.famille, famille.cdnom FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						INNER JOIN $nomvar.famille ON famille.cdnom = liste.famille
						LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
						WHERE utm = :maille AND (rang = 'ES' OR rang ='SSES')  AND statutobs != 'No' AND (validation = 1 OR validation = 2) AND (sensible <= 1 or sensible is null) AND floutage <= 1
						GROUP BY famille.famille, famille.cdnom ");
	}		
	$req->bindValue(':maille', $maille);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function compte_famille_maille5($maille,$droit,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($droit == 'oui')
	{
		$req = $bdd->prepare("SELECT COUNT(DISTINCT obs.cdref) AS nb, famille.famille, famille.cdnom FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						INNER JOIN $nomvar.famille ON famille.cdnom = liste.famille
						WHERE codel935 = :maille AND (rang = 'ES' OR rang ='SSES')  AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY famille.famille, famille.cdnom ");
	}
	else
	{
		$req = $bdd->prepare("SELECT COUNT(DISTINCT obs.cdref) AS nb, famille.famille, famille.cdnom FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						INNER JOIN $nomvar.famille ON famille.cdnom = liste.famille
						LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
						WHERE codel935 = :maille AND (rang = 'ES' OR rang ='SSES')  AND statutobs != 'No' AND (validation = 1 OR validation = 2) AND (sensible <= 1 or sensible is null) AND floutage <= 1
						GROUP BY famille.famille, famille.cdnom ");
	}		
	$req->bindValue(':maille', $maille);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function liste($maille,$droit,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($droit == 'oui')
	{
		$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdnom, famille FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						WHERE codel93 = :l93 AND (rang = 'ES' OR rang ='SSES')  AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						ORDER BY nom ");
	}
	else
	{
		$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdnom, famille FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
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
function listeutm($maille,$droit,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($droit == 'oui')
	{
		$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdnom, famille FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						WHERE utm = :utm AND (rang = 'ES' OR rang ='SSES')  AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						ORDER BY nom ");
	}
	else
	{
		$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdnom, famille FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
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
function liste5($maille,$droit,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($droit == 'oui')
	{
		$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdnom, famille FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						WHERE codel935 = :l93 AND (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						ORDER BY nom ");
	}
	else
	{
		$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdnom, famille FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
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
function nbespece($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT COUNT(DISTINCT obs.cdref) AS Nb FROM obs.obs
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						WHERE rang = 'ES' ");
	$nbobs = $req->fetchColumn();
	$req->closeCursor();
	return $nbobs;
}
function nouvelle_espece($maille,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT obs.cdref AS cdnom, nom, nomvern, EXTRACT(YEAR FROM MIN(date1)) AS annee FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN $nomvar.liste ON obs.cdref = liste.cdnom
						WHERE codel93 = :maille AND (rang = 'ES' OR rang = 'SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY obs.cdref, nom, nomvern
						ORDER BY nom ");
	$req->bindValue(':maille', $maille);
	$req->execute();
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function nouvelle_espece_utm($maille,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT obs.cdref AS cdnom, nom, nomvern, EXTRACT(YEAR FROM MIN(date1)) AS annee FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN $nomvar.liste ON obs.cdref = liste.cdnom
						WHERE utm = :maille AND (rang = 'ES' OR rang = 'SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY obs.cdref, nom, nomvern
						ORDER BY nom ");
	$req->bindValue(':maille', $maille);
	$req->execute();
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function nouvelle_espece_maille5($maille,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT obs.cdref AS cdnom, nom, nomvern, EXTRACT(YEAR FROM MIN(date1)) AS annee FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN $nomvar.liste ON obs.cdref = liste.cdnom
						WHERE codel935 = :maille AND (rang = 'ES' OR rang = 'SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY obs.cdref, nom, nomvern
						ORDER BY nom ");
	$req->bindValue(':maille', $maille);
	$req->execute();
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}