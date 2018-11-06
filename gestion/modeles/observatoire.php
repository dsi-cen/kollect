<?php
function gestion($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT gestionobs FROM site.membre WHERE idmembre = :id ");
	$req->bindValue(':id', $id);
	$req->execute();
	$gestionobs = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $gestionobs;
}