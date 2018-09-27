<?php
function modif()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT idmodif, typeid, numid, typemodif, modif, datemodif, nom, prenom FROM site.modif 
						INNER JOIN site.membre ON membre.idmembre = modif.idmembre
						ORDER BY datemodif DESC LIMIT 100 ");
	$liste = $req->fetchAll();
	$req->closeCursor();
	return $liste;
}
function virtuel()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT typeid, idsession, nomvirtuel, datevirt, nom, prenom FROM site.virtuel
						INNER JOIN site.membre USING(idmembre)
						ORDER BY datevirt DESC LIMIT 100 ");
	$liste = $req->fetchAll();
	$req->closeCursor();
	return $liste;
}
