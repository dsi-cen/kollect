<?php
function listeok($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT cdnom, cdref, liste.nom, nomvern, auteur FROM $nomvar.liste
						INNER JOIN import.verifcdnom ON verifcdnom.nom = liste.nom 
						WHERE cdnom = cdref
						ORDER BY liste.nom ");
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);	
}
function listepr($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT cdnom, cdref, liste.nom, nomvern, auteur FROM $nomvar.liste
						INNER JOIN import.verifcdnom ON verifcdnom.nom = liste.nom 
						WHERE cdnom != cdref
						ORDER BY liste.nom ");
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);	
}
function listeno($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT nom FROM import.verifcdnom
						WHERE NOT EXISTS (SELECT cdnom FROM $nomvar.liste 
						WHERE (liste.nom = verifcdnom.nom))
						ORDER BY nom ");
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);	
}
function liste_import()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT to_char(dateimport, 'DD/MM/YYYY') AS datefr, nom, prenom, idobsdeb, idobsfin, descri, id FROM import.histo
						INNER JOIN site.membre ON membre.idmembre = histo.idm ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}