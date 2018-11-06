<?php
function quarante($nomvar)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idobser, nomphoto, prenom, observateur.nom, idobs, photo.cdnom, to_char(datephoto, 'DD/MM/YYYY') AS datefr, liste.nom AS sp, nomvern, photo.observatoire FROM site.photo
						INNER JOIN referentiel.observateur USING(idobser)
						INNER JOIN referentiel.liste ON liste.cdnom = photo.cdnom
						WHERE photo.observatoire = :observa
						ORDER BY datesaisie DESC						
						LIMIT 40 ");
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultats = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultats;
}
function nbespece($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(DISTINCT obs.cdref) AS Nb FROM obs.obs 
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref 
						WHERE observa = :nomvar AND (rang = 'ES' OR rang = 'SSES') ");
	$req->bindValue(':nomvar', $nomvar);
	$req->execute();
	$nbobs = $req->fetchColumn();
	$req->closeCursor();
	return $nbobs;
}
function nbphoto($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(*) AS Nb FROM site.photo WHERE observatoire = :nomvar ");
	$req->bindValue(':nomvar', $nomvar);
	$req->execute();
	$nbphoto = $req->fetchColumn();
	$req->closeCursor();
	return $nbphoto;
}
function nbespecep($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(DISTINCT cdnom) as nb FROM site.photo WHERE observatoire = :nomvar ");
	$req->bindValue(':nomvar', $nomvar);
	$req->execute();
	$nbespecep = $req->fetchColumn();
	$req->closeCursor();
	return $nbespecep;
}
function listephoto($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT COUNT(idphoto) AS nb, nom, nomvern, cdref FROM site.photo
						INNER JOIN $nomvar.liste ON liste.cdnom = photo.cdnom
						WHERE rang = 'ES' OR rang = 'SSES'
						GROUP BY nom, nomvern, cdref
						ORDER BY nom ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function sansphoto($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT nom, nomvern, liste.cdref FROM obs.obs
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						WHERE NOT EXISTS (SELECT cdnom FROM site.photo
							WHERE photo.cdnom = liste.cdnom AND observatoire = :observa
						) AND rang = 'ES' OR rang = 'SSES' 
						ORDER BY nom ");
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function taxon($id,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT nom, cdref, liste.nomvern, rang FROM $nomvar.liste WHERE liste.cdnom = :cdnom ");
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_stade($cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT stade FROM site.photo WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_stade_idobser($cdnom,$idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT stade FROM site.photo WHERE cdnom = :cdnom AND idobser = :idobser ");
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':idobser', $idobser);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_sexe($cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT sexe FROM site.photo WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_sexe_idobser($cdnom,$idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT sexe FROM site.photo WHERE cdnom = :cdnom AND idobser = :idobser");
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':idobser', $idobser);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function phototaxon($cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idobser, datephoto, sexe, stade, nomphoto, prenom, nom, to_char(datephoto, 'DD/MM/YYYY') AS datefr FROM site.photo
						INNER JOIN referentiel.observateur USING(idobser)
						WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);
}
function phototaxon_idobser($cdnom,$idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idobser, datephoto, sexe, stade, nomphoto, prenom, nom, to_char(datephoto, 'DD/MM/YYYY') AS datefr FROM site.photo
						INNER JOIN referentiel.observateur USING(idobser)
						WHERE cdnom = :cdnom AND idobser = :idobser ");
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':idobser', $idobser);
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);
}
function recherche_famille($idfam,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT famille FROM $nomvar.famille WHERE cdnom = :cdnom ");
	$req->bindValue(':cdnom', $idfam, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function recherche_sous_famille($idsfam,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT sousfamille, famille, famille.cdnom FROM $nomvar.sousfamille 
						INNER JOIN $nomvar.famille ON famille.cdnom = sousfamille.cdsup
						WHERE sousfamille.cdnom = :cdnom ");
	$req->bindValue(':cdnom', $idsfam, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
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