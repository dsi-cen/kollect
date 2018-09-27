<?php
function nouveau($nomvar)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("WITH sel AS (SELECT MIN(date1) AS prem, cdref FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							WHERE observa = :observa
							GROUP BY cdref
						)
						SELECT idobs, fiche.idfiche, to_char(sel.prem, 'DD/MM/YYYY') AS datefr, sel.cdref, liste.nom, liste.nomvern, EXTRACT(YEAR FROM date1) AS annee, plusobser, fiche.idobser, observateur.nom AS nomobser, prenom, famille.famille, iddet FROM sel
						INNER JOIN obs.fiche ON fiche.date1 = sel.prem
						INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche AND obs.cdnom = sel.cdref
						INNER JOIN $nomvar.liste ON liste.cdnom = sel.cdref
						INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
						INNER JOIN $nomvar.famille ON famille.cdnom = liste.famille
						WHERE rang = 'ES' OR rang = 'SSES' ");
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function nouveauannee($nomvar)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("WITH sel AS (SELECT MIN(date1) AS prem, liste.cdref FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							WHERE observa = :observa AND rang = 'ES' OR rang = 'SSES'
							GROUP BY liste.cdref
						)
						SELECT EXTRACT(YEAR FROM sel.prem) AS annee, COUNT(cdref) AS nb FROM sel
						GROUP BY annee 
						ORDER BY annee DESC ");
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function nouveaufamille($nomvar)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("WITH sel AS (SELECT MIN(date1) AS prem, cdref FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							WHERE observa = :observa
							GROUP BY cdref
						)
						SELECT DISTINCT EXTRACT(YEAR FROM sel.prem) AS annee, famille.famille, famille.cdnom FROM sel
						INNER JOIN obs.fiche ON fiche.date1 = sel.prem
						INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche AND obs.cdnom = sel.cdref
						INNER JOIN $nomvar.liste ON liste.cdnom = sel.cdref
						INNER JOIN $nomvar.famille ON famille.cdnom = liste.famille
						WHERE rang = 'ES' OR rang = 'SSES'
						ORDER BY famille ");
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function cherche_observateur($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, prenom, observateur.idobser FROM obs.plusobser
						INNER JOIN referentiel.observateur ON observateur.idobser = plusobser.idobser
						WHERE idfiche = :idfiche
						ORDER BY idplus ");
	$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
	$req->execute();
	$obsplus = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $obsplus;
}
function determinateur($iddet)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, prenom FROM referentiel.observateur WHERE idobser = :iddet");
	$req->bindValue(':iddet', $iddet);
	$req->execute();
	$det = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $det;
}