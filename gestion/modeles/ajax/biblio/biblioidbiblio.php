<?php
if(isset($_GET['term'])) 
{
	$term = $_GET['term'];
	include '../../../../global/configbase.php';
	include '../../../../lib/pdo2.php';
	
	function liste($term)
	{
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT titre, idbiblio FROM biblio.biblio WHERE idbiblio = :id ");
		$req->bindValue(':id', $term);
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;
	}
	
	$resultat = liste($term);
		
	foreach($resultat as $n)
	{
		$titre = strip_tags($n['titre']);
		$data[] = ['titre'=>$titre,'id'=>$n['idbiblio']];		
	}
	echo json_encode($data);  
}
?>