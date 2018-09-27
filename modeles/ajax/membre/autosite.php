<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
if(isset($_GET['term'])) 
{
	$term = $_GET['term'];
	$idobser = $_GET['idobser'];
	
	function liste($term,$idobser)
	{
		$resultat= array();
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT DISTINCT site, commune, site.codecom, site.idsite FROM obs.site 
							INNER JOIN referentiel.commune ON commune.codecom = site.codecom 
							INNER JOIN obs.fiche ON fiche.idsite = site.idsite
							WHERE site ILIKE :recherche AND idobser = :idobser
							ORDER BY site 
							LIMIT 20 ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':recherche', ''.$term.'%');
		$req->bindValue(':idobser', $idobser);
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;
	}
	$resultat = liste($term,$idobser);
	
	echo json_encode($resultat, JSON_NUMERIC_CHECK);
}	
?>