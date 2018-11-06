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
	$req->bindValue(':typef', 'photo');
	$req->bindValue(':observa', $observa);
	if($req->execute())
	{
		$idphoto = $bdd->lastInsertId('site.photodet_idpdet_seq');
	}
	else
	{
		$idphoto = 'non';
	}
	$req->closeCursor();
	return $idphoto;	
}

if(isset($_POST['image-data']))
{
	$nomini = $_POST['nomphoto'];
	$nomphoto = 'det'.time();
	
	$dossier_destination1 = '../../../photo/det/p800/';
	$dossier_destination2 = '../../../photo/det/p400/';
	$dossier_destination3 = '../../../photo/det/p200/';
	$nomfichier = $nomphoto.'.jpg';
	
	$img = $_POST['image-data'];
	$exp = explode(',', $img);
	$data = base64_decode($exp[1]);
	$file = $dossier_destination1 . $nomfichier;
	
	if(file_put_contents($file, $data) !== false) 
	{
		require '../../../lib/RedimImageJpg.php';
		$orien = $_POST['orien'];
		$repSource = $dossier_destination1;
		$repDest = $dossier_destination2;
		$redim = ($orien == 'paysage') ? fctredimimage(400,266,$repDest,'',$repSource,$nomfichier) : fctredimimage(200,300,$repDest,'',$repSource,$nomfichier);
		$repDest = $dossier_destination3;
		$redim = ($orien == 'paysage') ? fctredimimage(200,133,$repDest,'',$repSource,$nomfichier) : fctredimimage(100,150,$repDest,'',$repSource,$nomfichier);
		if ($redim == true) 
		{ 
			//enregistrement bdd
			$idm = $_POST['idm'];
			$datep = $_POST['dateph'];
			$codecom = $_POST['codecom'];
			$observa = $_POST['observa'];
			$rq = htmlspecialchars($_POST['rq']);
			$dates = date("Y-m-d H:i:s");
			$idphoto = insere_photo($idm,$codecom,$datep,$dates,$nomphoto,$nomini,$rq,$observa);
			if($idphoto != 'non')
			{
				$retour['statut'] = 'Oui';
				$retour['idphoto'] = $idphoto;
			}
			else
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! Problème lors de l\'enregistrement de la photo dans la table.</div>';
			}
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! Problème lors du redimensionnement de la photo.</div>';
			echo json_encode($retour);	
			exit;
		}		
	}
	else
	{
		$retour['statut'] = 'Non'; 
	}	
}
echo json_encode($retour);	