<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function verif($cdnom,$codecom,$date)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idfiche, idobs FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						WHERE cdref = :cdnom AND fiche.codecom = :codecom AND date1 = :date ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':codecom', $codecom);
	$req->bindValue(':date', $date);
	$req->execute();
	$nbresultats = $req->rowCount();
	$req->closeCursor();
	return $nbresultats;
}
if (!empty($_POST["cdnom"]) && !empty($_POST["codecom"]) && !empty($_POST["date"]))
{
	$cdnom = $_POST['cdnom'];
	$codecom = $_POST['codecom'];
	$datetmp = DateTime::createFromFormat('d/m/Y', $_POST['date']);
	$date = $datetmp->format('Y-m-d');
	
	$verif = verif($cdnom,$codecom,$date);
		
	$retour['statut'] = ($verif > 0) ? 'Oui' : 'Non';
	$retour['er'] = 'Non';
}
else
{
	$retour['statut'] = 'Non';
	$retour['er'] = 'Oui';
}
echo json_encode($retour);	