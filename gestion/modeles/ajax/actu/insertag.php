<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
function rechercher_tag($tag)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT tag FROM actu.tag WHERE tag = :tag ");
	$req->bindValue(':tag', $tag);
	$req->execute();
	$nbresultats = $req->rowCount();
	$req->closeCursor();
	return $nbresultats;
}
function insere_tag($tag)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO actu.tag (tag) VALUES (:tag) ");
	$req->bindValue(':tag', $tag);
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
if(isset($_POST['tag']))
{
	$tag = $_POST['tag'];
	$nbresultats = rechercher_tag($tag);
	if ($nbresultats == 0)
	{
		$vali = insere_tag($tag);
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
	{
		$retour['statut'] = 'Il existe déjà un tag : '.$tag.'';
	}
}
else 
{$retour['statut'] = 'Tous les champs ne sont pas parvenus';}
echo json_encode($retour);