<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function taxon($cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT nom, nomvern, sensible FROM referentiel.liste
						LEFT JOIN referentiel.sensible ON sensible.cdnom = liste.cdnom
						WHERE liste.cdnom = :cdnom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}	
function recherchepoint($cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT lat, lng FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.coordonnee USING(idcoord)
						WHERE cdref = :cdnom AND localisation = 1 AND floutage = 0 ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}	

if(isset($_POST['cdnom']))
{
	$cdnom = htmlspecialchars($_POST['cdnom']);
	
	$taxon = taxon($cdnom);
	if($taxon['sensible'] == '')
	{
		$liste = recherchepoint($cdnom);
		foreach($liste as $n)
		{
			$tab = array('nom'=>$taxon['nom'], 'geojson_point' => null);
			$tab['geojson_point'] = array('coordinates' => array(floatval($n['lng']), floatval($n['lat'])), 'type' => 'Point');
			$resultats[] = $tab;
		}
		if(count($resultats) > 0)
		{
			$retour['point'] = $resultats;
		}		
	}
	else
	{
		$retour['sensible'] = 'oui';
	}
	$retour['nom'] = $taxon['nom'];
	$retour['statut'] = 'Oui';
	
	echo json_encode($retour, JSON_NUMERIC_CHECK);
}