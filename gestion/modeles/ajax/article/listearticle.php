<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function recherche($sel)
{
	$bdd = PDO2::getInstance();		
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idarticle, article, titre, soustitre FROM site.article WHERE typear = :sel ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':sel', $sel);
	$req->execute();
	$article = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $article;		
}

if (isset($_POST['sel']))
{
	$sel = $_POST['sel'];
	$article = recherche($sel);
	
	if($article != false)
	{
		$retour['statut'] = 'Oui';	
		$retour['article'] = $article;
	}
	else
	{
		$retour['statut'] = 'Non';
	}
}
echo json_encode($retour);