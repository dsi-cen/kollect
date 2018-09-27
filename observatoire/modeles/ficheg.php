<?php
function recherche_fichegenre($id,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, famille.famille, cdref, liste.auteur FROM $nomvar.liste 
						INNER JOIN $nomvar.famille ON famille.cdnom = liste.famille
						WHERE liste.cdnom = :cdnom ");
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetch();
	$req->closeCursor();
	return $resultat;
}
function recherche_ranginfgenre($id,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT nom, auteur, nomvern, liste.cdnom, sensible FROM $nomvar.liste 
						INNER JOIN obs.obs ON obs.cdref = liste.cdnom
						LEFT JOIN referentiel.sensible ON sensible.cdnom = liste.cdref
						WHERE cdtaxsup = :cdnom AND rang = 'ES'
						ORDER BY nom");
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function photo($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT observateur, nomphoto, observatoire, to_char(datephoto, 'DD/MM/YYYY') AS datefr, cdnom FROM site.photo
						INNER JOIN referentiel.observateur USING(idobser)
						WHERE cdnom = :cdnom
						ORDER BY ordre
						LIMIT 3 ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function nombre_especegenre($id,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(idobs) AS nb, COUNT(DISTINCT codecom) AS nbcom, COUNT(DISTINCT codel93) AS nbmaille FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						LEFT JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord  
						WHERE cdtaxsup = :cdnom ");
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function nombre_genresp($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM obs.obs WHERE cdref = :cdnom ");
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
function complexe($id,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT com, nom FROM referentiel.similaire
						INNER JOIN $nomvar.liste ON liste.cdnom = similaire.com						
						WHERE liste.cdtaxsup = :cdnom  ");
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recup_genrecomplexe($id,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT cdtaxsup FROM $nomvar.liste
						INNER JOIN referentiel.similaire ON similaire.cdnom = liste.cdnom						
						WHERE com = :cdnom AND rang = 'ES' ");
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}