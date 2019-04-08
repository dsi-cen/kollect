<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function listesite($codecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
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

function listesitedep($dep)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("SELECT iddep FROM referentiel.departement WHERE departement LIKE :dep ") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':dep', $dep);
    $req->execute();
    $codedep = $req->fetchAll(PDO::FETCH_ASSOC);
    $req = $bdd->prepare("SELECT site, idsite, site.idcoord, x, y, lat, lng, altitude, codel93, utm, utm1, geo FROM obs.site
						INNER JOIN obs.coordonnee USING(idcoord)
						LEFT JOIN obs.coordgeo ON coordgeo.idcoord = coordonnee.idcoord
						WHERE codecom LIKE :codecom ") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':codecom', $codedep[0]['iddep'] . "%");
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
} else if (isset($_POST['dep'])) {
    $dep = $_POST['dep'];
    $listesite = listesitedep($dep);
    echo json_encode($listesite);
}

?>