<?php
if(isset($_POST['id']))
{
	include '../../../../global/configbase.php';
	include '../../../../lib/pdo2.php';
	
	function mod_obser($id,$nom,$prenom,$idm,$aff)
	{
		$bdd = PDO2::getInstance();
		$bdd->query("SET NAMES 'UTF8'");
		$req = $bdd->prepare("UPDATE referentiel.observateur SET observateur = :obser, nom = :nom, prenom = :prenom, idm = :idm, aff = :aff WHERE idobser = :id ");
		$req->bindValue(':id', $id);
		$req->bindValue(':obser', $nom.' '.$prenom);
		$req->bindValue(':nom', $nom);
		$req->bindValue(':prenom', $prenom);
		$req->bindValue(':idm', $idm);
		$req->bindValue(':aff', $aff);
		$ok = ($req->execute()) ? 'oui' : 'non';
		$req->closeCursor();
		return $ok;
	}	
	
	$id = $_POST['id'];
	$nom = $_POST['nom'];
	$prenom = $_POST['prenom'];
	$aff = ($_POST['aff'] == '' || $_POST['aff'] == 'oui') ? 'oui' : 'non';
	$idm = ($_POST['idm'] == '') ? null : $_POST['idm'];
	$vali = mod_obser($id,$nom,$prenom,$idm,$aff);
	$retour['statut'] = ($vali == 'oui') ? 'Ok' : 'Erreur ! Problème lors de la modification';
	
}
else 
{$retour['statut'] = 'Tous les champs ne sont pas parvenus';}
echo json_encode($retour);