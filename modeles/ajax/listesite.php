<?php
include '../../global/configbase.php';
include '../../lib/pdo2.php';
if(isset($_GET['term'])) 
{
	$term = $_GET['term'];
	function liste($term)
	{
		$resultat= array();
		$bdd = PDO2::getInstance();
		$bdd->query("SET NAMES 'UTF8'");
		$req = $bdd->prepare("SELECT site, commune, idsite FROM obs.site 
							INNER JOIN referentiel.commune ON commune.codecom = site.codecom 
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