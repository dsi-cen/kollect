<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function cherchephoto($idactu)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idphoto, nom FROM actu.photoactu WHERE idactu = :id ");
	$req->bindValue(':id', $idactu, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function supprime_photo($idphoto)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM actu.photoactu WHERE idphoto = :id ");
	$req->bindValue(':id', $idphoto);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;
}
function supprime_actu($idactu)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM actu.actu WHERE idactu = :id ");
	$req->bindValue(':id', $idactu);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;
}

if (isset($_POST['idactu']))
{
	$idactu = $_POST['idactu'];
	
	$actu = supprime_actu($idactu);
	
	if($actu == 'oui')
	{
		//sup photo
		$photo = cherchephoto($idactu);
		if($photo['idphoto'] != '')
		{
			$photosup = supprime_photo($photo['idphoto']);
			if($photosup == 'oui')
			{
				unlink('../../../../photo/article/P800/'.$photo['nom'].'.jpg');
				unlink('../../../../photo/article/P400/'.$photo['nom'].'.jpg');
				unlink('../../../../photo/article/P200/'.$photo['nom'].'.jpg');
			}
		}
		//sup doc
		$retour['statut'] = 'Oui';
	}
	else
	{
		$retour['statut'] = 'Non';
	}	
	
	echo json_encode($retour);	
}