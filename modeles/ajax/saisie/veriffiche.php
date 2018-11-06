<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function verif($idobser,$date1,$idsite,$idcoord)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idfiche FROM obs.fiche WHERE idobser = :idobser AND idsite = :idsite AND idcoord = :idcoord AND date1 = :date ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobser', $idobser, PDO::PARAM_INT);
	$req->bindValue(':idsite', $idsite, PDO::PARAM_INT);
	$req->bindValue(':date', $date1);
	$req->bindValue(':idcoord', $idcoord, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
if(isset($_POST['idobser']))
{	
	$date1 = DateTime::createFromFormat('d/m/Y', $_POST['date']);
	$date1mysql = $date1->format('Y-m-d');	
	$retour['verif'] = verif($_POST['idobser'],$date1mysql,$_POST['idsite'],$_POST['idcoord']);
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';	
}
echo json_encode($retour);	
?>