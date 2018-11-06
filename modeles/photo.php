<?php
function quarante()
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT nomphoto, prenom, observateur.nom, idobs, to_char(datephoto, 'DD/MM/YYYY') AS datefr, liste.nom AS sp, nomvern, photo.observatoire FROM site.photo
						INNER JOIN referentiel.observateur USING(idobser)
						INNER JOIN referentiel.liste ON liste.cdnom = photo.cdnom
						ORDER BY datesaisie DESC
						LIMIT 40 ") or die(print_r($bdd->errorInfo()));
	$resultats = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultats;
}