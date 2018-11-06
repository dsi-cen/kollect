<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
if(isset($_GET['term'])) 
{
	$term = $_GET['term'];
	function liste($term)
	{
		$resultat= array();
		$bdd = PDO2::getInstance();
		$bdd->query("SET NAMES 'UTF8'");
		$req = $bdd->prepare("SELECT site, commune, iddep, coordonnee.x, coordonnee.y, coordonnee.altitude, utm, utm1, site.codecom, site.idcoord, idsite, coordonnee.lat, coordonnee.lng, codel93, codel935, geo FROM obs.site 
							INNER JOIN referentiel.commune ON commune.codecom = site.codecom 
							INNER JOIN obs.coordonnee ON coordonnee.idcoord = site.idcoord
							LEFT JOIN obs.coordgeo ON coordgeo.idcoord = coordonnee.idcoord
							WHERE site ILIKE :recherche
							ORDER BY site 
							LIMIT 20 ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':recherche', ''.$term.'%');
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;
	}
	$resultat = liste($term);
	
	echo json_encode($resultat, JSON_NUMERIC_CHECK);
}	
?>