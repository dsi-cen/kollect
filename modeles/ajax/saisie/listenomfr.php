<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
if(isset($_GET['term'])) 
{
	$term = $_GET['term'];
	if (isset($_GET['sel']))
	{
		$sel = $_GET['sel'];
	}	
	
	/*function liste($term,$sel)
	{
		$resultat= array();
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT nom, liste.cdnom, cdref, auteur, nomvern, stade, bino, photo, son, loupe FROM $sel.liste 
							LEFT JOIN vali.critere ON critere.cdnom = liste.cdref
							WHERE nomvern ILIKE :recherche AND locale = 'oui' AND cdref = liste.cdnom ORDER BY nomvern LIMIT 15") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':recherche', '%'.$term.'%');
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;
	}*/		
	function liste($term,$sel)
	{
		$resultat= array();
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT l.nom, l.cdnom, cdref, l.auteur, l.nomvern, stade, bino, photo, son, loupe, vali FROM $sel.liste AS l
							LEFT JOIN vali.critere ON critere.cdnom = l.cdref
							LEFT JOIN referentiel.liste ON liste.cdnom = l.cdref
							WHERE l.nomvern ILIKE :recherche AND locale = 'oui' AND cdref = l.cdnom ORDER BY nomvern LIMIT 15") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':recherche', '%'.$term.'%');
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;
	}
	
	if(isset($sel))
	{
		$resultat = liste($term,$sel);
	}
	else
	{
		$resultat = array(array('nom'=>'Choisir un observatoire','cdnom'=>'0','auteur'=>''));
	}
	
	echo json_encode($resultat); 
}	
?>