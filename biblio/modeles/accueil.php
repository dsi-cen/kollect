<?php
function derniere_ref()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT idbiblio, nom, prenom, plusauteur, titre, publi, annee, tome, fascicule, page, to_char(datesaisie, 'DD/MM/YYYY') AS datefr, CONCAT(string_agg(DISTINCT codecom::text, ''',''')) AS codecom FROM biblio.biblio
						INNER JOIN biblio.auteurs USING(idauteur)
						LEFT JOIN biblio.bibliocom USING(idbiblio)
						GROUP BY idbiblio, nom, prenom, plusauteur, titre, publi, annee, tome, fascicule, page
						ORDER BY datesaisie DESC LIMIT 3 ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function recherche_auteur($idbiblio)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, prenom, idauteur FROM biblio.plusauteur
						INNER JOIN biblio.auteurs USING(idauteur)
						WHERE idbiblio = :idbiblio ");
	$req->bindValue(':idbiblio', $idbiblio);
	$req->execute();
	$auteur = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $auteur;
}
function nbref()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT COUNT(*) AS Nb FROM biblio.biblio ");
	$nbref = $req->fetchColumn();
	$req->closeCursor();
	return $nbref;
}
function nbauteur()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT COUNT(*) AS Nb FROM biblio.auteurs ");
	$nbauteur = $req->fetchColumn();
	$req->closeCursor();
	return $nbauteur;
}
function nbtaxon()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT COUNT(DISTINCT cdnom) AS nb FROM biblio.bibliotaxon ");
	$nbauteur = $req->fetchColumn();
	$req->closeCursor();
	return $nbauteur;
}
function cherche_commune($codecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query('SELECT commune, codecom FROM referentiel.commune
						WHERE codecom IN('.$codecom.')
						ORDER BY commune ');
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function dernier_taxon()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT DISTINCT nom, nomvern, auteur, idbiblio, to_char(biblio.datesaisie, 'DD/MM/YYYY') AS datefr FROM biblio.biblio
						LEFT JOIN biblio.bibliofiche USING(idbiblio)
						LEFT JOIN obs.obs USING(idfiche)
						LEFT JOIN biblio.bibliotaxon USING(idbiblio)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref OR liste.cdnom = bibliotaxon.cdnom
						ORDER BY idbiblio DESC
						LIMIT 3 ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}