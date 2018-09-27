<?php
function validateur($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT discipline FROM site.validateur WHERE idmembre = :id ");
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
/*function liste_idobs_com()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("WITH sel AS (
							SELECT idobs FROM vali.comvali
							ORDER BY idcom LIMIT 100
						)
						SELECT DISTINCT ON (idobs) idobs FROM sel ORDER BY idobs DESC");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}*/
function liste_idobs_com()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("WITH sel AS (
							SELECT idobs FROM vali.comvali
							ORDER BY idcom
						)
						SELECT DISTINCT ON (idobs) idobs FROM sel ORDER BY idobs DESC LIMIT 100");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function liste_com()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("WITH sel AS (
							SELECT idobs FROM vali.comvali
							ORDER BY idcom desc LIMIT 100
						), sel1 AS (
							SELECT DISTINCT ON (idobs) idobs FROM sel
						)
						SELECT sel1.idobs, CONCAT(prenom, ' ', nom) AS nom, to_char(datecom, 'DD/MM/YYYY à HH24:MI') AS datefr, commentaire FROM sel1
						INNER JOIN vali.comvali USING(idobs)
						INNER JOIN site.membre ON membre.idmembre = comvali.idm
						ORDER BY idobs DESC, idcom ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}