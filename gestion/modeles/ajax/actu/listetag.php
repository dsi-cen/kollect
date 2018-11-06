<?php 
if(isset($_GET['term'])) 
{
	$term = $_GET['term'];
	include '../../../../global/configbase.php';
	include '../../../../lib/pdo2.php';
	
	function liste_mots($term)
	{
		$resultat= array();
		$bdd = PDO2::getInstance();
		$bdd->query("SET NAMES 'UTF8'");
		$req = $bdd->prepare("SELECT tag FROM actu.tag WHERE tag ILIKE :recherche LIMIT 10");
		$req->bindValue(':recherche', ''.$term.'%');
		$req->execute();
		while($donnees = $req->fetch())
		{
			array_push($resultat, $donnees['tag']);
		}
		$req->closeCursor();
		return $resultat;
	}
	
	$resultat = liste_mots($term);
	echo json_encode($resultat); 
}	
?>