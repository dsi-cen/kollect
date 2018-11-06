<?php
include '../global/configbase.php';
include '../lib/pdo2.php';
if(isset($_GET['term'])) 
{
	$term = $_GET['term'];
	function liste($term)
	{
		$resultat= array();
		$bdd = PDO2::getInstanceinstall();
		$bdd->query("SET NAMES 'UTF8'");
		$req = $bdd->prepare("SELECT DISTINCT pnr, id FROM install.pnr 
							WHERE pnr ILIKE :recherche
							ORDER BY pnr") or die(print_r($bdd->errorInfo()));
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