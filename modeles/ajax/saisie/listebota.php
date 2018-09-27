<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
if(isset($_GET['term'])) 
{
	$term = $_GET['term'];
	$nomvar = $_GET['sel'];
	
	$json = file_get_contents('../../../json/'.$nomvar.'.json');
	$rjson = json_decode($json, true);
	$tablebota = ($rjson['saisie']['listebota'] == 'aucune') ? 'listebota' : $rjson['saisie']['listebota'];
			
	function liste($term,$tablebota)
	{
		$resultat= array();
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		if($tablebota == 'listebota')
		{
			$req = $bdd->prepare("SELECT nom, cdnom, nomvern FROM referentiel.listebota
								WHERE (nom ILIKE :recherche OR nomvern ILIKE :recherche) ORDER BY nom LIMIT 15") or die(print_r($bdd->errorInfo()));
		}
		else
		{
			$req = $bdd->prepare("SELECT nom, cdnom, nomvern FROM referentiel.liste 
								WHERE (nom ILIKE :recherche OR nomvern ILIKE :recherche) AND observatoire = :bota ORDER BY nom LIMIT 15") or die(print_r($bdd->errorInfo()));
			$req->bindValue(':bota', $tablebota);
		}		
		$req->bindValue(':recherche', '%'.$term.'%');
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;
	}		
	$resultat = liste($term,$tablebota);
		
	echo json_encode($resultat); 
}	
?>