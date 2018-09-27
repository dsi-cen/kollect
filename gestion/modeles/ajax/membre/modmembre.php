<?php
if(isset($_POST['id']))
{
	include '../../../../global/configbase.php';
	include '../../../../lib/pdo2.php';
	//M
	function mod_membre($id,$nom,$prenom,$mail,$droits,$gestion)
	{
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("UPDATE site.membre SET nom = :nom, prenom = :prenom, droits = :droits, mail = :mail, gestionobs = :gestion WHERE idmembre = :id ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':id', $id);
		$req->bindValue(':nom', $nom);
		$req->bindValue(':prenom', $prenom);
		$req->bindValue(':mail', $mail);
		$req->bindValue(':droits', $droits);
		$req->bindValue(':gestion', $gestion);
		$req->execute();
		$req->closeCursor();
	}
	function cherche_validateur($id)
	{
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT discipline FROM site.validateur WHERE idmembre = :id ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':id', $id);
		$req->execute();
		$nbresultats = $req->rowCount();
		$resultat = $req->fetch();
		$req->closeCursor();
		return array($nbresultats, $resultat);
	}
	function modif_validateur($id,$disc)
	{
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("UPDATE site.validateur SET discipline = :disc WHERE idmembre = :id ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':id', $id);
		$req->bindValue(':disc', $disc);
		$req->execute();
		$req->closeCursor();
	}
	function sup_validateur($id)
	{
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("DELETE FROM site.validateur WHERE idmembre = :id ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':id', $id);
		$req->execute();
		$req->closeCursor();
	}
	function insere_validateur($id,$disc)
	{
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("INSERT INTO site.validateur (idmembre, discipline) VALUES(:id, :disc) ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':id', $id);
		$req->bindValue(':disc', $disc);
		$req->execute();
		$req->closeCursor();
	}
	//C
	$id = $_POST['id'];
	$nom = $_POST['nom'];
	$prenom = $_POST['prenom'];
	$mail =  $_POST['mail'];
	$droits = $_POST['droits'];
	//$disc = substr($_POST['disc'], 0, -2);
	$disc = $_POST['disc'];
	$gestion = $_POST['gestion'];
	mod_membre($id,$nom,$prenom,$mail,$droits,$gestion);
	$vali = cherche_validateur($id);
	if($vali[0] != 0)
	{
		$valior = $vali[1]['discipline'];
		if (($disc != $valior) and ($disc != ''))
		{
			modif_validateur($id,$disc);
		}
		elseif (($disc != $valior) and ($disc == ''))
		{
			sup_validateur($id);
		}
	}
	else
	{
		if ($disc != '')
		{
			insere_validateur($id,$disc);
		}
	}		
	$retour['statut'] = 'Ok';	
}
else 
{$retour['statut'] = 'Tous les champs ne sont pas parvenus';}
echo json_encode($retour);