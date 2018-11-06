<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function listesite($idcoord)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT commune, codecom, site, idsite, idcoord, coordonnee.x, coordonnee.y, coordonnee.lat, coordonnee.lng, coordonnee.altitude, codel93, utm, utm1 FROM obs.site
						INNER JOIN obs.coordonnee USING(idcoord)
						INNER JOIN referentiel.commune USING(codecom)
						WHERE idcoord = :idcoord ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idcoord', $idcoord);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
if(isset($_POST['idcoord']))
{	
	$idcoord = $_POST['idcoord'];
	$liste = listesite($idcoord);
	echo json_encode($liste);
}	
?>