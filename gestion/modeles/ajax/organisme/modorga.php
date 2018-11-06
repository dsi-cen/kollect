<?php
if(isset($_POST['id']))
{
	include '../../../../global/configbase.php';
	include '../../../../lib/pdo2.php';
	
	function mod($id,$orga,$descri)
	{
		$bdd = PDO2::getInstance();
		$bdd->query("SET NAMES 'UTF8'");
		$req = $bdd->prepare("UPDATE referentiel.organisme SET organisme = :orga, descri = :descri WHERE idorg = :id ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':id', $id);
		$req->bindValue(':orga', $orga);
		$req->bindValue(':descri', $descri);
		$ok = ($req->execute()) ? 'oui' : 'non';
		$req->closeCursor();
		return $ok;
	}	
	
	$id = $_POST['id'];
	$orga = $_POST['orga'];
	$descri = $_POST['descri'];
	
	$vali = mod($id,$orga,$descri);
	$retour['statut'] = ($vali == 'oui') ? 'Ok' : 'Erreur ! Problème lors de la modification';
	
}
else 
{$retour['statut'] = 'Tous les champs ne sont pas parvenus';}
echo json_encode($retour);