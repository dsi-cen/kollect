<?php
function photo_famille($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT famille.cdnom, famille.famille, COUNT(photo.cdnom) AS nb, COUNT(DISTINCT photo.cdnom) AS nb1 FROM $nomvar.liste
						INNER JOIN site.photo ON photo.cdnom = liste.cdnom
						INNER JOIN $nomvar.famille ON liste.famille = famille.cdnom
						GROUP BY famille.cdnom, famille.famille 
						ORDER BY famille.famille");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function photo_famille_auteur($nomvar,$idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT famille.cdnom, famille.famille, COUNT(photo.cdnom) AS nb, COUNT(DISTINCT photo.cdnom) AS nb1 FROM $nomvar.liste
						INNER JOIN site.photo ON photo.cdnom = liste.cdnom
						INNER JOIN $nomvar.famille ON liste.famille = famille.cdnom
						WHERE idobser = :idobser
						GROUP BY famille.cdnom, famille.famille
						ORDER BY famille.famille ");
	$req->bindValue(':idobser', $idobser, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function cherche_observateur($idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT nom, prenom, idm FROM referentiel.observateur WHERE idobser = :idobser ");
	$req->bindValue(':idobser', $idobser, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function recherche_photo_lettre($nomvar,$tri)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($tri == 'nom')
	{
		$req = $bdd->query("SELECT DISTINCT SUBSTR(nom, 1, 1) AS l FROM $nomvar.liste 
							INNER JOIN site.photo ON photo.cdnom = liste.cdnom
							WHERE rang = 'ES' OR rang = 'SSES'
							ORDER BY l ");
	}
	else
	{
		$req = $bdd->query("SELECT DISTINCT SUBSTR(nomvern, 1, 1) AS l FROM $nomvar.liste 
							INNER JOIN site.photo ON photo.cdnom = liste.cdnom
							WHERE rang = 'ES' OR rang = 'SSES'
							ORDER BY l ");
	}	
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_photo_lettre_auteur($nomvar,$tri,$idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($tri == 'nom')
	{
		$req = $bdd->prepare("SELECT DISTINCT SUBSTR(nom, 1, 1) AS l FROM $nomvar.liste 
							INNER JOIN site.photo ON photo.cdnom = liste.cdnom
							WHERE idobser = :idobser AND (rang = 'ES' OR rang = 'SSES')
							ORDER BY l ");
	}
	else
	{
		$req = $bdd->prepare("SELECT DISTINCT SUBSTR(nomvern, 1, 1) AS l FROM $nomvar.liste 
							INNER JOIN site.photo ON photo.cdnom = liste.cdnom
							WHERE idobser = :idobser AND (rang = 'ES' OR rang = 'SSES')
							ORDER BY l ");
	}	
	$req->bindValue(':idobser', $idobser, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_famille($fam,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT famille FROM $nomvar.famille WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $fam, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function recherche_sous_famille($sfam,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT sousfamille, famille, famille.cdnom FROM $nomvar.sousfamille 
						INNER JOIN $nomvar.famille ON famille.cdnom = sousfamille.cdsup
						WHERE sousfamille.cdnom = :cdnom ");
	$req->bindValue(':cdnom', $sfam, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function rechercher_rang($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT idrang FROM $nomvar.rang ORDER BY idrang");
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function liste_sousfamille($nomvar,$stribu,$tribu,$fam,$idobser)
{
	$strQuery = 'SELECT DISTINCT sousfamille.cdnom FROM '.$nomvar.'.liste INNER JOIN '.$nomvar.'.genre ON genre.cdnom = liste.cdtaxsup';
	if($stribu == 'oui') { $strQuery .= ' LEFT JOIN '.$nomvar.'.soustribu ON soustribu.cdnom = genre.cdsup'; }
	if($tribu == 'oui') { $strQuery .= ' LEFT JOIN '.$nomvar.'.tribu ON tribu.cdnom = genre.cdsup'; }
	if($stribu == 'oui') { $strQuery .= ' OR tribu.cdnom = soustribu.cdsup'; }
	$strQuery .= ' LEFT JOIN '.$nomvar.'.sousfamille ON sousfamille.cdnom = genre.cdsup';
	if($tribu == 'oui') { $strQuery .= ' OR sousfamille.cdnom = tribu.cdsup'; }
	$strQuery .= ' INNER JOIN site.photo ON photo.cdnom = liste.cdnom';	
	$strQuery .= " WHERE liste.famille = :cdnom AND rang = 'ES'";
	if(!empty($idobser)) { $strQuery .= " AND idobser = :idobser"; }
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdnom', $fam, PDO::PARAM_INT);
	if(!empty($idobser)) { $req->bindValue(':idobser', $idobser, PDO::PARAM_INT); }
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function photo_sousfamille_autre($nomvar,$stribu,$tribu,$fam,$idobser)
{
	$strQuery = "SELECT DISTINCT ON (liste.nom) liste.nom, liste.nomvern, liste.cdnom, nomphoto, CONCAT(prenom, ' ', observateur.nom) AS obser FROM $nomvar.liste INNER JOIN $nomvar.genre ON genre.cdnom = liste.cdtaxsup";
	if($stribu == 'oui') { $strQuery .= ' LEFT JOIN '.$nomvar.'.soustribu ON soustribu.cdnom = genre.cdsup'; }
	if($tribu == 'oui') { $strQuery .= ' LEFT JOIN '.$nomvar.'.tribu ON tribu.cdnom = genre.cdsup'; }
	if($stribu == 'oui') { $strQuery .= ' OR tribu.cdnom = soustribu.cdsup'; }
	$strQuery .= ' LEFT JOIN '.$nomvar.'.sousfamille ON sousfamille.cdnom = genre.cdsup';
	if($tribu == 'oui') { $strQuery .= ' OR sousfamille.cdnom = tribu.cdsup'; }
	$strQuery .= ' INNER JOIN site.photo ON photo.cdnom = liste.cdnom';	
	$strQuery .= ' INNER JOIN referentiel.observateur USING(idobser)';	
	$strQuery .= " WHERE liste.famille = :cdnom AND rang = 'ES' AND sousfamille.cdnom IS NULL";
	if(!empty($idobser)) { $strQuery .= " AND idobser = :idobser"; } else { $strQuery .= " AND photo.ordre = 1"; }
	$strQuery .= ' ORDER BY liste.nom';
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdnom', $fam, PDO::PARAM_INT);
	if(!empty($idobser)) { $req->bindValue(':idobser', $idobser, PDO::PARAM_INT); }
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function photo_sans_sousfamille($nomvar,$fam,$idobser)
{
	$strQuery = "SELECT DISTINCT ON (liste.nom) liste.nom, liste.nomvern, liste.cdnom, nomphoto, CONCAT(prenom, ' ', observateur.nom) AS obser FROM $nomvar.liste";
	$strQuery .= ' INNER JOIN site.photo ON photo.cdnom = liste.cdnom';	
	$strQuery .= ' INNER JOIN referentiel.observateur USING(idobser)';	
	$strQuery .= " WHERE liste.famille = :cdnom AND (rang = 'ES' OR rang = 'SSES')";
	if(!empty($idobser)) { $strQuery .= " AND idobser = :idobser"; } else { $strQuery .= " AND photo.ordre = 1"; }
	$strQuery .= ' ORDER BY liste.nom';
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdnom', $fam, PDO::PARAM_INT);
	if(!empty($idobser)) { $req->bindValue(':idobser', $idobser, PDO::PARAM_INT); }
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function photo_sousfamille($nomvar,$stribu,$tribu,$fam,$idobser)
{
	$strQuery = "SELECT DISTINCT ON (sousfamille) sousfamille, sousfamille.cdnom, nomphoto, CONCAT(prenom, ' ', observateur.nom) AS obser FROM $nomvar.liste INNER JOIN $nomvar.genre ON genre.cdnom = liste.cdtaxsup";
	if($stribu == 'oui') { $strQuery .= ' LEFT JOIN '.$nomvar.'.soustribu ON soustribu.cdnom = genre.cdsup'; }
	if($tribu == 'oui') { $strQuery .= ' LEFT JOIN '.$nomvar.'.tribu ON tribu.cdnom = genre.cdsup'; }
	if($stribu == 'oui') { $strQuery .= ' OR tribu.cdnom = soustribu.cdsup'; }
	$strQuery .= ' LEFT JOIN '.$nomvar.'.sousfamille ON sousfamille.cdnom = genre.cdsup';
	if($tribu == 'oui') { $strQuery .= ' OR sousfamille.cdnom = tribu.cdsup'; }
	$strQuery .= ' INNER JOIN site.photo ON photo.cdnom = liste.cdnom';	
	$strQuery .= ' INNER JOIN referentiel.observateur USING(idobser)';	
	$strQuery .= " WHERE liste.famille = :cdnom AND rang = 'ES' AND sousfamille.cdnom IS NOT NULL";
	if(!empty($idobser)) { $strQuery .= " AND idobser = :idobser"; } else { $strQuery .= " AND photo.ordre = 1"; }
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdnom', $fam, PDO::PARAM_INT);
	if(!empty($idobser)) { $req->bindValue(':idobser', $idobser, PDO::PARAM_INT); }
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function photo_sousfamille_espece($nomvar,$stribu,$tribu,$sfam,$idobser)
{
	$strQuery = "SELECT DISTINCT ON (liste.nom) liste.nom, liste.nomvern, liste.cdnom, nomphoto, CONCAT(prenom, ' ', observateur.nom) AS obser FROM $nomvar.liste INNER JOIN $nomvar.genre ON genre.cdnom = liste.cdtaxsup";
	if($stribu == 'oui') { $strQuery .= ' LEFT JOIN '.$nomvar.'.soustribu ON soustribu.cdnom = genre.cdsup'; }
	if($tribu == 'oui') { $strQuery .= ' LEFT JOIN '.$nomvar.'.tribu ON tribu.cdnom = genre.cdsup'; }
	if($stribu == 'oui') { $strQuery .= ' OR tribu.cdnom = soustribu.cdsup'; }
	$strQuery .= ' LEFT JOIN '.$nomvar.'.sousfamille ON sousfamille.cdnom = genre.cdsup';
	if($tribu == 'oui') { $strQuery .= ' OR sousfamille.cdnom = tribu.cdsup'; }
	$strQuery .= ' INNER JOIN site.photo ON photo.cdnom = liste.cdnom';	
	$strQuery .= ' INNER JOIN referentiel.observateur USING(idobser)';
	$strQuery .= " WHERE sousfamille.cdnom = :cdnom AND (rang = 'ES' OR rang = 'SSES')";
	if(!empty($idobser)) { $strQuery .= " AND idobser = :idobser"; } else { $strQuery .= " AND photo.ordre = 1"; }
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdnom', $sfam, PDO::PARAM_INT);
	if(!empty($idobser)) { $req->bindValue(':idobser', $idobser, PDO::PARAM_INT); }
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}