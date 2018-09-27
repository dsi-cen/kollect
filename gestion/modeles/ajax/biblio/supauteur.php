<?php
if (isset($_POST["id"]))
{
	include '../../../../global/configbase.php';
	include '../../../../lib/pdo2.php';
	//M
	function cherche_auteur($id)
	{
		$bdd = PDO2::getInstance();
		$bdd->query('SET NAMES "utf8"');
		$req = $bdd->prepare("SELECT idbiblio FROM biblio.biblioauteur WHERE idauteur = :id ") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':id', $id);
		$req->execute();
		$nbresultats = $req->rowCount();
		$resultat = $req->fetchAll();
		$req->closeCursor();
		return array($nbresultats, $resultat);
	}
	function supprime_auteur($id)
	{
		$bdd = PDO2::getInstance();
		$req = $bdd->prepare("DELETE FROM biblio.auteurs WHERE idauteur = :id") or die(print_r($bdd->errorInfo()));
		$req->bindValue(':id', $id, PDO::PARAM_INT);
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
	$id = $_POST["id"];
	$listebiblio = cherche_auteur($id);
	if ($listebiblio[0] != 0)
	{
		foreach ($listebiblio[1] as $n)
		{
			$retourliste[] = $n['idbiblio'];
		}
		$retourliste = implode(', ',$retourliste);
		$retour['statut'] = array("Ok"=>'non');
		$retour['liste'] = $retourliste;
	}
	else
	{
		$vali = supprime_auteur($id);
		if ($vali == 'oui')
		{
			$retour['statut'] = array("Ok"=>'Ok');
		}
		else
		{
			$retour['statut'] = 'Un probleme est survenu';
		}
	}	
	echo json_encode($retour);
}