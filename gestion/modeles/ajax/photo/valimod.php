<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function modphoto($idphoto,$stade,$idobser,$sexe,$observa)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE site.photo SET idobser = :idobser, stade = :stade, sexe = :sexe, observatoire = :observa WHERE idphoto = :idphoto ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idphoto', $idphoto);
	$req->bindValue(':idobser', $idobser);
	$req->bindValue(':stade', $stade);
	$req->bindValue(':sexe', $sexe);
	$req->bindValue(':observa', $observa);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;
}

if(isset($_POST['idphoto']) && isset($_POST['auteur']))
{	
	$idobser = $_POST['auteur'];
	$stade = $_POST['stade'];
	$idphoto = $_POST['idphoto'];
	$sexe = $_POST['sexe'];
	$observa = $_POST['observa'];
	
	$vali = modphoto($idphoto,$stade,$idobser,$sexe,$observa);
	
	$retour['statut'] = ($vali == 'oui') ? 'Oui' : 'Non';
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);	
?>