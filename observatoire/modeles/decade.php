<?php
function liste($nomvar,$decade,$latin)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, famille, stade.stade, liste.cdref FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.ligneobs USING(idobs)
						INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						WHERE decade = :dec AND (rang = 'ES' OR rang ='SSES') AND idetatbio = 2 AND (validation = 1 OR validation = 2)
						ORDER BY $latin ") or die(print_r($bdd->errorInfo()));	
	$req->bindValue(':dec', $decade);
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);
}
function listefam($nomvar,$decade)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT famille.cdnom, famille.famille, famille.nomvern FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.ligneobs USING(idobs)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						INNER JOIN $nomvar.famille ON famille.cdnom = liste.famille
						WHERE decade = :dec AND (rang = 'ES' OR rang ='SSES') AND idetatbio = 2 AND (validation = 1 OR validation = 2)
						ORDER BY famille ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':dec', $decade);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function listeobs($nomvar,$decade,$latin)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdref, COUNT(idobs) AS nb FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.ligneobs USING(idobs)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						WHERE decade = :dec AND (rang = 'ES' OR rang ='SSES') AND idetatbio = 2 AND (validation = 1 OR validation = 2)
						GROUP BY nom, nomvern, liste.cdref
						ORDER BY nb DESC, $latin ") or die(print_r($bdd->errorInfo()));	
	$req->bindValue(':dec', $decade);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function graph($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(DISTINCT cdref) AS nb, iddecade, decade.decade FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.ligneobs USING(idobs)
						INNER JOIN referentiel.decade ON decade.decade = fiche.decade
						WHERE observa = :observa AND idetatbio = 2
						GROUP BY iddecade, decade.decade ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function graphcat($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(DISTINCT obs.cdref) AS nb, iddecade, decade.decade, cat FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.ligneobs USING(idobs)
						INNER JOIN referentiel.decade ON decade.decade = fiche.decade
						INNER JOIN $nomvar.liste ON liste.cdref = obs.cdref
						INNER JOIN $nomvar.categorie ON categorie.famille = liste.famille
						WHERE observa = :observa AND idetatbio = 2
						GROUP BY iddecade, decade.decade, cat ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function photo($nomvar,$decade,$debut)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("WITH sel AS (
							SELECT obs.cdref, liste.nom, nomvern, COUNT(obs.idobs) AS nb, stade AS idstade FROM obs.obs
									INNER JOIN obs.fiche USING(idfiche)
									INNER JOIN obs.ligneobs USING(idobs)
									INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
									WHERE observa = :observa AND decade = :dec AND rang != 'GN' AND idetatbio = 2  AND (validation = 1 OR validation = 2)
									GROUP BY obs.cdref, liste.nom, nomvern, stade 
									ORDER BY nb DESC
									LIMIT 20 OFFSET :deb
							)								
							SELECT sel.*, nomphoto, ordre, observateur.nom AS obsern, prenom, stade.stade FROM sel
							LEFT JOIN site.photo ON photo.cdnom = sel.cdref AND photo.stade = sel.idstade
							LEFT JOIN referentiel.observateur ON photo.idobser = observateur.idobser
							INNER JOIN referentiel.stade ON stade.idstade = sel.idstade
							WHERE ordre = 1 OR ordre IS NULL 
							ORDER BY sel.nb DESC ") or die(print_r($bdd->errorInfo()));	
	$req->bindValue(':deb', $debut);
	$req->bindValue(':observa', $nomvar);
	$req->bindValue(':dec', $decade);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function photocat($nomvar,$decade,$debut,$cat)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("WITH sel AS (
							SELECT obs.cdref, liste.nom, nomvern, COUNT(obs.idobs) AS nb, stade AS idstade FROM obs.obs
									INNER JOIN obs.fiche USING(idfiche)
									INNER JOIN obs.ligneobs USING(idobs)
									INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
									INNER JOIN $nomvar.categorie ON categorie.famille = liste.famille
									WHERE observa = :observa AND decade = :dec AND cat = :cat AND rang != 'GN' AND idetatbio = 2  AND (validation = 1 OR validation = 2)
									GROUP BY obs.cdref, liste.nom, nomvern, stade 
									ORDER BY nb DESC
									LIMIT 20 OFFSET :deb
							)								
							SELECT sel.*, nomphoto, ordre, observateur.nom AS obsern, prenom, stade.stade FROM sel
							LEFT JOIN site.photo ON photo.cdnom = sel.cdref AND photo.stade = sel.idstade
							LEFT JOIN referentiel.observateur ON photo.idobser = observateur.idobser
							INNER JOIN referentiel.stade ON stade.idstade = sel.idstade
							WHERE ordre = 1 OR ordre IS NULL 
							ORDER BY sel.nb DESC ") or die(print_r($bdd->errorInfo()));	
	$req->bindValue(':deb', $debut);
	$req->bindValue(':observa', $nomvar);
	$req->bindValue(':dec', $decade);
	$req->bindValue(':cat', $cat);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function nb_sp($nomvar,$decade)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(DISTINCT obs.cdref) AS nb FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.ligneobs USING(idobs)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						WHERE decade = :dec AND (rang = 'ES' OR rang ='SSES') AND idetatbio = 2 AND (validation = 1 OR validation = 2) ") or die(print_r($bdd->errorInfo()));	
	$req->bindValue(':dec', $decade);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
function nb_spcat($nomvar,$decade,$cat)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(DISTINCT obs.cdref) AS nb FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.ligneobs USING(idobs)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						INNER JOIN $nomvar.categorie ON categorie.famille = liste.famille
						WHERE decade = :dec AND cat = :cat AND (rang = 'ES' OR rang ='SSES') AND idetatbio = 2 AND (validation = 1 OR validation = 2) ") or die(print_r($bdd->errorInfo()));	
	$req->bindValue(':dec', $decade);
	$req->bindValue(':cat', $cat);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}