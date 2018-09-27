<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
session_start();

function verif($idok,$idnon)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT COUNT(*) FROM referentiel.observateur WHERE idobser = :idok OR idobser = :idnon	");
	$req->bindValue(':idok', $idok);
	$req->bindValue(':idnon', $idnon);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
function mod_fiche($idok,$idnon)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE obs.fiche SET idobser = :idok WHERE idobser = :idnon ");
	$req->bindValue(':idok', $idok);
	$req->bindValue(':idnon', $idnon);
	$req->execute();
	$req->closeCursor();
}	
function mod_obs($idok,$idnon)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE obs.obs SET iddet = :idok WHERE iddet = :idnon ");
	$req->bindValue(':idok', $idok);
	$req->bindValue(':idnon', $idnon);
	$req->execute();
	$req->closeCursor();
}	
function mod_plusobser($idok,$idnon)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE obs.plusobser SET idobser = :idok WHERE idobser = :idnon ");
	$req->bindValue(':idok', $idok);
	$req->bindValue(':idnon', $idnon);
	$req->execute();
	$req->closeCursor();
}	
function modif($idm,$type,$modif,$datem,$idok)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO site.modif (typeid, numid, typemodif, modif, datemodif, idmembre)
						VALUES(:typeid, :id, :type, :modif, :datem, :idm) ");
	$req->bindValue(':id', $idok);
	$req->bindValue(':typeid', 'Idobser');
	$req->bindValue(':type', $type);
	$req->bindValue(':modif', $modif);
	$req->bindValue(':datem', $datem);
	$req->bindValue(':idm', $idm);
	$req->execute();
	$req->closeCursor();
}

if(isset($_POST['idok']) && isset($_POST['idnon']))
{
	if(!empty($_POST['idok']) && !empty($_POST['idnon']))
	{
		$idok = $_POST['idok'];
		$idnon = $_POST['idnon'];
		
		$verif = verif($idok,$idnon);
		if($verif == 2)
		{
			mod_fiche($idok,$idnon);
			mod_obs($idok,$idnon);
			mod_plusobser($idok,$idnon);
			
			$type = 'Fusion';
			$modif = 'Fusion idobser '.$idnon.' avec idobser '.$idok;
			$datem = date("Y-m-d H:i:s");
			modif($_SESSION['idmembre'],$type,$modif,$datem,$idok);
			
			$retour['statut'] = 'Oui';	
		}
		else
		{
			$retour['statut'] = 'Non';
		}		
	}
	else
	{
		$retour['statut'] = 'Non';
	}
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);