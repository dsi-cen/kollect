<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function insere_com($idpdet,$idm,$com,$datecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO site.comdet (idpdet,idm,commentaire,datecom) VALUES(:idpdet, :idm, :com, :datecom) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idm);
	$req->bindValue(':idpdet', $idpdet);
	$req->bindValue(':com', $com);
	$req->bindValue(':datecom', $datecom);
	$ok = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $ok;	
}
function chercheidm($idpdet)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT idm FROM site.comdet WHERE idpdet = :idpdet ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idpdet', $idpdet, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function insere_notif($idpdet,$idmor)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO site.notif (idm,type,idtype)
						VALUES(:idm, :type, :idpdet) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idmor);
	$req->bindValue(':idpdet', $idpdet);
	$req->bindValue(':type', 'det');
	$ok = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
}
if(isset($_POST['idpdet']) && isset($_POST['com']))
{
	$idpdet = $_POST['idpdet'];
	$idm = $_SESSION['idmembre'];
	$idmor = $_POST['idmor'];
	$com = htmlspecialchars($_POST['com']);
	$com = str_replace(array("\r\n", "\n", "\r"), ' ', $com);
	
	$datecom = date("Y-m-d H:i:s");
	
	$vali = insere_com($idpdet,$idm,$com,$datecom);
	if($vali == 'oui')
	{
		if($idmor != $idm)
		{
			insere_notif($idpdet,$idmor);
		}
		elseif($idmor == $idm)
		{
			$lidm = chercheidm($idpdet);
			if($lidm != false)
			{
				foreach($lidm as $n)
				{
					if($n['idm'] != $idm)
					{	
						insere_notif($idpdet,$n['idm']);
					}
				}
			}			
		}		
	}	
	$retour['statut'] = ($vali == 'oui') ? 'Oui' : 'Non';
	echo json_encode($retour);
}

	