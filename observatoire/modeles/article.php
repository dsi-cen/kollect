<?php
function article($id)
{
	$bdd = PDO2::getInstance();		
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT typear, titre, soustitre, article FROM site.article WHERE idarticle = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->execute();
	$article = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $article;		
}
function animateur($nomvar)
{
	$bdd = PDO2::getInstance();		
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, prenom FROM site.membre WHERE gestionobs ILIKE :rech ORDER BY nom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':rech', '%'.$nomvar.'%');
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function validateurnom($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, prenom FROM site.validateur
						INNER JOIN site.membre USING(idmembre)
						WHERE discipline ILIKE :observa
						ORDER BY nom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':observa', '%'.$nomvar.'%');
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}