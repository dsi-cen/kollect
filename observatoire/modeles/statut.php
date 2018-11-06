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
function liste_statut_com($codecom,$droit,$nomvar)
{
	$strQuery = "WITH sel AS (SELECT COUNT(idobs) AS nbt, obs.cdref FROM obs.obs
					INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
					WHERE rang = 'ES' OR rang = 'SSES' AND statutobs != 'No' AND (validation = 1 OR validation = 2)
					GROUP BY obs.cdref ), sel1 AS (";
	$strQuery .= " SELECT COUNT(idobs) AS nb, cdref FROM obs.obs
					INNER JOIN obs.fiche USING(idfiche)";
	if($droit == 'non') { $strQuery .= ' LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref'; }
	$strQuery .= " WHERE codecom = :codecom AND observa = :observa AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= ' AND (sensible <= 1 OR sensible IS NULL) AND floutage <= 1'; }
	$strQuery .= " GROUP BY cdref)";
	$strQuery .= " SELECT sel.nbt, sel1.nb, nom, nomvern, sel.cdref, ir FROM sel	
					INNER JOIN sel1 USING(cdref)
					INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref
					ORDER BY nom";
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':codecom', $codecom);
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function liste_statut_l93($maille,$droit,$nomvar)
{
	$strQuery = "WITH sel AS (SELECT COUNT(idobs) AS nbt, obs.cdref FROM obs.obs
					INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
					WHERE rang = 'ES' OR rang = 'SSES' AND statutobs != 'No' AND (validation = 1 OR validation = 2)
					GROUP BY obs.cdref ), sel1 AS (";
	$strQuery .= " SELECT COUNT(idobs) AS nb, cdref FROM obs.obs
					INNER JOIN obs.fiche USING(idfiche)
					INNER JOIN obs.coordonnee USING(idcoord)";
	if($droit == 'non') { $strQuery .= ' LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref'; }
	$strQuery .= " WHERE codel93 = :maille AND observa = :observa AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= ' AND (sensible <= 1 OR sensible IS NULL) AND floutage <= 1'; }
	$strQuery .= " GROUP BY cdref)";
	$strQuery .= " SELECT sel.nbt, sel1.nb, nom, nomvern, sel.cdref, ir FROM sel	
					INNER JOIN sel1 USING(cdref)
					INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref
					ORDER BY nom";
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':maille', $maille);
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function liste_statut_l935($maille,$droit,$nomvar)
{
	$strQuery = "WITH sel AS (SELECT COUNT(idobs) AS nbt, obs.cdref FROM obs.obs
					INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
					WHERE rang = 'ES' OR rang = 'SSES' AND statutobs != 'No' AND (validation = 1 OR validation = 2)
					GROUP BY obs.cdref ), sel1 AS (";
	$strQuery .= " SELECT COUNT(idobs) AS nb, cdref FROM obs.obs
					INNER JOIN obs.fiche USING(idfiche)
					INNER JOIN obs.coordonnee USING(idcoord)";
	if($droit == 'non') { $strQuery .= ' LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref'; }
	$strQuery .= " WHERE codel935 = :maille AND observa = :observa AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= ' AND (sensible <= 1 OR sensible IS NULL) AND floutage <= 1'; }
	$strQuery .= " GROUP BY cdref)";
	$strQuery .= " SELECT sel.nbt, sel1.nb, nom, nomvern, sel.cdref, ir FROM sel	
					INNER JOIN sel1 USING(cdref)
					INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref
					ORDER BY nom";
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':maille', $maille);
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function liste_statut_utm($maille,$droit,$nomvar)
{
	$strQuery = "WITH sel AS (SELECT COUNT(idobs) AS nbt, obs.cdref FROM obs.obs
					INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
					WHERE rang = 'ES' OR rang = 'SSES' AND statutobs != 'No' AND (validation = 1 OR validation = 2)
					GROUP BY obs.cdref ), sel1 AS (";
	$strQuery .= " SELECT COUNT(idobs) AS nb, cdref FROM obs.obs
					INNER JOIN obs.fiche USING(idfiche)
					INNER JOIN obs.coordonnee USING(idcoord)";
	if($droit == 'non') { $strQuery .= ' LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref'; }
	$strQuery .= " WHERE utm = :maille AND observa = :observa AND statutobs != 'No' AND (validation = 1 OR validation = 2)";
	if($droit == 'non') { $strQuery .= ' AND (sensible <= 1 OR sensible IS NULL) AND floutage <= 1'; }
	$strQuery .= " GROUP BY cdref)";
	$strQuery .= " SELECT sel.nbt, sel1.nb, nom, nomvern, sel.cdref, ir FROM sel	
					INNER JOIN sel1 USING(cdref)
					INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref
					ORDER BY nom";
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':maille', $maille);
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function liste_statut_dep($iddep,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("WITH sel AS (
							SELECT COUNT(idobs) AS nbt, obs.cdref FROM obs.obs
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							WHERE rang = 'ES' OR rang = 'SSES' AND statutobs != 'No' AND (validation = 1 OR validation = 2)
							GROUP BY obs.cdref
						), sel1 AS (
							SELECT COUNT(idobs) AS nb, cdref FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							WHERE iddep = :iddep AND observa = :observa AND statutobs != 'No' AND (validation = 1 OR validation = 2)
							GROUP BY cdref
						)
							SELECT sel.nbt, sel1.nb, nom, nomvern, sel.cdref, ir FROM sel	
							INNER JOIN sel1 USING(cdref)
							INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref
							ORDER BY nom ");
	$req->bindValue(':iddep', $iddep);
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function statut_commune($codecom,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdref, type, lr FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN statut.statut ON statut.cdnom = obs.cdref
						INNER JOIN statut.statutsite USING(cdprotect)
						WHERE codecom = :codecom AND obs.observa = :observa
						GROUP BY cdref, type, lr ");	
	$req->bindValue(':codecom', $codecom);
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function statut_dep($iddep,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdref, type, lr FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN statut.statut ON statut.cdnom = obs.cdref
						INNER JOIN statut.statutsite USING(cdprotect)
						WHERE iddep = :iddep AND obs.observa = :observa
						GROUP BY cdref, type, lr ");	
	$req->bindValue(':iddep', $iddep);
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function statut_l93($maille,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdref, type, lr FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN statut.statut ON statut.cdnom = obs.cdref
						INNER JOIN statut.statutsite USING(cdprotect)
						WHERE codel93 = :maille AND obs.observa = :observa
						GROUP BY cdref, type, lr ");
	$req->bindValue(':maille', $maille);
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function statut_l935($maille,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdref, type, lr FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN statut.statut ON statut.cdnom = obs.cdref
						INNER JOIN statut.statutsite USING(cdprotect)
						WHERE codel935 = :maille AND obs.observa = :observa
						GROUP BY cdref, type, lr ");
	$req->bindValue(':maille', $maille);
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function statut_utm($maille,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdref, type, lr FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN statut.statut ON statut.cdnom = obs.cdref
						INNER JOIN statut.statutsite USING(cdprotect)
						WHERE utm = :maille AND obs.observa = :observa
						GROUP BY cdref, type, lr ");
	$req->bindValue(':maille', $maille);
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}