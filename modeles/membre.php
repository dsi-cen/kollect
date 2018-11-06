<?php
function cherche_membre($idm)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT contact, mail FROM site.membre 
						LEFT JOIN site.prefmembre USING(idmembre)
						WHERE idmembre = :idm ");
	$req->bindValue(':idm', $idm);
	$req->execute();
	$membre = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $membre;		
}
function cherche_notif($idm)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(idnotif) AS nb, type, idtype FROM site.notif 
						WHERE idm = :idm 
						GROUP BY idtype, type
						ORDER BY idtype");
	$req->bindValue(':idm', $idm);
	$req->execute();
	$notif = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $notif;		
}
function typedon($idm)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT typedon, floutage, COUNT(idobs) AS nb, idobser FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN referentiel.observateur USING(idobser)
						WHERE idm = :idm
						GROUP BY typedon, floutage, idobser");
	$req->bindValue(':idm', $idm);
	$req->execute();
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;		
}
function organisme()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("SELECT idorg, organisme
                                        FROM referentiel.organisme
                                        left join referentiel.observateur_organisme using (idorg)
                                        left join referentiel.observateur ON observateur.idm = observateur_organisme.idobser
                                        where observateur_organisme.idobser = :idmembre
                                        ORDER BY organisme ");
    $req->bindValue(':idmembre', $_SESSION['idmembre']);
    $req->execute();
    $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultats;
}
function rechercheobservateurid($idm)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idobser FROM referentiel.observateur WHERE idm = :idm ");
	$req->bindValue(':idm', $idm);
	$req->execute();
	$idobser = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $idobser;		
}
function liste_espece($idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("WITH sel AS (SELECT COUNT(idobs) AS nbt, cdref FROM obs.obs
							INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
							WHERE rang = 'ES' OR rang = 'SSES'
							GROUP BY cdref
						)
						SELECT sel.nbt, COUNT(DISTINCT idobs) AS nb, nom, nomvern, sel.cdref, ir, observa, to_char(MAX(date1), 'DD/MM/YYYY') AS max FROM sel
						INNER JOIN obs.obs ON obs.cdref = sel.cdref
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref
						LEFT JOIN obs.plusobser USING(idfiche)
						WHERE fiche.idobser = :idobser OR plusobser.idobser = :idobser
						GROUP BY sel.cdref, nom, nomvern, nbt, ir, observa
						ORDER BY observa, nom ") or die(print_r($bdd->errorInfo()));	
	$req->bindValue(':idobser', $idobser);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}