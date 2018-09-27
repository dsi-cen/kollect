<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function cherchesite($idsite)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT lat, lng, geo FROM obs.coordonnee 
						LEFT JOIN obs.coordgeo ON coordgeo.idcoord = coordonnee.idcoord
						INNER JOIN obs.site ON site.idcoord = coordonnee.idcoord
						WHERE idsite  = :idsite ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idsite', $idsite);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['idsite']))
{	
	$idsite = $_POST['idsite'];
		
	$liste = cherchesite($idsite);
	
	$retour['site'] = $liste;
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';	
}
echo json_encode($retour);	
?>