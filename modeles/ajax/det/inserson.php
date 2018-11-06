<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function insere_photo($idm,$codecom,$datep,$dates,$nomphoto,$nomini,$rq,$observa)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO site.photodet (idm, codecom, datephoto, datesaisie, nomphoto, nomini, rq, vali, typef, observa) VALUES(:idm, :codecom, :datep, :dates, :nom, :ini, :rq, :vali, :typef, :observa) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idm);
	$req->bindValue(':codecom', $codecom);
	$req->bindValue(':datep', $datep);
	$req->bindValue(':dates', $dates);
	$req->bindValue(':nom', $nomphoto);
	$req->bindValue(':ini', $nomini);
	$req->bindValue(':rq', $rq);
	$req->bindValue(':vali', 'non');
	$req->bindValue(':typef', 'son');
	$req->bindValue(':observa', $observa);
	if($req->execute())
	{
		$idson = $bdd->lastInsertId('site.photodet_idpdet_seq');
	}
	else
	{
		$idson = 'non';
	}
	$req->closeCursor();
	return $idson;	
}

if(isset($_FILES['mp']['name']) && $_FILES['mp']['name'] != '')
{
	$taillemax = 2000000;
	$extension = strtolower(substr(strrchr($_FILES['mp']['name'], '.'),1));
	if($extension == 'mp3')
	{
		$taille = filesize($_FILES['mp']['tmp_name']);
		if($taille > $taillemax)
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Votre fichier est trop lourd.</div>';
			echo json_encode($retour);	
			exit;
		}
		$nomson = 'det'.time();
		$dossier_destination = '../../../son/det/';
		$repSource = $_FILES['mp']['tmp_name'];
		$sonDest = $nomson.'.mp3';
		$destination = $dossier_destination . $sonDest; 
		$ok = move_uploaded_file($repSource, $destination);
		if($ok == true)
		{
			//enregistrement bdd
			$idm = $_POST['idms'];
			$datep = $_POST['dates'];
			$codecom = $_POST['codecoms'];
			$observa = $_POST['observas'];
			$rq = htmlspecialchars($_POST['rqs']);
			$dates = date("Y-m-d H:i:s");
			$nomini = $_FILES['mp']['name'];
			$idson = insere_photo($idm,$codecom,$datep,$dates,$nomson,$nomini,$rq,$observa);
			if($idson != 'non')
			{
				$retour['statut'] = 'Oui';
				$retour['idson'] = $idson;
			}
			else
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! Problème lors de l\'enregistrement du fichier son dans la table.</div>';
			}			
		}
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! Problème cd n\'est pas un ficher mp3.</div>';
	}
}
echo json_encode($retour);	