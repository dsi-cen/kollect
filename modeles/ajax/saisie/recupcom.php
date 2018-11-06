<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function recup($x,$y)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT codecom, commune, iddep FROM referentiel.comsaisie 
						INNER JOIN referentiel.commune USING(codecom)
						WHERE comsaisie.poly @> :recherche ");
	$req->bindValue(':recherche', '('.$x.','.$y.')');
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recupdep($x,$y)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT codecom, commune, departement, iddep FROM referentiel.comsaisie 
						INNER JOIN referentiel.commune USING(codecom)
						INNER JOIN referentiel.departement USING(iddep)
						WHERE comsaisie.poly @> :recherche ");
	$req->bindValue(':recherche', '('.$x.','.$y.')');
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['x']))
{	
	$x = $_POST['x'];
	$y = $_POST['y'];
	$dep = $_POST['dep'];
	
	$recup = ($dep == 'oui') ? recupdep($x,$y) : recup($x,$y);
	
	if($recup['codecom'] != '')
	{
		$retour['com'] = $recup;
		$retour['emp'] = 'Oui';
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