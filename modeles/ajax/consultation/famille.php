<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

if(isset($_GET['term'])) 
{
	$observa = $_GET['observa'];
	$term = $_GET['term'];
			
	function liste($term,$observa)
	{
		$resultat= array();
		$bdd = PDO2::getInstance();
		$bdd->query("SET NAMES 'UTF8'");
		$req = $bdd->prepare("SELECT DISTINCT famille.famille, famille.cdnom FROM $observa.famille
							INNER JOIN $observa.liste ON liste.famille = famille.cdnom
							INNER JOIN obs.obs ON obs.cdref = liste.cdnom
							WHERE famille.famille ILIKE :recherche 
							ORDER BY famille LIMIT 15");
		$req->bindValue(':recherche', $term.'%');
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;
	}		
	$resultat = liste($term,$observa);
		
	echo json_encode($resultat); 
}	
?>