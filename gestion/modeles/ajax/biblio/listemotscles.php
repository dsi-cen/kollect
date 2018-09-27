<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

if(isset($_GET['term'])) 
{
	$term = $_GET['term'];
			
	function liste($term)
	{
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT idmc, mot FROM biblio.motcle WHERE mot ILIKE :recherche ORDER BY mot LIMIT 15");
		$req->bindValue(':recherche', '%'.$term.'%');
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;
	}		
	$resultat = liste($term);
		
	echo json_encode($resultat); 
}	
?>