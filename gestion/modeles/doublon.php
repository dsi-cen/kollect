<?php
function doublon_fiche()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT DISTINCT idfiche, t1.iddep, commune, site, date1, t1.idcoord, observateur FROM obs.fiche AS t1
						LEFT JOIN referentiel.commune USING(codecom)
						LEFT JOIN obs.site USING(idsite)
						INNER JOIN referentiel.observateur USING(idobser)
						WHERE EXISTS (
							SELECT * FROM obs.fiche as t2
							WHERE t1.idfiche <> t2.idfiche
							AND t1.idsite = t2.idsite
							AND t1.idcoord = t2.idcoord
							AND t1.date1 = t2.date1 
							AND t1.date2 = t2.date2 )
						ORDER BY date1 ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}