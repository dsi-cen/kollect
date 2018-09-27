<?php
function changement()
{
	$bdd = PDO2::getInstance();		
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT valfinal, observatoire, histo.rang AS ancrang, liste.rang, histo.nom AS ancnom, histo.nomvern AS ancvern, liste.nom, liste.nomvern FROM taxref.histo
						INNER JOIN referentiel.liste ON liste.cdnom = histo.valfinal
						ORDER BY histo.nom");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;		
}