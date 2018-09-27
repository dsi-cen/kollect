<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

if(isset($_GET['term'])) 
{
	$term = $_GET['term'];
			
	function liste($term)
	{
		$resultat= array();
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT codecom, commune FROM referentiel.commune WHERE commune ILIKE :recherche ORDER BY commune LIMIT 10");
		$req->bindValue(':recherche', ''.$term.'%');
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;
	}		
	$resultat = liste($term);
		
	echo json_encode($resultat); 
}	
?>