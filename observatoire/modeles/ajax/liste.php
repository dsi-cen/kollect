<?php 
include '../../../global/configbase.php';
include '../../lib/pdo2.php';
if(isset($_GET['term'])) 
{
	$sel = $_GET['sel'];
	$term = $_GET['term'];
			
	function liste($term,$sel)
	{
		$resultat= array();
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT DISTINCT(nom), liste.cdnom, auteur, nomvern, observatoire FROM referentiel.liste 
							INNER JOIN obs.obs ON obs.cdref = liste.cdnom
							WHERE (nom ILIKE :recherche OR nomvern ILIKE :recherche) AND observatoire = :sel AND (rang = 'ES' OR rang = 'SSES')
							ORDER BY nom LIMIT 15");
		$req->bindValue(':recherche', '%'.$term.'%');
		$req->bindValue(':sel', $sel);
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;
	}		
	$resultat = liste($term,$sel);
		
	echo json_encode($resultat); 
}	
?>