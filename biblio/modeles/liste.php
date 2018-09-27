<?php
function plusauteur($idbiblio)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, prenom, prenomab, idauteur FROM biblio.plusauteur
						INNER JOIN biblio.auteurs USING(idauteur)
						WHERE idbiblio = :idbiblio ");
	$req->bindValue(':idbiblio', $idbiblio);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_observa($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idbiblio, nom, prenomab, plusauteur, titre, annee, tome, fascicule, publi FROM biblio.biblio
						INNER JOIN biblio.auteurs USING(idauteur)
						INNER JOIN biblio.biblioobserva USING(idbiblio)
						WHERE observa = :id
						ORDER BY annee DESC ");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function auteur($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, prenom FROM biblio.auteurs WHERE idauteur = :id ");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_auteur($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idbiblio, nom, prenomab, plusauteur, titre, annee, tome, fascicule, publi FROM biblio.biblio
						INNER JOIN biblio.auteurs USING(idauteur)
						LEFT JOIN biblio.plusauteur USING(idbiblio)
						WHERE biblio.idauteur = :id OR plusauteur.idauteur = :id
						ORDER BY annee DESC ");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function motcle($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT mot FROM biblio.motcle WHERE idmc = :id ");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_motcle($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idbiblio, nom, prenomab, plusauteur, titre, annee, tome, fascicule, publi FROM biblio.biblio
						INNER JOIN biblio.auteurs USING(idauteur)
						INNER JOIN biblio.bibliomc USING(idbiblio)
						WHERE idmc = :id AND typep != 'Livre' ");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function commune($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT commune FROM referentiel.commune WHERE codecom = :id ");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_commune($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idbiblio, nom, prenomab, plusauteur, titre, annee, tome, fascicule, publi FROM biblio.biblio
						INNER JOIN biblio.auteurs USING(idauteur)
						INNER JOIN biblio.bibliocom USING(idbiblio)
						WHERE codecom = :id ");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_publi($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idbiblio, nom, prenomab, plusauteur, titre, annee, tome, fascicule, publi FROM biblio.biblio
						INNER JOIN biblio.auteurs USING(idauteur)
						WHERE publi ILIKE :id ");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function taxon($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, nomvern FROM referentiel.liste WHERE cdnom = :id ");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_taxon($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT idbiblio, auteurs.nom, prenomab, plusauteur, titre, annee, tome, fascicule, publi FROM biblio.biblio
						INNER JOIN biblio.auteurs USING(idauteur)
						LEFT JOIN biblio.bibliotaxon USING(idbiblio)
						LEFT JOIN biblio.bibliofiche USING(idbiblio)
						LEFT JOIN obs.obs USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref OR liste.cdnom = bibliotaxon.cdnom
						WHERE liste.cdnom = :id ");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}