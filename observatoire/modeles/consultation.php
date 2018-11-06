<?php
function chercheobmembre($idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idobser, observateur.nom, observateur.prenom FROM site.membre
						LEFT JOIN referentiel.observateur ON observateur.idm = membre.idmembre
						WHERE idmembre = :idm ");
	$req->bindValue(':idm', $idm);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_typeobs($idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(idobs) AS nb, floutage FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						WHERE idobser = :idobser
						GROUP BY floutage ");
	$req->bindValue(':idobser', $idobser);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function etude()
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT idetude, etude FROM referentiel.etude WHERE masquer = 'oui' ORDER BY etude ");
	$resultats = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultats;
}
function organisme()
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT idorg, organisme FROM referentiel.organisme ORDER BY organisme ");
	$resultats = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultats;
}
function habitat()
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT cdhab, lbcode, lbhabitat FROM referentiel.eunis WHERE locale = 'oui' ORDER BY lbcode ");
	$resultats = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultats;
}
function statut($nomvar)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdprotect, intitule, article, statutsite.type FROM statut.statutsite 
						INNER JOIN statut.libelle USING(cdprotect)
						WHERE observa = :observa ");
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}