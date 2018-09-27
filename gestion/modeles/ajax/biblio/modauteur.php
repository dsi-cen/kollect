<?php
if(isset($_POST['id']) AND isset($_POST['nom']) AND isset($_POST['prenom']))
{
	include '../../../../global/configbase.php';
	include '../../../../lib/pdo2.php';
	//M
	function modifie_auteurs($id, $nom, $prenom, $prenomab)
	{
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("UPDATE biblio.auteurs SET nom = :nom, prenom = :prenom, prenomab = :prenomab WHERE idauteur = :id ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':id', $id);
		$req->bindValue(':nom', $nom);
		$req->bindValue(':prenom', $prenom);
		$req->bindValue(':prenomab', $prenomab);
		if ($req->execute())
		{
			$vali = 'oui';
		} 
		else
		{
			$vali = '';
		}
		$req->closeCursor();
		return $vali;	
	}
	//C
	$id = $_POST['id'];
	$nom = $_POST['nom'];
	$prenom = $_POST['prenom'];
	$prenomab = $_POST['prenomab'];
	$vali = modifie_auteurs($id, $nom, $prenom, $prenomab);
	if ($vali == 'oui')
	{
		$retour['statut'] = array("Ok"=>'Ok');
	}
	else
	{
		$retour['statut'] = 'Un probleme est survenu';
	}
}
else 
{$retour['statut'] = 'Tous les champs ne sont pas parvenus';}
echo json_encode($retour);