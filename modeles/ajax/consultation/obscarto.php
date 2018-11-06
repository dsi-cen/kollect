<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function proche($lat,$lng,$dist)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT site, idsite, coordonnee.idcoord, lat, lng FROM obs.coordonnee
						INNER JOIN obs.site USING(idcoord)
						WHERE (6366*acos(cos(radians(:lat))*cos(radians(lat))*cos(radians(lng)-radians(:lng))+sin(radians(:lat))*sin(radians(lat)))) < :dist ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':lat', $lat);
	$req->bindValue(':lng', $lng);
	$req->bindValue(':dist', $dist);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function procheobserva($lat,$lng,$dist,$sel)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT site, site.idsite, coordonnee.idcoord, lat, lng FROM obs.coordonnee
						INNER JOIN obs.site USING(idcoord)
						INNER JOIN obs.fiche USING(idcoord)
						INNER JOIN obs.obs USING(idfiche)
						WHERE (6366*acos(cos(radians(:lat))*cos(radians(lat))*cos(radians(lng)-radians(:lng))+sin(radians(:lat))*sin(radians(lat)))) < :dist AND observa = :sel ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':lat', $lat);
	$req->bindValue(':lng', $lng);
	$req->bindValue(':dist', $dist);
	$req->bindValue(':sel', $sel);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function listeobserva($lat,$lng,$dist,$sel)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT nom FROM obs.coordonnee
						INNER JOIN obs.fiche USING(idcoord)
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE (6366*acos(cos(radians(:lat))*cos(radians(lat))*cos(radians(lng)-radians(:lng))+sin(radians(:lat))*sin(radians(lat)))) < :dist AND observa = :sel ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':lat', $lat);
	$req->bindValue(':lng', $lng);
	$req->bindValue(':dist', $dist);
	$req->bindValue(':sel', $sel);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function liste($lat,$lng,$dist)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT nom, observa FROM obs.coordonnee
						INNER JOIN obs.fiche USING(idcoord)
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE (6366*acos(cos(radians(:lat))*cos(radians(lat))*cos(radians(lng)-radians(:lng))+sin(radians(:lat))*sin(radians(lat)))) < :dist ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':lat', $lat);
	$req->bindValue(':lng', $lng);
	$req->bindValue(':dist', $dist);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
if(isset($_POST['dist']) && isset($_POST['sel']))
{
	$sel = $_POST['sel'];
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	$dist = $_POST['dist'];
	
	$proche = ($sel == 'aucun') ? proche($lat,$lng,$dist) : procheobserva($lat,$lng,$dist,$sel);
	if(count($proche) > 0 )
	{
		$listetaxon = ($sel == 'aucun') ? liste($lat,$lng,$dist) : listeobserva($lat,$lng,$dist,$sel);
		$nbtaxon = count($listetaxon);
		
		foreach($proche as $n)
		{
			$tab = array('site'=>$n['site'], 'geojson_point' => null);
			$tab['geojson_point'] = array('coordinates' => array(floatval($n['lng']), floatval($n['lat'])), 'type' => 'Point');
			$resultats[] = $tab;
		}
		
		$retour['statut'] = 'Oui';
		$retour['point'] = $resultats;
		$retour['nb'] = $nbtaxon;
	}
	else
	{
		$retour['statut'] = 'Non';
	}
	echo json_encode($retour);
}	