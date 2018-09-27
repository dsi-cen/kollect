<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function listesite($codecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT site, idsite, site.idcoord, x, y, lat, lng, altitude, codel93, utm, utm1, geo FROM obs.site
						INNER JOIN obs.coordonnee USING(idcoord)
						LEFT JOIN obs.coordgeo ON coordgeo.idcoord = coordonnee.idcoord
						WHERE codecom = :codecom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':codecom', $codecom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
if(isset($_POST['codecom']))
{	
	$codecom = $_POST['codecom'];
	$listesite = listesite($codecom);
	echo json_encode($listesite);
}	
?>