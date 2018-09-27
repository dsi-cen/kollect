<?php
function rechercher_rang($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT idrang, rang FROM $nomvar.rang ORDER BY idrang");
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function recherche_fiche($id,$nomvar,$sytema)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($sytema == 'oui')
	{
		$req = $bdd->prepare("SELECT nom, genre, espece, famille.famille, cdref, liste.auteur, liste.nomvern, liste.rang, liste.cdtaxsup, systematique.ordre, sensible, famille.cdnom AS cdnomf, url, infosp.cdnom AS info FROM $nomvar.liste 
							INNER JOIN $nomvar.famille ON famille.cdnom = liste.famille
							LEFT JOIN $nomvar.systematique ON systematique.cdnom = liste.cdnom
							LEFT JOIN referentiel.sensible ON sensible.cdnom = liste.cdref
							LEFT JOIN referentiel.infosp ON infosp.cdnom = liste.cdnom
							WHERE liste.cdnom = :cdnom ");
	}
	else
	{
		$req = $bdd->prepare("SELECT nom, genre, espece, famille.famille, cdref, liste.auteur, liste.nomvern, rang, liste.cdtaxsup, sensible, famille.cdnom AS cdnomf, url, infosp.cdnom AS info FROM $nomvar.liste 
							INNER JOIN $nomvar.famille ON famille.cdnom = liste.famille
							LEFT JOIN referentiel.sensible ON sensible.cdnom = liste.cdref
							LEFT JOIN referentiel.infosp ON infosp.cdnom = liste.cdnom
							WHERE liste.cdnom = :cdnom ");
	}
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_sfamille_sfst($cdnomg,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT sousfamille, sousfamille.cdnom FROM $nomvar.genre
						LEFT JOIN $nomvar.soustribu ON soustribu.cdnom = genre.cdsup
						LEFT JOIN $nomvar.tribu ON tribu.cdnom = genre.cdsup OR tribu.cdnom = soustribu.cdsup
						LEFT JOIN $nomvar.sousfamille ON sousfamille.cdnom = genre.cdsup OR sousfamille.cdnom = tribu.cdsup
						WHERE genre.cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnomg, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_sfamille_sft($cdnomg,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT sousfamille, sousfamille.cdnom FROM $nomvar.genre
						LEFT JOIN $nomvar.tribu ON tribu.cdnom = genre.cdsup
						LEFT JOIN $nomvar.sousfamille ON sousfamille.cdnom = genre.cdsup OR sousfamille.cdnom = tribu.cdsup
						WHERE genre.cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnomg, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_sfamille_sf($cdnomg,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT sousfamille, sousfamille.cdnom FROM $nomvar.genre
						LEFT JOIN $nomvar.sousfamille ON sousfamille.cdnom = genre.cdsup
						WHERE genre.cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnomg, PDO::PARAM_INT);
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
function recherche_syno($id,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, auteur FROM $nomvar.liste WHERE (cdref = :cdnom AND cdnom != :cdnom) AND rang = 'ES' ");
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_ranginf($id,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT nom, auteur, nomvern, liste.cdnom FROM $nomvar.liste 
						INNER JOIN obs.obs ON obs.cdref = liste.cdnom
						WHERE cdsup = :cdnom
						ORDER BY nom");
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_gen($id,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT gen1, gen2 FROM $nomvar.systematique WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function nombre_espece5($id,$nomvar,$rang)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($rang == 'ES')
	{
		$req = $bdd->prepare("SELECT COUNT(idobs) AS nb, COUNT(DISTINCT codecom) AS nbcom, COUNT(DISTINCT codel93) AS nbmaille10, COUNT(DISTINCT codel935) AS nbmaille5 FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						LEFT JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord  
						WHERE obs.cdref = :cdnom OR cdsup = :cdnom ");
	}
	elseif($rang == 'SSES')
	{
		$req = $bdd->prepare("SELECT COUNT(idobs) AS nb, COUNT(DISTINCT codecom) AS nbcom, COUNT(DISTINCT codel93) AS nbmaille10, COUNT(DISTINCT codel935) AS nbmaille5 FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						LEFT JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord  
						WHERE cdref = :cdnom ");
		
	}	
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function nombre_espece($id,$nomvar,$rang)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($rang == 'ES')
	{
		$req = $bdd->prepare("SELECT COUNT(idobs) AS nb, COUNT(DISTINCT codecom) AS nbcom, COUNT(DISTINCT codel93) AS nbmaille10 FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						LEFT JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord  
						WHERE obs.cdref = :cdnom OR cdsup = :cdnom ");
	}
	elseif($rang == 'SSES')
	{
		$req = $bdd->prepare("SELECT COUNT(idobs) AS nb, COUNT(DISTINCT codecom) AS nbcom, COUNT(DISTINCT codel93) AS nbmaille10 FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						LEFT JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord  
						WHERE cdref = :cdnom ");
		
	}	
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function nombre_especefr($id,$nomvar,$rang)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($rang == 'ES')
	{
		$req = $bdd->prepare("SELECT COUNT(idobs) AS nb, COUNT(DISTINCT iddep) AS nbcom, COUNT(DISTINCT codel93) AS nbmaille10 FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						LEFT JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord  
						WHERE obs.cdref = :cdnom OR cdsup = :cdnom ");
	}
	elseif($rang == 'SSES')
	{
		$req = $bdd->prepare("SELECT COUNT(idobs) AS nb, COUNT(DISTINCT iddep) AS nbcom, COUNT(DISTINCT codel93) AS nbmaille10 FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						LEFT JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord  
						WHERE cdref = :cdnom ");
		
	}	
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
/*
SELECT observateur, nomphoto, observatoire, to_char(datephoto, 'DD/MM/YYYY') AS datefr FROM site.photo
						INNER JOIN referentiel.observateur USING(idobser)
						INNER JOIN obs.obs USING(idobs)
						INNER JOIN lepido.liste ON liste.cdref = photo.cdnom
						WHERE liste.cdref = 219821 AND (validation = 1 OR validation = 2)
						ORDER BY ordre
						LIMIT 3
						*/
function photo($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT observateur, nomphoto, observatoire, to_char(datephoto, 'DD/MM/YYYY') AS datefr FROM site.photo
						INNER JOIN referentiel.observateur USING(idobser)
						INNER JOIN obs.obs USING(idobs)
						WHERE photo.cdnom = :cdnom AND (validation = 1 OR validation = 2)
						ORDER BY ordre
						LIMIT 3 ");
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function suiv_prec($nom,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("(SELECT liste.cdnom, nom, 'ap' AS sens FROM $nomvar.liste 
							INNER JOIN obs.obs ON obs.cdref = liste.cdnom
							WHERE nom > :nom AND liste.cdref = liste.cdnom AND (rang = 'ES' OR rang = 'SSES')
							ORDER BY nom ASC LIMIT 1)
							UNION 
							(SELECT liste.cdnom, nom, 'av' AS sens FROM $nomvar.liste 
							INNER JOIN obs.obs ON obs.cdref = liste.cdnom
							WHERE nom < :nom AND liste.cdref = liste.cdnom AND (rang = 'ES' OR rang = 'SSES')
							ORDER BY nom DESC LIMIT 1) ");
	$req->bindValue(':nom', $nom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function suiv_precV($nom,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("(SELECT liste.cdnom, nomvern AS nom, 'ap' AS sens FROM $nomvar.liste 
							INNER JOIN obs.obs ON obs.cdref = liste.cdnom
							WHERE nomvern > :nom AND liste.cdref = liste.cdnom AND (rang = 'ES' OR rang = 'SSES')
							ORDER BY nom ASC LIMIT 1)
							UNION 
							(SELECT liste.cdnom, nomvern AS nom, 'av' AS sens FROM $nomvar.liste 
							INNER JOIN obs.obs ON obs.cdref = liste.cdnom
							WHERE nomvern < :nom AND liste.cdref = liste.cdnom AND (rang = 'ES' OR rang = 'SSES')
							ORDER BY nom DESC LIMIT 1) ");
	$req->bindValue(':nom', $nom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function precedent($ordre,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, liste.cdnom FROM $nomvar.liste
						INNER JOIN $nomvar.systematique ON systematique.cdnom = liste.cdnom
						INNER JOIN obs.obs ON obs.cdref = liste.cdnom
						WHERE ordre < :ordre
						ORDER BY ordre DESC
						LIMIT 1 ");
	$req->bindValue(':ordre', $ordre);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function suivant($ordre,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, liste.cdnom FROM $nomvar.liste
						INNER JOIN $nomvar.systematique ON systematique.cdnom = liste.cdnom
						INNER JOIN obs.obs ON obs.cdref = liste.cdnom
						WHERE ordre > :ordre
						ORDER BY ordre ASC
						LIMIT 1 ");
	$req->bindValue(':ordre', $ordre);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function habitat($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM obs.obshab WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
function recherche_statut($id,$cdprotect,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT type, lr, intitule FROM statut.statut
						INNER JOIN statut.libelle USING(cdprotect)
						INNER JOIN $nomvar.liste ON liste.cdnom = statut.cdnom
						WHERE cdref = :cdnom AND cdprotect IN($cdprotect) ");
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_indice($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT ir FROM referentiel.liste WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
function lepinet($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT lepinet FROM lien.lepinet WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
function nbgenre($cdnomg,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(DISTINCT liste.cdnom) FROM $nomvar.liste 
						INNER JOIN obs.obs ON obs.cdref = liste.cdnom
						WHERE cdtaxsup = :cdnom OR obs.cdref = :cdnom ");
	$req->bindValue(':cdnom', $cdnomg);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
function recherche_simi($id,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT simi, nom, nomvern FROM referentiel.similaire 
						INNER JOIN $nomvar.liste ON liste.cdnom = similaire.simi
						WHERE similaire.cdnom = :cdnom ");
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function biblio($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT count(*) FROM biblio.biblio
						LEFT JOIN biblio.bibliofiche USING(idbiblio)
						LEFT JOIN obs.obs USING(idfiche)
						LEFT JOIN biblio.bibliotaxon USING(idbiblio)
						WHERE cdref = :cdnom OR bibliotaxon.cdnom = :cdnom ");
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}