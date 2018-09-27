<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function recupinfo($idphoto)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nomphoto, observatoire FROM site.photo WHERE idphoto = :idphoto ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idphoto', $idphoto, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function supprime_photo($idphoto)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM site.photo WHERE idphoto = :idphoto ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idphoto', $idphoto);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;
}

if(isset($_POST['idphoto'])) 
{
	$idphoto = $_POST['idphoto'];
	
	$info = recupinfo($idphoto);
	
	$vali = supprime_photo($idphoto);
	if($vali == 'oui')
	{
		$photo800 = '../../../photo/P800/'.$info['observatoire'].'/'.$info['nomphoto'].'.jpg';
		if(file_exists($photo800))
		{
			copy($photo800, '../../../photo/sup/'.$info['nomphoto'].'.jpg');
			$retour['copie'] = 'Oui';
		}
		
		$cheminfichier200 = '../../../photo/P200/'.$info['observatoire'].'/'.$info['nomphoto'].'.jpg';
		$cheminfichier400 = '../../../photo/P400/'.$info['observatoire'].'/'.$info['nomphoto'].'.jpg';
		$cheminfichier800 = '../../../photo/P800/'.$info['observatoire'].'/'.$info['nomphoto'].'.jpg';
		if(file_exists($cheminfichier200)) { unlink($cheminfichier200); }
		if(file_exists($cheminfichier400)) { unlink($cheminfichier400); }
		if(file_exists($cheminfichier800)) { unlink($cheminfichier800); }
				
		$retour['statut'] = 'Oui';
	}
	else
	{
		$retour['statut'] = 'Non';
	}	
}
else
{
	$retour['statut'] = 'Problème';
}
echo json_encode($retour);
?>