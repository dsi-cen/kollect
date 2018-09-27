<?php
function listeactu($nbactu,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idactu, titre, to_char(datecreation, 'DD/MM/YYYY') AS datefr FROM actu.actu
						WHERE theme = :theme
						ORDER BY datecreation DESC LIMIT $nbactu ");
	$req->bindValue(':theme', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll();
	$req->closeCursor();
	return $resultat;
}
function article($type)
{
	$bdd = PDO2::getInstance();		
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idarticle FROM site.article WHERE typear = :type ");
	$req->bindValue(':type', $type);
	$req->execute();
	$article = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $article;		
}
function nbobservateur($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("WITH sel AS (
							SELECT DISTINCT idobser FROM obs.fiche 
							INNER JOIN obs.obs USING(idfiche)
							WHERE observa = :nomvar
							UNION 
							SELECT DISTINCT idobser FROM obs.obs						
							LEFT JOIN obs.plusobser on plusobser.idfiche = obs.idfiche
							WHERE observa = :nomvar
						)
						SELECT COUNT(idobser) AS nb FROM sel  ");
	$req->bindValue(':nomvar', $nomvar);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
/*function nbobservateur($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(DISTINCT observateur.idobser) AS nb FROM obs.fiche 
						INNER JOIN obs.obs USING(idfiche)
						LEFT JOIN obs.plusobser USING(idfiche)
						INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser OR observateur.idobser = plusobser.idobser
						WHERE observa = :nomvar  ");
	$req->bindValue(':nomvar', $nomvar);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
WITH sel AS (
							SELECT DISTINCT idobser FROM obs.fiche 
							INNER JOIN obs.obs USING(idfiche)
							WHERE observa = 'bota'
							UNION 
							SELECT DISTINCT idobser FROM obs.obs						
							LEFT JOIN obs.plusobser on plusobser.idfiche = obs.idfiche
							WHERE observa = 'bota'
						)
						SELECT COUNT(idobser) FROM sel

function nbobservateur($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT idobser FROM obs.fiche
						INNER JOIN obs.obs USING (idfiche) 
						WHERE observa = :nomvar  ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':nomvar', $nomvar);
	$req->execute();
	$nbobs = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $nbobs;
}
function nbobservateur1($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT plusobser.idobser FROM obs.fiche
						INNER JOIN obs.obs USING (idfiche) 
						INNER JOIN obs.plusobser ON plusobser.idfiche = fiche.idfiche
						WHERE observa = :nomvar ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':nomvar', $nomvar);
	$req->execute();
	$nbobs = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $nbobs;
}*/
function nbobs($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(*) AS Nb FROM obs.obs WHERE observa = :nomvar ");
	$req->bindValue(':nomvar', $nomvar);
	$req->execute();
	$nbobs = $req->fetchColumn();
	$req->closeCursor();
	return $nbobs;
}
function nbespece($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(DISTINCT obs.cdref) AS Nb FROM obs.obs 
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref 
						WHERE observa = :nomvar AND (rang = 'ES' OR rang = 'SSES') ");
	$req->bindValue(':nomvar', $nomvar);
	$req->execute();
	$nbobs = $req->fetchColumn();
	$req->closeCursor();
	return $nbobs;
}
function nbphoto($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(*) AS Nb FROM site.photo WHERE observatoire = :nomvar ");
	$req->bindValue(':nomvar', $nomvar);
	$req->execute();
	$nbphoto = $req->fetchColumn();
	$req->closeCursor();
	return $nbphoto;
}
function nbespecep($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(DISTINCT cdnom) as nb FROM site.photo WHERE observatoire = :nomvar ");
	$req->bindValue(':nomvar', $nomvar);
	$req->execute();
	$nbespecep = $req->fetchColumn();
	$req->closeCursor();
	return $nbespecep;
}
function photo($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, prenom, nomphoto, observatoire, to_char(datephoto, 'DD/MM/YYYY') as datefr, cdnom FROM site.photo
						INNER JOIN referentiel.observateur USING(idobser)
						WHERE datesaisie <= NOW() AND observatoire = :nomvar
						ORDER BY datesaisie DESC
						LIMIT 3 ");
	$req->bindValue(':nomvar', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function decade($nomvar,$decade)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$bdd->query('SET lc_time_names = "fr_FR"');
	$req = $bdd->prepare("WITH sel AS (SELECT obs.cdref, liste.nom, nomvern, COUNT(obs.idobs) AS nb FROM obs.obs
									INNER JOIN obs.fiche USING(idfiche)
									INNER JOIN obs.ligneobs USING(idobs)
									INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
									WHERE observa = :nomvar AND decade = :decade AND rang != 'GN' AND rang != 'COM' AND idetatbio = 2
									GROUP BY obs.cdref, liste.nom, nomvern 
									ORDER BY nb DESC
									LIMIT 6 
							)								
							SELECT sel.*, nomphoto, ordre, observateur.nom AS obsern, prenom FROM sel
							LEFT JOIN site.photo ON photo.cdnom = sel.cdref
							LEFT JOIN referentiel.observateur ON photo.idobser = observateur.idobser
							WHERE ordre = 1 OR ordre IS NULL 
							ORDER BY sel.nb DESC ");
	$req->bindValue(':nomvar', $nomvar);
	$req->bindValue(':decade', $decade);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function listeobs($nomvar,$dater)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("WITH sel AS (SELECT idobs, to_char(date1, 'DD/MM/YYYY') AS datefr, date1, observa, cdref, codecom, floutage FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							WHERE date1 >= :date AND observa = :nomvar
							ORDER BY date1 DESC
							LIMIT 10
						)
						SELECT idobs, datefr, commune, iddep, nom, nomvern, observa, floutage, sensible, sel.cdref FROM sel
						INNER JOIN $nomvar.liste ON liste.cdnom = sel.cdref
						LEFT JOIN referentiel.commune ON commune.codecom = sel.codecom
						LEFT JOIN referentiel.sensible ON sensible.cdnom = sel.cdref
						ORDER BY date1 DESC ");
	$req->bindValue(':date', $dater);
	$req->bindValue(':nomvar', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}