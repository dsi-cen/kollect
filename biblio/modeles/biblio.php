<?php
function recherche($idbiblio)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT titre, idauteur, nom, prenom, typep, publi, annee, tome, fascicule, page, resume, plusauteur, observa, url, plusauteur, isbn FROM biblio.biblio
						INNER JOIN biblio.auteurs USING(idauteur)
						LEFT JOIN biblio.biblioobserva USING(idbiblio)
						LEFT JOIN biblio.lienexterne USING(idbiblio)
						WHERE idbiblio = :idbiblio ");
	$req->bindValue(':idbiblio', $idbiblio);
	$req->execute();
	$biblio = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $biblio;		
}
function recherche_auteur($idbiblio)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT nom, prenom, idauteur FROM biblio.plusauteur
						INNER JOIN biblio.auteurs USING(idauteur)
						WHERE idbiblio = :idbiblio ");
	$req->bindValue(':idbiblio', $idbiblio);
	$req->execute();
	$auteur = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $auteur;
}
function commune($idbiblio)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT codecom, commune FROM biblio.bibliocom
						INNER JOIN referentiel.commune USING(codecom)
						WHERE idbiblio = :idbiblio ");
	$req->bindValue(':idbiblio', $idbiblio);
	$req->execute();
	$biblio = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $biblio;		
}
function taxon($idbiblio)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT liste.cdnom, nom, nomvern, observatoire FROM biblio.biblio
						LEFT JOIN biblio.bibliofiche USING(idbiblio)
						LEFT JOIN obs.obs USING(idfiche)
						LEFT JOIN biblio.bibliotaxon USING(idbiblio)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref OR liste.cdnom = bibliotaxon.cdnom
						WHERE idbiblio = :idbiblio
						ORDER BY nom ");
	$req->bindValue(':idbiblio', $idbiblio);
	$req->execute();
	$biblio = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $biblio;		
}
function motcle($idbiblio)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idmc, mot FROM biblio.bibliomc
						INNER JOIN biblio.motcle USING(idmc)
						WHERE idbiblio = :idbiblio
						ORDER BY mot ");
	$req->bindValue(':idbiblio', $idbiblio);
	$req->execute();
	$biblio = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $biblio;		
}