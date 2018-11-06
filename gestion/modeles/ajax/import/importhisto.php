<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function insere_histo($dates,$idm,$prem,$dern,$desc)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO import.histo (dateimport, idm, idobsdeb, idobsfin, descri) VALUES(:date, :idm, :prem, :dern, :desc) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':date', $dates);
	$req->bindValue(':idm', $idm);
	$req->bindValue(':prem', $prem);
	$req->bindValue(':dern', $dern);
	$req->bindValue(':desc', $desc);
	$ok = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $ok;
}

if(isset($_POST['idm']))
{
	$idm = $_POST['idm'];
	$prem = $_POST['prem'];
	$dern = $_POST['dern'];
	$desc = $_POST['desc'];
	$dates = date("Y-m-d");
	
	$ok = insere_histo($dates,$idm,$prem,$dern,$desc);
	
	if($ok == 'oui')
	{
		$retour['statut'] = 'Oui';
	}
	else
	{
		$retour['statut'] = 'Non';
	}	
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);