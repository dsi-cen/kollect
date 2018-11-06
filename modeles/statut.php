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
function cherche_departement_dep($codecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT iddep, departement FROM referentiel.departement 
						INNER JOIN referentiel.commune USING(iddep)
						WHERE codecom = :codecom ");
	$req->bindValue(':codecom', $codecom);
	$req->execute();
	$result = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function liste_statut_com($codecom,$droit)
{
	$strQuery = "WITH sel AS (SELECT COUNT(idobs) AS nbt, cdref FROM obs.obs
					INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
					WHERE rang = 'ES' OR rang = 'SSES' AND statutobs != 'No' AND (validation = 1 OR validation = 2)
					GROUP BY cdref )";
	$strQuery .= " SELECT sel.nbt, COUNT(idobs) AS nb, nom, nomvern, sel.cdref, ir, observa FROM sel
					INNER JOIN obs.obs ON obs.cdref = sel.cdref
					INNER JOIN obs.fiche USING(idfiche)
					INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref";
	if($droit == 'non') { $strQuery .= ' LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref'; }
	$strQuery .= " WHERE codecom = :codecom AND (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= ' AND (sensible <= 1 OR sensible IS NULL) AND floutage <= 1'; }
	$strQuery .= " GROUP BY sel.cdref, nom, nomvern, nbt, ir, observa";
	$strQuery .= " ORDER BY nom";
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':codecom', $codecom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function liste_statut_l93($maille,$droit)
{
	$strQuery = "WITH sel AS (SELECT COUNT(idobs) AS nbt, cdref FROM obs.obs
					INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
					WHERE rang = 'ES' OR rang = 'SSES' AND statutobs != 'No' AND (validation = 1 OR validation = 2)
					GROUP BY cdref )";
	$strQuery .= " SELECT sel.nbt, COUNT(idobs) AS nb, nom, nomvern, sel.cdref, ir, observa FROM sel
					INNER JOIN obs.obs ON obs.cdref = sel.cdref
					INNER JOIN obs.fiche USING(idfiche)
					INNER JOIN obs.coordonnee USING(idcoord)
					INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref";
	if($droit == 'non') { $strQuery .= ' LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref'; }
	$strQuery .= " WHERE codel93 = :maille AND (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= ' AND (sensible <= 1 OR sensible IS NULL) AND floutage <= 1'; }
	$strQuery .= " GROUP BY sel.cdref, nom, nomvern, nbt, ir, observa";
	$strQuery .= " ORDER BY nom";
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':maille', $maille);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function liste_statut_l935($maille,$droit)
{
	$strQuery = "WITH sel AS (SELECT COUNT(idobs) AS nbt, cdref FROM obs.obs
					INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
					WHERE rang = 'ES' OR rang = 'SSES' AND statutobs != 'No' AND (validation = 1 OR validation = 2)
					GROUP BY cdref )";
	$strQuery .= " SELECT sel.nbt, COUNT(idobs) AS nb, nom, nomvern, sel.cdref, ir, observa FROM sel
					INNER JOIN obs.obs ON obs.cdref = sel.cdref
					INNER JOIN obs.fiche USING(idfiche)
					INNER JOIN obs.coordonnee USING(idcoord)
					INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref";
	if($droit == 'non') { $strQuery .= ' LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref'; }
	$strQuery .= " WHERE codel935 = :maille AND (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= ' AND (sensible <= 1 OR sensible IS NULL) AND floutage <= 1'; }
	$strQuery .= " GROUP BY sel.cdref, nom, nomvern, nbt, ir, observa";
	$strQuery .= " ORDER BY nom";
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':maille', $maille);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function liste_statut_utm($maille,$droit)
{
	$strQuery = "WITH sel AS (SELECT COUNT(idobs) AS nbt, cdref FROM obs.obs
					INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
					WHERE rang = 'ES' OR rang = 'SSES' AND statutobs != 'No' AND (validation = 1 OR validation = 2)
					GROUP BY cdref )";
	$strQuery .= " SELECT sel.nbt, COUNT(idobs) AS nb, nom, nomvern, sel.cdref, ir, observa FROM sel
					INNER JOIN obs.obs ON obs.cdref = sel.cdref
					INNER JOIN obs.fiche USING(idfiche)
					INNER JOIN obs.coordonnee USING(idcoord)
					INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref";
	if($droit == 'non') { $strQuery .= ' LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref'; }
	$strQuery .= " WHERE utm = :maille AND (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= ' AND (sensible <= 1 OR sensible IS NULL) AND floutage <= 1'; }
	$strQuery .= " GROUP BY sel.cdref, nom, nomvern, nbt, ir, observa";
	$strQuery .= " ORDER BY nom";
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':maille', $maille);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function liste_statut_dep($iddep)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("WITH sel AS (SELECT COUNT(idobs) AS nbt, cdref FROM obs.obs
							INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
							WHERE rang = 'ES' OR rang = 'SSES' AND statutobs != 'No' AND (validation = 1 OR validation = 2)
							GROUP BY cdref
						)
						SELECT sel.nbt, COUNT(idobs) AS nb, nom, nomvern, sel.cdref, ir, observa FROM sel
						INNER JOIN obs.obs ON obs.cdref = sel.cdref
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref
						WHERE iddep = :iddep AND (rang = 'ES' OR rang ='SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY sel.cdref, nom, nomvern, nbt, ir, observa
						ORDER BY nom ");
	$req->bindValue(':iddep', $iddep);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function statut_commune($codecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdref, type, lr FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN statut.statut ON statut.cdnom = obs.cdref
						INNER JOIN statut.statutsite USING(cdprotect)
						WHERE codecom = :codecom
						GROUP BY cdref, type, lr ");	
	$req->bindValue(':codecom', $codecom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function statut_dep($iddep)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdref, type, lr FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN statut.statut ON statut.cdnom = obs.cdref
						INNER JOIN statut.statutsite USING(cdprotect)
						WHERE iddep = :iddep
						GROUP BY cdref, type, lr ");	
	$req->bindValue(':iddep', $iddep);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function statut_l93($maille)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdref, type, lr FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN statut.statut ON statut.cdnom = obs.cdref
						INNER JOIN statut.statutsite USING(cdprotect)
						WHERE codel93 = :maille
						GROUP BY cdref, type, lr ");
	$req->bindValue(':maille', $maille);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function statut_l935($maille)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdref, type, lr FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN statut.statut ON statut.cdnom = obs.cdref
						INNER JOIN statut.statutsite USING(cdprotect)
						WHERE codel935 = :maille
						GROUP BY cdref, type, lr ");
	$req->bindValue(':maille', $maille);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function statut_utm($maille)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdref, type, lr FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN statut.statut ON statut.cdnom = obs.cdref
						INNER JOIN statut.statutsite USING(cdprotect)
						WHERE utm = :maille
						GROUP BY cdref, type, lr ");
	$req->bindValue(':maille', $maille);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}