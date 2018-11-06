<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function mod($observa)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE referentiel.liste SET vali = 1 WHERE observatoire = :observa AND vali = 0 ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':observa', $observa);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;
}

if(isset($_POST['sel']))
{	
	$observa = $_POST['sel'];
			
	$vali = mod($observa);
	
	$retour['statut'] = ($vali == 'oui') ? 'Oui' : 'Non';
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);	
?>