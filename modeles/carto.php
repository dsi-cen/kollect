<?php
function recherche_taxon()
{
	$bdd = PDO2::getInstance();
	$req = $bdd->query("WITH sel AS (
							SELECT DISTINCT cdref FROM obs.obs
					)
					SELECT liste.cdnom, nom, nomvern, observatoire FROM referentiel.liste
					INNER JOIN sel ON sel.cdref = liste.cdnom
					WHERE rang != 'GN'
					ORDER BY nom ");
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $liste;	
}