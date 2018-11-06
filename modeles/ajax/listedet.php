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
		$req = $bdd->prepare("SELECT DISTINCT(nom), liste.cdnom, auteur, nomvern, observatoire FROM referentiel.liste 
							WHERE nom ILIKE :recherche OR nomvern ILIKE :recherche 
							ORDER BY nom LIMIT 15") or die(print_r($bdd->errorInfo()));
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