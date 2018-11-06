<?php
function cherche_commune($codecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
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
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT departement FROM referentiel.departement WHERE iddep = :iddep ");
	$req->bindValue(':iddep', $iddep);
	$req->execute();
	$result = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function liste_observateur($codecom,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT nom, prenom, COUNT(idobs) AS nb FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						LEFT JOIN obs.plusobser USING(idfiche)
						INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser OR observateur.idobser = plusobser.idobser
						WHERE codecom = :codecom AND observa = :observa
						GROUP BY nom, prenom
						ORDER BY nb DESC ");
	$req->bindValue(':codecom', $codecom);
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function liste_observateur_dep($iddep,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT nom, prenom, COUNT(idobs) AS nb FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						LEFT JOIN obs.plusobser USING(idfiche)
						INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser OR observateur.idobser = plusobser.idobser
						WHERE iddep = :iddep AND observa = :observa
						GROUP BY nom, prenom
						ORDER BY nb DESC ");
	$req->bindValue(':iddep', $iddep);
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function comptefamille($codecom,$droit,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($droit == 'oui')
	{
		$req = $bdd->prepare("SELECT COUNT(DISTINCT obs.cdref) AS nb, famille.famille, famille.cdnom FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						INNER JOIN $nomvar.famille ON famille.cdnom = liste.famille
						WHERE codecom = :codecom AND (rang = 'ES' OR rang ='SSES')  AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY famille.famille, famille.cdnom ");
	}
	else
	{
		$req = $bdd->prepare("SELECT COUNT(DISTINCT obs.cdref) AS nb, famille.famille, famille.cdnom FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						INNER JOIN $nomvar.famille ON famille.cdnom = liste.famille
						LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
						WHERE codecom = :codecom AND (rang = 'ES' OR rang ='SSES')  AND statutobs != 'No' AND (validation = 1 OR validation = 2) AND (sensible <= 1 or sensible is null) AND floutage <= 1
						GROUP BY famille.famille, famille.cdnom ");
	}		
	$req->bindValue(':codecom', $codecom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function comptefamilledep($iddep,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("WITH sel AS (
							SELECT COUNT(DISTINCT obs.cdref) AS nb, cdref FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							WHERE iddep = :iddep AND observa = 'lepido' AND statutobs != 'No' AND (validation = 1 OR validation = 2)
							GROUP BY cdref
						)
						SELECT COUNT(famille.cdnom) as nb, famille.famille, famille.cdnom FROM sel
						INNER JOIN $nomvar.liste ON liste.cdnom = sel.cdref
						INNER JOIN $nomvar.famille ON famille.cdnom = liste.famille
						WHERE rang = 'ES' OR rang = 'SSES'
						GROUP BY famille.famille, famille.cdnom
						ORDER BY famille.famille ");
	$req->bindValue(':iddep', $iddep);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function liste($codecom,$droit,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($droit == 'oui')
	{
		$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdnom, famille FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						WHERE codecom = :codecom AND (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						ORDER BY nom ");
	}
	else
	{
		$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdnom, famille FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
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
function listedepart($iddep,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdnom, famille FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						WHERE iddep = :iddep AND (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						ORDER BY nom ");
	$req->bindValue(':iddep', $iddep);
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);
}
function nbespece($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT COUNT(DISTINCT obs.cdref) AS Nb FROM obs.obs
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						WHERE (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2) ");
	$nbobs = $req->fetchColumn();
	$req->closeCursor();
	return $nbobs;
}
function nouvelle_espece($codecom,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT obs.cdref AS cdnom, nom, nomvern, EXTRACT(YEAR FROM MIN(date1)) AS annee FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN $nomvar.liste ON obs.cdref = liste.cdnom
						WHERE codecom = :codecom AND (rang = 'ES' OR rang = 'SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY obs.cdref, nom, nomvern
						ORDER BY nom ");
	$req->bindValue(':codecom', $codecom);
	$req->execute();
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function nouvelle_espece_dep($iddep,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT obs.cdref AS cdnom, nom, nomvern, EXTRACT(YEAR FROM MIN(date1)) AS annee FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN $nomvar.liste ON obs.cdref = liste.cdnom
						WHERE iddep = :iddep AND (rang = 'ES' OR rang = 'SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY obs.cdref, nom, nomvern
						ORDER BY nom ");
	$req->bindValue(':iddep', $iddep);
	$req->execute();
	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}