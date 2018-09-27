<?php
function rechercher_rang($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT idrang, rang FROM $nomvar.rang ORDER BY idrang");
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function recherche_sfamille($cdnom,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT sousfamille, famille, famille.cdnom FROM $nomvar.sousfamille
						INNER JOIN $nomvar.famille ON famille.cdnom = sousfamille.cdsup
						WHERE sousfamille.cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnom, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_famille($cdnom,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT famille FROM $nomvar.famille WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnom, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_sousfamille($nomvar,$cdnom,$afflatin,$sgenre,$stribu,$tribu)
{
	$strQuery = "SELECT DISTINCT liste.cdnom, liste.cdsup, nom, genre.genre, genre.cdnom AS cdnomgenre, liste.nomvern, liste.auteur, COUNT(idobs) AS nb,";
	$strQuery .= ($stribu == 'oui') ? " soustribu," : " '' AS soustribu,";
	$strQuery .= ($tribu == 'oui') ? " tribu," : " '' AS tribu,";
	$strQuery .= ($sgenre == 'oui') ? " sousgenre," : " '' AS sousgenre,";
	$strQuery .= " rang FROM $nomvar.liste";
	$strQuery .= " LEFT JOIN $nomvar.genre ON genre.genre = liste.genre";
	if($sgenre == 'oui') { $strQuery .= " LEFT JOIN $nomvar.sousgenre ON sousgenre.cdnom = liste.cdsup"; }
	if($stribu == 'oui') { $strQuery .= " LEFT JOIN $nomvar.soustribu ON soustribu.cdnom = genre.cdsup"; }
	if($tribu == 'oui') { $strQuery .= ($stribu == 'oui') ? " LEFT JOIN $nomvar.tribu ON tribu.cdnom = soustribu.cdsup OR tribu.cdnom = genre.cdsup" : " LEFT JOIN $nomvar.tribu ON tribu.cdnom = genre.cdsup"; }
	$strQuery .= ($tribu == 'oui') ? " LEFT JOIN $nomvar.sousfamille ON sousfamille.cdnom = genre.cdsup OR sousfamille.cdnom = tribu.cdsup" : " LEFT JOIN $nomvar.sousfamille ON sousfamille.cdnom = genre.cdsup";
	$strQuery .= " INNER JOIN obs.obs ON obs.cdref = liste.cdnom";
	$strQuery .= " WHERE sousfamille.cdnom = :cdnom AND liste.cdref = liste.cdnom";
	$strQuery .= " GROUP BY liste.cdnom, liste.nom, liste.nomvern, liste.auteur, genre.genre, genre.cdnom, soustribu, tribu, sousgenre";
	$strQuery .= ($afflatin == 'oui') ? " ORDER BY genre, nom" : " ORDER BY genre, nomvern";
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdnom', $cdnom, PDO::PARAM_INT);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;
	//return $strQuery;
}
function recherche_sfstfam($nomvar,$stribu,$tribu,$cdnom)
{
	$strQuery = 'SELECT DISTINCT sousfamille.cdnom FROM '.$nomvar.'.liste INNER JOIN '.$nomvar.'.genre ON genre.cdnom = liste.cdtaxsup';
	if($stribu == 'oui') { $strQuery .= ' LEFT JOIN '.$nomvar.'.soustribu ON soustribu.cdnom = genre.cdsup'; }
	if($tribu == 'oui') { $strQuery .= ' LEFT JOIN '.$nomvar.'.tribu ON tribu.cdnom = genre.cdsup'; }
	if($stribu == 'oui') { $strQuery .= ' OR tribu.cdnom = soustribu.cdsup'; }
	$strQuery .= ' LEFT JOIN '.$nomvar.'.sousfamille ON sousfamille.cdnom = genre.cdsup';
	if($tribu == 'oui') { $strQuery .= ' OR sousfamille.cdnom = tribu.cdsup'; }
	$strQuery .= " WHERE liste.famille = :cdnom AND rang = 'ES'";
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdnom', $cdnom, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function liste_sousfamille_autre($nomvar,$stribu,$tribu,$cdnom,$afflatin)
{
	$strQuery = "SELECT DISTINCT liste.nom, liste.nomvern, liste.auteur, liste.cdnom FROM $nomvar.liste INNER JOIN $nomvar.genre ON genre.cdnom = liste.cdtaxsup";
	if($stribu == 'oui') { $strQuery .= ' LEFT JOIN '.$nomvar.'.soustribu ON soustribu.cdnom = genre.cdsup'; }
	if($tribu == 'oui') { $strQuery .= ' LEFT JOIN '.$nomvar.'.tribu ON tribu.cdnom = genre.cdsup'; }
	if($stribu == 'oui') { $strQuery .= ' OR tribu.cdnom = soustribu.cdsup'; }
	$strQuery .= ' LEFT JOIN '.$nomvar.'.sousfamille ON sousfamille.cdnom = genre.cdsup';
	if($tribu == 'oui') { $strQuery .= ' OR sousfamille.cdnom = tribu.cdsup'; }
	$strQuery .= " INNER JOIN obs.obs ON obs.cdref = liste.cdnom";
	$strQuery .= " WHERE liste.famille = :cdnom AND rang = 'ES' AND sousfamille.cdnom IS NULL";
	$strQuery .= ($afflatin == 'oui') ? ' ORDER BY liste.nom' : ' ORDER BY liste.nomvern';
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdnom', $cdnom, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function liste_sousfamille($nomvar,$stribu,$tribu,$cdnom,$afflatin)
{
	$strQuery = "SELECT DISTINCT sousfamille, liste.nom, liste.nomvern, liste.auteur, liste.cdnom, sousfamille.cdnom AS sbfm FROM $nomvar.liste INNER JOIN $nomvar.genre ON genre.cdnom = liste.cdtaxsup";
	if($stribu == 'oui') { $strQuery .= ' LEFT JOIN '.$nomvar.'.soustribu ON soustribu.cdnom = genre.cdsup'; }
	if($tribu == 'oui') { $strQuery .= ' LEFT JOIN '.$nomvar.'.tribu ON tribu.cdnom = genre.cdsup'; }
	if($stribu == 'oui') { $strQuery .= ' OR tribu.cdnom = soustribu.cdsup'; }
	$strQuery .= ' LEFT JOIN '.$nomvar.'.sousfamille ON sousfamille.cdnom = genre.cdsup';
	if($tribu == 'oui') { $strQuery .= ' OR sousfamille.cdnom = tribu.cdsup'; }
	$strQuery .= " INNER JOIN obs.obs ON obs.cdref = liste.cdnom";
	$strQuery .= " WHERE liste.famille = :cdnom AND rang = 'ES' AND sousfamille.cdnom IS NOT NULL";
	$strQuery .= ($afflatin == 'oui') ? ' ORDER BY sousfamille, liste.nom' : ' ORDER BY sousfamille, liste.nomvern';
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdnom', $cdnom, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function liste_sans_sousfamille($nomvar,$cdnom,$afflatin)
{
	$strQuery = "SELECT DISTINCT liste.nom, liste.nomvern, liste.cdnom, liste.auteur FROM $nomvar.liste INNER JOIN obs.obs ON obs.cdref = liste.cdnom WHERE liste.famille = :cdnom AND rang = 'ES'";
	$strQuery .= ($afflatin == 'oui') ? ' ORDER BY liste.nom' : ' ORDER BY liste.nomvern';
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdnom', $cdnom, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
/*SELECT liste.nom, liste.nomvern, liste.cdnom, COUNT(idobs) AS nb FROM lepido.liste
INNER JOIN lepido.genre ON genre.cdnom = liste.cdtaxsup
LEFT JOIN lepido.soustribu ON soustribu.cdnom = genre.cdsup
LEFT JOIN lepido.tribu ON tribu.cdnom = genre.cdsup OR tribu.cdnom = soustribu.cdsup
LEFT JOIN lepido.sousfamille ON sousfamille.cdnom = genre.cdsup OR sousfamille.cdnom = tribu.cdsup
INNER JOIN obs.obs ON obs.cdref = liste.cdnom
WHERE liste.famille = 185249 AND rang = 'ES' AND sousfamille.cdnom IS NULL
GROUP BY liste.nom, liste.nomvern, liste.cdnom
ORDER BY liste.nom

SELECT DISTINCT sousfamille, liste.nom, liste.nomvern, liste.cdnom, sousfamille.cdnom AS sbfm FROM lepido.liste
INNER JOIN lepido.genre ON genre.cdnom = liste.cdtaxsup
LEFT JOIN lepido.soustribu ON soustribu.cdnom = genre.cdsup
LEFT JOIN lepido.tribu ON tribu.cdnom = genre.cdsup OR tribu.cdnom = soustribu.cdsup
LEFT JOIN lepido.sousfamille ON sousfamille.cdnom = genre.cdsup OR sousfamille.cdnom = tribu.cdsup
INNER JOIN obs.obs ON obs.cdref = liste.cdnom
WHERE liste.famille = 185249 AND rang = 'ES' AND sousfamille.cdnom IS NOT NULL
ORDER BY liste.nom
*/