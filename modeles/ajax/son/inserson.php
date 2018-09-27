<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function insere_son($cdnom,$idobser,$dateson,$nomson,$dates,$idobs,$descri)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO site.son (cdnom, idobser, nomson, datesaisie, idobs, descri, dateson) VALUES(:cdnom, :idobser, :nom, :dates, :idobs, :descri, :dateson) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':idobser', $idobser);
	$req->bindValue(':dateson', $dateson);
	$req->bindValue(':nom', $nomson);
	$req->bindValue(':dates', $dates);
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':descri', $descri);
	$idson = ($req->execute()) ? $bdd->lastInsertId('site.son_idson_seq') : 'non';
	$req->closeCursor();
	return $idson;	
}

if(isset($_POST['idobs']))
{
	$taillemax = 2000000;
	$nomson = $_POST['nomson'];
	if(isset($_FILES['mp']['name']) && $_FILES['mp']['name'] != '')
	{
		$extension = strtolower(substr(strrchr($_FILES['mp']['name'], '.'),1));
		if($extension == 'mp3')
		{
			$taille = filesize($_FILES['mp']['tmp_name']);
			$retour['taille'] = $taille;
			if($taille > $taillemax)
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert">Votre fichier est trop lourd.</div>';
				echo json_encode($retour);	
				exit;
			}				
			$dossier_destination = '../../../son/';
			$repSource = $_FILES['mp']['tmp_name'];
			$sonDest = $nomson.'.mp3';
			$destination = $dossier_destination . $sonDest; 
			$ok = move_uploaded_file($repSource, $destination);
			if ($ok == true)
			{
				$idobser = $_POST['idobser'];
				$cdnom = $_POST['cdnom'];
				$idobs = $_POST['idobs'];
				$dateson = $_POST['dates'];
				$dates = date("Y-m-d H:i:s");
				$descri = htmlspecialchars($_POST['descri']);
				
				$idson =  insere_son($cdnom,$idobser,$dateson,$nomson,$dates,$idobs,$descri);
				if($idson != 'non')
				{
					$retour['statut'] = 'Oui';
					$retour['son'] = $nomson;
					
				}
				else
				{
					$retour['statut'] = 'Non';
					$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! Problème lors de l\'enregistrement du fichier son dans la table.</div>';
				}
			}
		}
	}	
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! Aucun fichier son.</div>';
	}	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! Aucune observation liée.</div>';
}
echo json_encode($retour);	