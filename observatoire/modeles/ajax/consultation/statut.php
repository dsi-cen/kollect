<?php
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function liste($statut)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT lr FROM statut.statut WHERE cdprotect = :cd ");
	$req->bindValue(':cd', $statut);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['statut']))
{
	$statut = $_POST['statut'];
	
	$liste = liste($statut);
	
	if($liste != false)
	{
		$tmp = '<option value="NR">-- choisir au besoin --</option>';
		foreach($liste as $n)
		{
			$tmp .= '<option data-cd="'.$statut.'" value="'.$n['lr'].'">'.$n['lr'].'</option>';
		}
		$retour['lr'] = $tmp;
	}
	
	$retour['statut'] = 'Oui';		
}	
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);