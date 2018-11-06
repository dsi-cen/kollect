<?php
function listeactu()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT idactu, to_char(datecreation, 'DD/MM/YYYY') AS datefr, theme, titre, compte, photoactu.nom AS nomphoto, iddoc, visible, prenom, membre.nom FROM actu.actu
						LEFT JOIN actu.photoactu USING(idactu)
						LEFT JOIN actu.docactu USING(idactu)
						LEFT JOIN site.membre ON membre.idmembre = actu.idauteur
						ORDER BY datecreation DESC ");
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);
}
function chercheactu($idactu)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idactu, titre, soustitre, actu, tag, theme, url, visible, idauteur, nomdoc, nom, auteur, info FROM actu.actu
						LEFT JOIN actu.photoactu USING(idactu)
						LEFT JOIN actu.docactu USING(idactu)
						WHERE idactu = :id ");
	$req->bindValue(':id', $idactu);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function chercheauteuractu($idauteur)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT nom, prenom FROM site.membre WHERE idmembre = :id ");
	$req->bindValue(':id', $idauteur);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}