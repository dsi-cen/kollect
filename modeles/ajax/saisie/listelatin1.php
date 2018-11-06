<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
if(isset($_GET['term'])) 
{
	$term = $_GET['term'];
	$sel = $_GET['sel'];
		
	function liste($term,$sel)
	{
		$resultat= array();
		$bdd = PDO2::getInstance();
		$bdd->query("SET NAMES 'UTF8'");
		$req = $bdd->prepare("SELECT nom, liste.cdnom, cdref, auteur, nomvern FROM $sel.liste 
							WHERE nom ILIKE :recherche AND locale = 'non' ORDER BY nom LIMIT 15") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':recherche', ''.$term.'%');
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;
	}		
	$resultat = liste($term,$sel);
		
	echo json_encode($resultat); 
}	
?>