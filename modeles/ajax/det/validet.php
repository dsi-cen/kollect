<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function inser_det($idpdet,$idm,$cdnom,$dates,$ndet)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO site.determination (idpdet, idm, cdnom, datedet, ndet) VALUES(:idpdet, :idm, :cdnom, :dates, :ndet) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idm);
	$req->bindValue(':idpdet', $idpdet);
	$req->bindValue(':dates', $dates);
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':ndet', $ndet);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function mod_photodet($idpdet,$observa,$val)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE site.photodet SET vali = :val, observa = :observa WHERE idpdet = :idpdet ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idpdet', $idpdet, PDO::PARAM_INT);
	$req->bindValue(':observa', $observa);
	$req->bindValue(':val', $val);
	$req->execute();
	$req->closeCursor();
}
function insere_notif($idpdet,$idmor)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO site.notif (idm,type,idtype)
						VALUES(:idm, :type, :idpdet) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idmor);
	$req->bindValue(':idpdet', $idpdet);
	$req->bindValue(':type', 'det');
	$ok = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
}
function insere_com($idpdet,$idm,$com,$datecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO site.comdet (idpdet,idm,commentaire,datecom) VALUES(:idpdet, :idm, :com, :datecom) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idm);
	$req->bindValue(':idpdet', $idpdet);
	$req->bindValue(':com', $com);
	$req->bindValue(':datecom', $datecom);
	$ok = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $ok;	
}

if(isset($_POST['idpdet']))
{
	$idpdet = $_POST['idpdet'];
	$cdnom = $_POST['cdnom'];
	$idm = $_SESSION['idmembre'];
	$idmor = $_POST['idmor'];
	$dates = date("Y-m-d");
	$taxon = $_POST['taxon'];
	$observa = $_POST['observa'];
	$ndet = $_POST['ndet'];
	$hsite = $_POST['hsite'];
	
	
	if($ndet == 'oui' || $hsite == 'oui')
	{
		$cdnom = 0;
		if($hsite == 'oui') { $observa = 'NR'; }
		$ndet = 'oui';
	}
	
	$vali = inser_det($idpdet,$idm,$cdnom,$dates,$ndet);
		
	if($vali == 'oui')
	{
		$val = ($ndet == 'oui' || $hsite == 'oui') ? 'nde' : 'oui';		
		mod_photodet($idpdet,$observa,$val);
		$datecom = date("Y-m-d H:i:s");
		$com = 'Commentaire automatique : Détermination faite par '.$_POST['membre'].' - '.$taxon;
		insere_com($idpdet,$idm,$com,$datecom);
		if($idmor != $idm)
		{
			insere_notif($idpdet,$idmor);
		}		
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