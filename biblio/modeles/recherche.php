<?php
function recherche_auteur()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT DISTINCT SUBSTR(nom, 1, 1) AS l FROM biblio.auteurs ORDER BY l ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_observa()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT observa, COUNT(*) AS nb FROM biblio.biblioobserva
						GROUP BY observa ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_mot()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT DISTINCT SUBSTR(mot, 1, 1) AS l FROM biblio.motcle 
						INNER JOIN biblio.bibliomc USING(idmc)
						INNER JOIN biblio.biblio USING(idbiblio)
						WHERE typep != 'Livre'
						ORDER BY l ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_commune()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT DISTINCT SUBSTR(commune, 1, 1) AS l FROM biblio.bibliocom 
						INNER JOIN referentiel.commune USING(codecom)
						ORDER BY l ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function cartocommune()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT codecom AS id, COUNT(bibliocom.codecom) AS nb, commune AS emp, poly, geojson FROM referentiel.commune 
						LEFT JOIN biblio.bibliocom USING(codecom)
						GROUP BY codecom, commune, poly, geojson ");	
	$carto = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $carto;
}
function recherche_publi()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT DISTINCT SUBSTR(publi, 1, 1) AS l FROM biblio.biblio ORDER BY l");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_taxon_latin()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT DISTINCT SUBSTR(nom, 1, 1) AS l FROM biblio.biblio
						LEFT JOIN biblio.bibliofiche USING(idbiblio)
						LEFT JOIN obs.obs USING(idfiche)
						LEFT JOIN biblio.bibliotaxon USING(idbiblio) 
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref OR liste.cdnom = bibliotaxon.cdnom
						ORDER BY l ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_taxon_fr()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT DISTINCT SUBSTR(nomvern, 1, 1) AS l FROM biblio.biblio
						LEFT JOIN biblio.bibliofiche USING(idbiblio)
						LEFT JOIN obs.obs USING(idfiche)
						LEFT JOIN biblio.bibliotaxon USING(idbiblio) 
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref OR liste.cdnom = bibliotaxon.cdnom
						ORDER BY l ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}