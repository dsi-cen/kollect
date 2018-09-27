<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function proche($lat,$lng,$dist)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT site, idsite, codecom, coordonnee.idcoord, lat, lng, geo FROM obs.coordonnee
						INNER JOIN obs.site USING(idcoord)
						LEFT JOIN obs.coordgeo ON coordgeo.idcoord = coordonnee.idcoord
						WHERE (6366*acos(cos(radians(:lat))*cos(radians(lat))*cos(radians(lng)-radians(:lng))+sin(radians(:lat))*sin(radians(lat)))) < :dist ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':lat', $lat);
	$req->bindValue(':lng', $lng);
	$req->bindValue(':dist', $dist);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
if(isset($_POST['lat']) && isset($_POST['lng']))
{
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	$dist = $_POST['dist'];
	
	$proche = proche($lat,$lng,$dist);
	if(count($proche) > 0 )
	{
		$retour['statut'] = 'Oui';
		$retour['liste'] = $proche;
	}
	else
	{
		$retour['statut'] = 'Non';
	}
	
	echo json_encode($retour);
}	
?>