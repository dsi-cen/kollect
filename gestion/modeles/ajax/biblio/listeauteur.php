<?php 
if(isset($_GET['term'])) 
{
	$term = $_GET['term'];
	include '../../../../global/configbase.php';
	include '../../../../lib/pdo2.php';
	
	function liste_auteur($term)
	{
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT idauteur, nom, prenom FROM biblio.auteurs WHERE nom ILIKE :recherche");
		$req->bindValue(':recherche', ''.$term.'%');
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;
	}
	
	$resultat = liste_auteur($term);
		
	echo json_encode($resultat); 
}	
?>