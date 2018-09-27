<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function insere_com($idobs,$idm,$com,$datecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO site.comobs (idobs,idm,commentaire,datecom) VALUES(:idobs, :idm, :com, :datecom) ");
	$req->bindValue(':idm', $idm);
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':com', $com);
	$req->bindValue(':datecom', $datecom);
	$ok = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $ok;	
}
function cherchecom($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(*) FROM site.liencom WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function chercheidm($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT DISTINCT idm FROM site.comobs WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function modif_liencom($idobs)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("UPDATE site.liencom SET nbcom = 2 WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs);
	$ok = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $ok;
}
function inser_liencom($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO site.liencom (idobs,nbcom) VALUES(:idobs, 1) ");
	$req->bindValue(':idobs', $idobs);
	$ok = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $ok;	
}
function insere_notif($idobs,$idmor)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO site.notif (idm,type,idtype) VALUES(:idm, :type, :idobs) ");
	$req->bindValue(':idm', $idmor);
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':type', 'comobs');
	$ok = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
}
if (isset($_POST['idobs']) && isset($_POST['idm']))
{
	$idobs = $_POST['idobs'];
	$idm = $_POST['idm'];
	$idmor = $_POST['idmor'];
	$com = htmlspecialchars($_POST['com']);
	$com = str_replace(array("\r\n", "\n", "\r"), ' ', $com);
		
	$datecom = date("Y-m-d H:i:s");
	
	$vali = insere_com($idobs,$idm,$com,$datecom);
	if($vali == 'oui')
	{
		$nbcom = cherchecom($idobs);
		$vali = ($nbcom >= 1) ? modif_liencom($idobs) : inser_liencom($idobs);
		
		if($idmor != $idm)
		{
			insere_notif($idobs,$idmor);
		}
		elseif($idmor == $idm)
		{
			$lidm = chercheidm($idobs);
			if($lidm != false)
			{
				foreach($lidm as $n)
				{
					if($n['idm'] != $idm)
					{	
						insere_notif($idobs,$n['idm']);
					}
				}
			}			
		}		
	}	
	$retour['statut'] = ($vali == 'oui') ? 'Oui' : 'Non';
	echo json_encode($retour);
}

	