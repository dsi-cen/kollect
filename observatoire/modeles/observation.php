<?php
function recherche_nom($id,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, nomvern, auteur, sensible, rang FROM $nomvar.liste 
						LEFT JOIN referentiel.sensible ON sensible.cdnom = liste.cdref
						WHERE liste.cdnom = :cdnom ");
	$req->bindValue(':cdnom', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}