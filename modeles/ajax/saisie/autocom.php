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
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT commune, x, y, lng, lat, codecom, iddep FROM referentiel.commune 
							WHERE commune ILIKE :recherche
							ORDER BY commune
							LIMIT 15 ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':recherche', ''.$term.'%');
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;
	}
	function liste1($term)
	{
		$resultat= array();
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT commune, x, y, commune.lng, commune.lat, departement, codecom, departement.iddep FROM referentiel.commune
							INNER JOIN referentiel.departement ON departement.iddep = commune.iddep
							WHERE commune ILIKE :recherche
							ORDER BY commune
							LIMIT 15 ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':recherche', ''.$term.'%');
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;
	}
	if ($_GET['dep'] == 'non')
	{
		$resultat = liste($term);
	}
	else
	{
		$resultat = liste1($term);
	}
	
	echo json_encode($resultat); 
}	
?>