<?php
function article($id)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT titre, soustitre, article FROM site.article WHERE idarticle = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->execute();
	$article = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $article;		
}
function rarticle($type)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT titre, soustitre, article FROM site.article WHERE typear = :type ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':type', $type);
	$req->execute();
	$article = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $article;		
}
function animateur($nomvar)
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT mail FROM site.membre WHERE gestionobs ILIKE :rech ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':rech', '%'.$nomvar.'%');
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}