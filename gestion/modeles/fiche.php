<?php
function recherche_fiche($id,$nomvar,$sytema)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($sytema == 'oui')
	{
		$req = $bdd->prepare("SELECT nom, famille.famille, cdref, liste.auteur, liste.nomvern, liste.rang, liste.cdsup, systematique.ordre, sensible, famille.cdnom AS cdnomf, liste.locale FROM $nomvar.liste 
							INNER JOIN $nomvar.famille ON famille.cdnom = liste.famille
							LEFT JOIN $nomvar.systematique ON systematique.cdnom = liste.cdnom
							LEFT JOIN referentiel.sensible ON sensible.cdnom = liste.cdref
							WHERE liste.cdnom = :cdnom ");
	}
	else
	{
		$req = $bdd->prepare("SELECT nom, famille.famille, cdref, liste.auteur, liste.nomvern, rang, liste.cdsup, sensible, famille.cdnom AS cdnomf, liste.locale FROM $nomvar.liste 
							INNER JOIN $nomvar.famille ON famille.cdnom = liste.famille
							LEFT JOIN referentiel.sensible ON sensible.cdnom = liste.cdref
							WHERE liste.cdnom = :cdnom ");
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_sup($id,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT cdtaxsup FROM $nomvar.liste WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
function rechercher_rang($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT idrang, rang FROM $nomvar.rang ORDER BY idrang");
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function recherche_taxo1($id,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT sousgenre, genre.genre, soustribu, tribu, sousfamille from $nomvar.liste
						LEFT JOIN $nomvar.sousgenre ON liste.cdsup = sousgenre.cdnom
						INNER JOIN $nomvar.genre ON liste.cdtaxsup = genre.cdnom
						LEFT JOIN $nomvar.soustribu ON genre.cdsup = soustribu.cdnom
						LEFT JOIN $nomvar.tribu ON tribu.cdnom = soustribu.cdsup OR tribu.cdnom = genre.cdsup
						LEFT JOIN $nomvar.sousfamille ON sousfamille.cdnom = tribu.cdsup OR sousfamille.cdnom = genre.cdsup
						WHERE liste.cdnom = :cdnom ");
	$req->bindValue(':cdnom', $id, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_taxo2($id,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT sousgenre, genre.genre, tribu, sousfamille from $nomvar.liste
						LEFT JOIN $nomvar.sousgenre ON liste.cdsup = sousgenre.cdnom
						INNER JOIN $nomvar.genre ON liste.cdtaxsup = genre.cdnom
						LEFT JOIN $nomvar.tribu ON tribu.cdnom = genre.cdsup
						LEFT JOIN $nomvar.sousfamille ON sousfamille.cdnom = tribu.cdsup OR sousfamille.cdnom = genre.cdsup
						WHERE liste.cdnom = :cdnom ");
	$req->bindValue(':cdnom', $id, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_taxo3($id,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT genre.genre, tribu, sousfamille from mam.liste
						INNER JOIN mam.genre ON liste.cdtaxsup = genre.cdnom
						LEFT JOIN mam.tribu ON tribu.cdnom = genre.cdsup
						LEFT JOIN mam.sousfamille ON sousfamille.cdnom = tribu.cdsup OR sousfamille.cdnom = genre.cdsup
						WHERE liste.cdnom = :cdnom ");
	$req->bindValue(':cdnom', $id, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}