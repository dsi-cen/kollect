<?php
function connexionor($idm,$droits,$p)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT obser, latin, floutage, nom, prenom, couche, typedon, org FROM site.membre
						LEFT JOIN site.prefmembre USING(idmembre)
						WHERE idmembre = :idm AND droits = :droits AND prenom = :p ");
	$req->bindValue(':idm', $idm);
	$req->bindValue(':droits', $droits);
	$req->bindValue(':p', $p);
	$req->execute();
	$connexion = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $connexion;
}
function notif($idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(idnotif) AS nb FROM site.notif WHERE idm = :idm ");
	$req->bindValue(':idm', $idm);
	$req->execute();
	$nbnotif = $req->fetchColumn();
	$req->closeCursor();
	return $nbnotif;
}
function mod_membrec($date,$idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE site.membre SET derniereconnection = :date WHERE idmembre = :idm ");
	$req->bindValue(':date', $date);
	$req->bindValue(':idm', $idm);
	$req->execute();
	$req->closeCursor();
}
function listecomsocial()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT prenom, nom, commentaire, to_char(datecom, 'DD/MM/YYYY') AS datefr, idobs FROM site.comobs
						INNER JOIN referentiel.observateur USING(idm)
						ORDER BY datecom DESC
						LIMIT 10 ");
	$listecomsocial = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $listecomsocial;
}
function listecomsocialobserva($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT prenom, nom, commentaire, to_char(datecom, 'DD/MM/YYYY') AS datefr, idobs FROM site.comobs
						INNER JOIN obs.obs USING(idobs)
						INNER JOIN referentiel.observateur USING(idm)
						WHERE observa = :nomvar
						ORDER BY datecom DESC
						LIMIT 10 ");
	$req->bindValue(':nomvar', $nomvar);
	$req->execute();
	$listecomsocial = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $listecomsocial;
}
function chercheip($ip)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM site.utilisateur WHERE ip = :ip ");
	$req->bindValue(':ip', $ip);
	$req->execute();
	$nbip = $req->fetchColumn();
	$req->closeCursor();
	return $nbip;
}
function inserip($ip,$idm,$temp,$agent,$referer,$uri)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO site.utilisateur (ip, timestamp, idm, agent, referer, uri) VALUES(:ip, :time, :idm, :agent, :referer, :uri) ");
	$req->execute(array('ip' => $ip, 'time' => $temp, 'idm' => $idm, 'agent' => $agent, 'referer' => $referer, 'uri' => $uri));
	$req->closeCursor();
}
function modip($ip,$idm,$temp,$agent,$referer,$uri)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE site.utilisateur SET timestamp = :time, idm = :idm, agent = :agent, referer = :referer, uri = :uri WHERE ip = :ip ");
	$req->execute(array('ip' => $ip, 'time' => $temp, 'idm' => $idm, 'agent' => $agent, 'referer' => $referer, 'uri' => $uri));
	$req->closeCursor();
}
function deleteip($cinqmin)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM site.utilisateur WHERE timestamp < :cinq ");
	$req->bindValue(':cinq', $cinqmin);
	$req->execute();
	$req->closeCursor();
}