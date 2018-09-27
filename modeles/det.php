<?php
function info($id)
{
	$bdd = PDO2::getInstance();		
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idm, to_char(datephoto, 'DD/MM/YYYY') AS dateph, to_char(datesaisie, 'DD/MM/YYYY') AS dates, nomphoto, nomini, rq, commune, prenom, nom, idobs, typef, observa FROM site.photodet
						INNER JOIN referentiel.commune USING(codecom)
						INNER JOIN site.membre ON membre.idmembre = photodet.idm
						WHERE idpdet = :id ");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;		
}
function cherche_det($id)
{
	$bdd = PDO2::getInstance();		
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT liste.nom, nomvern, to_char(datedet, 'DD/MM/YYYY') AS datefr, membre.nom AS nobser, prenom, rang, ndet FROM site.determination
						LEFT JOIN referentiel.liste USING(cdnom)
						INNER JOIN site.membre ON membre.idmembre = determination.idm
						WHERE idpdet = :id");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;		
}
function supnotif($id,$idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM site.notif WHERE idtype = :idpdet AND idm = :idm ");
	$req->bindValue(':idpdet', $id);
	$req->bindValue(':idm', $idm);
	$req->execute();
	$req->closeCursor();
}
function recherche_com($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idm, commentaire, prenom, nom, to_char(datecom, 'DD/MM/YYYY - HH24:MI') AS datefr FROM site.comdet 
						INNER JOIN site.membre ON membre.idmembre = comdet.idm
						WHERE idpdet = :id 
						ORDER BY datecom ");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function nbdet()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT COUNT(observa) AS nbo, observa FROM site.photodet
						GROUP BY observa ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}