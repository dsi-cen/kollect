<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
/*
SELECT idcoord, site, geo FROM obs.coordgeo
INNER JOIN obs.site USING(idcoord)
						WHERE poly @> ('1.2298,46.6768')
						*/
function recup($x,$y)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT codecom, commune, iddep FROM referentiel.comsaisie 
						INNER JOIN referentiel.commune USING(codecom)
						WHERE comsaisie.poly @> :recherche ");
	$req->bindValue(':recherche', '('.$x.','.$y.')');
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function cherchepoly($lat,$lng)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idcoord, geo, site FROM obs.coordgeo
						INNER JOIN obs.site USING(idcoord)
						WHERE poly @> :recherche ");
	$req->bindValue(':recherche', '('.$lng.','.$lat.')');
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['x']))
{	
	$x = $_POST['x'];
	$y = $_POST['y'];
	$recup = recup($x,$y);
	if($recup['codecom'] != '')
	{
		$retour['com'] = $recup;
		$retour['emp'] = 'Oui';
		
		$lat = $_POST['lat'];
		$lng = $_POST['lng'];
		$poly = cherchepoly($lat,$lng);
		if($poly != false)
		{
			$retour['poly'] = 'oui';
			$retour['pol'] = $poly;
		}		
	}
	else
	{
		$retour['emp'] = 'Non';
	}
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';	
}
echo json_encode($retour);	
?>