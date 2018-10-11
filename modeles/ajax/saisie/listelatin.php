<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
if(isset($_GET['term'])) 
{
	$term = $_GET['term'];
	$sel = $_GET['sel'];

	/*function liste($term,$sel)
	{
		$resultat= array();
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT nom, liste.cdnom, cdref, auteur, nomvern, stade, bino, photo, son, loupe FROM $sel.liste
							LEFT JOIN vali.critere ON critere.cdnom = liste.cdref
							WHERE nom ILIKE :recherche AND locale = 'oui' ORDER BY nom LIMIT 15") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':recherche', '%'.$term.'%');
		$req->execute();
		$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		return $resultat;
	}*/	
	function liste($term,$sel)
	{
		$resultat= array();
		// RLE Recherche avec deux termes
		$combinaison = array();
		$combinaison = explode(" ", $term);
		$combinaison = array_values(array_filter($combinaison, 'strlen'));
		if (count($combinaison) == 1){
			$bdd = PDO2::getInstance();
			$bdd->query('SET NAMES "utf8"');
			$req = $bdd->prepare("SELECT l.nom, l.cdnom, cdref, l.auteur,  l.nomvern, '{'|| l.rang || '}' as rang, stade, bino, photo, son, loupe, vali FROM $sel.liste AS l
								LEFT JOIN vali.critere ON critere.cdnom = l.cdref
								LEFT JOIN referentiel.liste ON liste.cdnom = l.cdref
								WHERE l.nom ILIKE :recherche AND l.nom ILIKE :recherche2 AND locale = 'oui' ORDER BY nom LIMIT 15");
			$req->bindValue(':recherche', '%'.$combinaison[0].'%');
			$req->bindValue(':recherche2', '%'.$combinaison[1].'%');
			$req->execute();
			$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
			$req->closeCursor();
			return $resultat;
		}
		else {
			$bdd = PDO2::getInstance();
			$bdd->query('SET NAMES "utf8"');
			$req = $bdd->prepare("SELECT l.nom, l.cdnom, cdref, l.auteur,  l.nomvern, '{'|| l.rang || '}' as rang, stade, bino, photo, son, loupe, vali FROM $sel.liste AS l
								LEFT JOIN vali.critere ON critere.cdnom = l.cdref
								LEFT JOIN referentiel.liste ON liste.cdnom = l.cdref
								WHERE trim(both substring(l.nom FROM 1 for CASE WHEN position( ' ' in l.nom ) > 0 THEN position( ' ' in l.nom ) ELSE length(l.nom) END)) ILIKE :recherche 
								AND trim(both substring(l.nom FROM CASE WHEN position( ' ' in l.nom ) > 0 THEN position( ' ' in l.nom ) ELSE 0 END for CASE WHEN position( ' ' in l.nom ) > 0 THEN length(l.nom) ELSE 0 END)) ILIKE :recherche2 AND locale = 'oui' ORDER BY l.nom LIMIT 30");
			$req->bindValue(':recherche', $combinaison[0].'%');
			$req->bindValue(':recherche2', $combinaison[1].'%');
			$req->execute();
			$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
			$req->closeCursor();
			return $resultat;
		}
	}
	$resultat = liste($term,$sel);
		
	echo json_encode($resultat); 
}	
?>
