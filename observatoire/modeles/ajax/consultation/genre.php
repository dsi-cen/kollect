<?php 
include '../../../../global/configbase.php';
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
		$req = $bdd->prepare("SELECT DISTINCT genre.genre, genre.cdnom FROM $observa.genre
							INNER JOIN $observa.liste ON liste.cdtaxsup = genre.cdnom
							INNER JOIN obs.obs ON obs.cdref = liste.cdnom
							WHERE genre.genre ILIKE :recherche 
							ORDER BY genre LIMIT 15");
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