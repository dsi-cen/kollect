<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function modvali($cdnom,$stade,$photo,$son,$loupe,$bino,$idstade)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE vali.critere SET stade = :stade, photo = :photo, son = :son, loupe = :loupe, bino = :bino, idstade = :idstade WHERE cdnom = :cdnom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':stade', $stade);
	$req->bindValue(':photo', $photo);
	$req->bindValue(':son', $son);
	$req->bindValue(':loupe', $loupe);
	$req->bindValue(':bino', $bino);
	$req->bindValue(':idstade', $idstade);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;
}

if(isset($_POST['cdnom']))
{	
	$cdnom = $_POST['cdnom'];
	$idstade = $_POST['idstade'];
	$stade = $_POST['stade'];
	$photo = $_POST['photo'];
	$son = $_POST['son'];
	$loupe = $_POST['loupe'];
	$bino = $_POST['bino'];
	
	$vali = modvali($cdnom,$stade,$photo,$son,$loupe,$bino,$idstade);
	
	$retour['statut'] = ($vali == 'oui') ? 'Oui' : 'Non';
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);	
?>