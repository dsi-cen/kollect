<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
function cherche_famille($nomvar,$id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cat FROM $nomvar.categorie WHERE famille = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->rowCount();
	$req->closeCursor();
	return $resultat;
}
function modifcategorie($nomvar,$id,$cat)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE $nomvar.categorie SET cat =:cat WHERE famille = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->bindValue(':cat', $cat);
	$vali = ($req->execute()) ? 'oui' : '';
	$req->closeCursor();
	return $vali;
}
function insercategorie($nomvar,$id,$cat)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO $nomvar.categorie (famille, cat) VALUES(:id, :cat) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->bindValue(':cat', $cat);
	$vali = ($req->execute()) ? 'oui' : '';
	$req->closeCursor();
	return $vali;
}
if (isset($_POST['sel']) && isset($_POST['id']) && isset($_POST['cat']))
{	
	$nomvar = $_POST['sel'];	
	$id = $_POST['id'];
	$cat = $_POST['cat'];

	$fam = cherche_famille($nomvar,$id);
	if ($fam != 0)
	{
		$vali = modifcategorie($nomvar,$id,$cat);
	}
	else
	{
		$vali = insercategorie($nomvar,$id,$cat);
	}
	if ($vali == 'oui')
	{
		$retour['statut'] = 'Oui';	
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! Problème lors de la modification ou insertion de '.$cat.' dans table categorie du schéma '.$nomvar.'.</p></div>';
	}		
	echo json_encode($retour);	
}