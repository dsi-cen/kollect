<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function insere_photo($cdnom,$idobser,$datep,$codecom,$stade,$nomphoto,$dates,$sexe,$obser,$idobs,$ordre)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO site.photo (cdnom, idobser, datephoto, codecom, stade, nomphoto, datesaisie, sexe, observatoire, idobs, ordre) VALUES(:cdnom, :idobser, :datep, :codecom, :stade, :nom, :dates, :sexe, :obser, :idobs, :ordre) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':idobser', $idobser);
	$req->bindValue(':datep', $datep);
	$req->bindValue(':codecom', $codecom);
	$req->bindValue(':stade', $stade);
	$req->bindValue(':nom', $nomphoto);
	$req->bindValue(':dates', $dates);
	$req->bindValue(':sexe', $sexe);
	$req->bindValue(':obser', $obser);
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':ordre', $ordre);
	if($req->execute())
	{
		$idphoto = $bdd->lastInsertId('site.photo_idphoto_seq');
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
	$obser = $_POST['obserph'];
	$nomphoto = $_POST['nomphoto'];
	$dossier_destination1 = '../../../photo/P800/'.$obser.'/';
	$dossier_destination2 = '../../../photo/P400/'.$obser.'/';
	$dossier_destination3 = '../../../photo/P200/'.$obser.'/';
	$nomfichier = $nomphoto.'.jpg';
	
	$img = $_POST['image-data'];
	$exp = explode(',', $img);
	$data = base64_decode($exp[1]);
	$file = $dossier_destination1 . $nomfichier;
	//file_put_contents($file, $data);
	//$img = str_replace('data:image/jpeg;base64,', '', $img);
	//$img = str_replace(' ', '+', $img);
	//$data = base64_decode($img);
	//$file = $dossier_destination1 . $nomfichier;
	//$success = file_put_contents($file, $data);
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
			$idobser = $_POST['idobserph'];
			$datep = $_POST['dateph'];
			$codecom = $_POST['codecomph'];
			$stade = $_POST['stadeph'];
			$sexe = $_POST['sexe'];
			$dates = date("Y-m-d H:i:s");
			$cdnom = $_POST['cdnomph'];
			$idobs = $_POST['idobsph'];
			$ordre = $_POST['ordreph'];
			$idphoto = insere_photo($cdnom,$idobser,$datep,$codecom,$stade,$nomphoto,$dates,$sexe,$obser,$idobs,$ordre);
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