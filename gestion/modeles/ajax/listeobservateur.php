<?php 
if(isset($_GET['term'])) 
{
	$term = $_GET['term'];
	include '../../../global/configbase.php';
	include '../../../lib/pdo2.php';
	//M
	function liste_observateur($term)
	{
		$resultat= array();
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT observateur, idobser FROM referentiel.observateur WHERE observateur ILIKE :recherche");
		$req->bindValue(':recherche', ''.$term.'%');
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;
	}
	//C
	$resultat = liste_observateur($term);
	
	//V
	echo json_encode($resultat); 
}	
?>