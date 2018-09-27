<?php
function table($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT table_name FROM information_schema.tables WHERE table_schema='$nomvar' AND table_name='liste'") or die(print_r($bdd->errorInfo()));
	$table = $req->rowCount();
	$req->closeCursor();
	return $table;		
}
/*function tblcat($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT table_name FROM information_schema.tables WHERE table_schema='$nomvar' AND table_name='categorie'") or die(print_r($bdd->errorInfo()));
	$table = $req->rowCount();
	$req->closeCursor();
	return $table;		
}*/
function recherche_tax($nomvar,$latin,$ordre)
{
	$bdd = PDO2::getInstance();
	if ($ordre == 'A')
	{
		$req = $bdd->query("SELECT distinct(liste.cdnom), nom, famille, liste.nomvern, liste.auteur, COUNT(idobs) AS nb, liste.rang FROM $nomvar.liste 
							INNER JOIN obs.obs ON obs.cdref = liste.cdnom
							WHERE liste.cdref = liste.cdnom AND liste.rang != 'GN' AND liste.locale = 'oui'
							GROUP BY liste.cdnom, nom, famille, liste.nomvern, liste.auteur, liste.rang
							ORDER BY $latin ");
	}
	elseif ($ordre == 'S')
	{
		$req = $bdd->query("SELECT distinct(liste.cdnom), ordre, nom, famille, liste.nomvern, liste.auteur, COUNT(idobs) AS nb, liste.rang FROM $nomvar.liste 
							LEFT JOIN $nomvar.systematique ON systematique.cdnom = liste.cdnom
							INNER JOIN obs.obs ON obs.cdref = liste.cdnom
							WHERE liste.cdref = liste.cdnom AND liste.rang != 'GN' AND liste.locale = 'oui'
							GROUP BY liste.cdnom, ordre, nom, famille, liste.nomvern, liste.auteur, liste.rang
							ORDER BY ordre, $latin ");
	}
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;	
}
function recherche_souses($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT liste.cdnom, cdtaxsup, nom, auteur, nomvern FROM $nomvar.liste 
						INNER JOIN obs.obs ON obs.cdref = liste.cdnom
						WHERE rang = 'SSES' AND liste.cdnom = liste.cdref AND locale = 'oui' ORDER BY nom");
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function recherche_sousescat($nomvar,$lcat)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("SELECT liste.cdnom, cdtaxsup, nom, auteur, nomvern FROM $nomvar.liste 
						INNER JOIN $nomvar.categorie ON categorie.famille = liste.famille
						INNER JOIN obs.obs ON obs.cdref = liste.cdnom
						WHERE rang = 'SSES' AND liste.cdnom = liste.cdref AND cat = :cat AND liste.locale = 'oui'
						ORDER BY nom ");
	$req->bindValue(':cat', $lcat);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function recherche_famille($nomvar,$ordre)
{
	$bdd = PDO2::getInstance();
	if ($ordre == 'A')
	{
		$req = $bdd->query("SELECT cdnom, famille, auteur, nomvern FROM $nomvar.famille WHERE locale = 'oui' ORDER BY famille");
	}
	elseif ($ordre == 'S')
	{
		$req = $bdd->query("SELECT famille.cdnom, famille, auteur, nomvern FROM $nomvar.famille 
							LEFT JOIN $nomvar.systematique ON systematique.cdnom = famille.cdnom
							WHERE locale = 'oui' 
							ORDER BY systematique.ordre, famille");
	}
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}