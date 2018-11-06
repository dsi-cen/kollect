<?php
function cherche_vali($idmembre)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT discipline FROM site.validateur WHERE idmembre = :id ");
	$req->bindValue(':id', $idmembre);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function nbvali($n)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(idobs) AS nb FROM obs.obs WHERE observa = :obser AND validation = 6 ");
	$req->bindValue(':obser', $n);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function nbvali7($n)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(idobs) AS nb FROM obs.obs WHERE observa = :obser AND validation = 7 ");
	$req->bindValue(':obser', $n);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function utilisateur()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT ip, agent, referer, uri, prenom, nom FROM site.utilisateur
						LEFT JOIN site.membre ON membre.idmembre = utilisateur.idm ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function nbdet($n)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM site.photodet WHERE observa = :obser AND vali != 'nde' ");
	$req->bindValue(':obser', $n);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function nbdetvali($n)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM site.photodet WHERE observa = :obser AND vali = 'oui' ");
	$req->bindValue(':obser', $n);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}