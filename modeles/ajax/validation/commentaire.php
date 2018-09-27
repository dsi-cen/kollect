<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function insere_com($idobs,$idm,$rq,$datecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO vali.comvali (idobs,idm,commentaire,datecom) VALUES(:idobs, :idm, :com, :datecom) ");
	$req->bindValue(':idm', $idm);
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':com', $rq);
	$req->bindValue(':datecom', $datecom);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function chercheidm($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT idm FROM vali.comvali WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function insere_notif($idobs,$idmor)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO site.notif (idm,type,idtype) VALUES(:idm, :type, :idobs) ");
	$req->bindValue(':idm', $idmor);
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':type', 'vali');
	$req->execute();
	$req->closeCursor();
}
function supnotif($idobs,$idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM site.notif WHERE idtype = :idobs AND idm = :idm ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':idm', $idm);
	$req->execute();
	$req->closeCursor();
}

if(isset($_POST['idobs']))
{
	$idobs = $_POST['idobs'];
	$idm = $_SESSION['idmembre'];
	$rq = htmlspecialchars($_POST['rq']);
	$rq = str_replace(array("\r\n", "\n", "\r"), ' ', $rq);
	
	$datecom = date("Y-m-d H:i:s");
	
	$vali = insere_com($idobs,$idm,$rq,$datecom);
	
	if($vali == 'oui')
	{
		$lidm = chercheidm($idobs);
		foreach($lidm as $n)
		{
			if($n['idm'] != $idm)
			{	
				insere_notif($idobs,$n['idm']);
			}
		}
		supnotif($idobs,$idm);	
	}	
	$retour['statut'] = ($vali == 'oui') ? 'Oui' : 'Non';
	echo json_encode($retour);
}

	