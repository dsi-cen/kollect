<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function inserearticle($article,$sel,$titre,$stitre)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO site.article (typear, titre, soustitre, article) VALUES(:sel, :titre, :stitre, :article) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':sel', $sel);
	$req->bindValue(':titre', $titre);
	$req->bindValue(':stitre', $stitre);
	$req->bindValue(':article', $article);
	$vali = ($req->execute()) ? $bdd->lastInsertId('site.article_idarticle_seq') : 'non';
	$req->closeCursor();
	return $vali;
}
function modarticle($idarticle,$article,$titre,$stitre)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE site.article SET titre =:titre, soustitre =:stitre, article =:article WHERE idarticle = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $idarticle);
	$req->bindValue(':titre', $titre);
	$req->bindValue(':stitre', $stitre);
	$req->bindValue(':article', $article);
	$vali = ($req->execute()) ? 'oui' : '';
	$req->closeCursor();
	return $vali;
}
if(isset($_POST['article']) && isset($_POST['idarticle']))
{
	$article = $_POST['article'];
	$idarticle = $_POST['idarticle'];
	$titre = $_POST['titre'];
	$stitre = $_POST['stitre'];
	$sel = $_POST['sel'];
	$sel1 = $_POST['sel1'];
	
	if($idarticle == '')
	{
		$vali = inserearticle($article,$sel,$titre,$stitre);
		if($vali != 'non')
		{
			$retour['statut'] = 'Oui';
			$retour['idarticle'] = $vali;
			$retour['mes'] = '<div class="alert alert-success" role="alert">L\'article '.$sel1.' a bien été enregistré.</div>';
		}
		else
		{
			$retour['statut'] = 'Non';
		}
	}
	else
	{
		$vali = modarticle($idarticle,$article,$titre,$stitre);
		if($vali == 'oui')
		{
			$retour['statut'] = 'Oui';
			$retour['mes'] = '<div class="alert alert-success" role="alert">L\'article '.$sel1.' a bien été enregistré.</div>';
		}
		else
		{
			$retour['statut'] = 'Non';
		}
	}
	echo json_encode($retour);	
}