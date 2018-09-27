<?php
/*WITH sel AS (SELECT obs.cdref, liste.nom, nomvern, COUNT(obs.idobs) AS nb, nomphoto, ordre, observateur.nom AS obsern, prenom, observa FROM obs.obs
									INNER JOIN obs.fiche USING(idfiche)
									INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
									LEFT JOIN site.photo ON photo.cdnom = obs.cdref
									LEFT JOIN referentiel.observateur ON photo.idobser = observateur.idobser
									WHERE decade = 'N2' AND rang != 'GN' 
									GROUP BY obs.cdref, liste.nom, nomvern, nomphoto, ordre, observateur.nom, prenom, observa
							)								
							SELECT sel.* FROM sel
							WHERE ordre = 1 OR ordre IS NULL*/
function listeactu($nbactu)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT idactu, titre, soustitre, to_char(datecreation, 'DD/MM/YYYY') AS datefr, theme, datecreation FROM actu.actu						
						WHERE visible = 1
						ORDER BY datecreation DESC LIMIT $nbactu ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
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
function nbobservateur()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT COUNT(*) AS Nb FROM referentiel.observateur WHERE (aff != 'non' OR aff IS NULL) ");
	$nbobs = $req->fetchColumn();
	$req->closeCursor();
	return $nbobs;
}
function nbobs()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT COUNT(*) AS Nb FROM obs.obs ");
	$nbobs = $req->fetchColumn();
	$req->closeCursor();
	return $nbobs;
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
function nbphoto()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT COUNT(*) AS nb FROM site.photo ");
	$nbphoto = $req->fetchColumn();
	$req->closeCursor();
	return $nbphoto;
}
function photo()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$bdd->query('SET lc_time_names = "fr_FR"');
	$req = $bdd->query("SELECT observateur.nom, prenom, nomphoto, photo.observatoire, to_char(datephoto, 'DD/MM/YYYY') as datefr, cdnom, idobs, liste.nom AS lat, nomvern FROM site.photo
						INNER JOIN referentiel.observateur USING(idobser)
						INNER JOIN referentiel.liste USING(cdnom)
						WHERE datesaisie <= NOW()
						ORDER BY datesaisie DESC
						LIMIT 5  ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
/*function listeobs()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT idobs, to_char(date1, 'DD/MM/YYYY') AS datefr, commune, liste.nom, nomvern, observa, idobs, fiche.iddep, floutage, sensible FROM obs.fiche
						INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
						LEFT JOIN referentiel.commune ON commune.codecom = fiche.codecom
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref
						ORDER BY date1 DESC, liste.nom
						LIMIT 5 ") or die(print_r($bdd->errorInfo()));
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}*/
function listeobs($dater)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("WITH sel AS (SELECT idobs, to_char(date1, 'DD/MM/YYYY') AS datefr, observa, cdref FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							WHERE date1 >= :date
							ORDER BY date1 DESC
							LIMIT 5
						)
						SELECT idobs, datefr, nom, nomvern, observa FROM sel
						INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref ");
	$req->bindValue(':date', $dater);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function decade($decade)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT * FROM  (
							WITH sel AS (SELECT obs.cdref, liste.nom, nomvern, COUNT(obs.idobs) AS nb, observa, ROW_NUMBER() OVER(PARTITION BY observa ORDER BY COUNT(obs.idobs) DESC) AS r FROM obs.obs
									INNER JOIN obs.fiche USING(idfiche)
									INNER JOIN obs.ligneobs USING(idobs)
									INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
									WHERE decade = :decade AND rang != 'GN' AND rang != 'COM' AND idetatbio = 2 
									GROUP BY obs.cdref, liste.nom, nomvern, observa
							)								
							SELECT sel.*, nomphoto, ordre, observateur.nom AS obsern, prenom FROM sel
							LEFT JOIN site.photo ON photo.cdnom = sel.cdref
							LEFT JOIN referentiel.observateur ON photo.idobser = observateur.idobser
							WHERE ordre = 1 OR ordre IS NULL ) AS t
						WHERE r = 1 ");
	$req->bindValue(':decade', $decade);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
// RLE ajout comptage des études
function nbetudes()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT COUNT(*) AS nb FROM referentiel.etude ");
	$nbetudes = $req->fetchColumn();
	$req->closeCursor();
	return $nbetudes;
}
// RLE ajout comptage biblio
function nbbiblio()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT COUNT(*) AS nb FROM biblio.biblio");
	$nbbib = $req->fetchColumn();
	$req->closeCursor();
	return $nbbib;
}
// RLE ajout comptage données publiques
function nbdonneespub()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("WITH select_typedon as (select obs.*, fiche.typedon from obs.obs join obs.fiche on obs.idfiche = fiche.idfiche) select count(*) as  nb from select_typedon where typedon = 'Pu'");
	$nbpub = $req->fetchColumn();
	$req->closeCursor();
	return $nbpub;
}
// RLE ajout comptage données privées
function nbdonneespriv()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("WITH select_typedon as (select obs.*, fiche.typedon from obs.obs join obs.fiche on obs.idfiche = fiche.idfiche) select count(*) as  nb from select_typedon where typedon = 'Pr'");
	$nbpriv = $req->fetchColumn();
	$req->closeCursor();
	return $nbpriv;
}