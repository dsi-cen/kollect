<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function recupid($idsite)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idsite, idcoord FROM obs.site WHERE idsite = :idsite ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idsite', $idsite);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function modif_fiche($n,$idsite,$idcoord)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE obs.fiche SET idsite = :idsite, idcoord = :idcoord WHERE idsite = :idsiteg ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idsite', $idsite);
	$req->bindValue(':idcoord', $idcoord);
	$req->bindValue(':idsiteg', $n, PDO::PARAM_INT);
	$req->execute();
	$req->closeCursor();
}
function verifsite($idsite)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT count(*) AS nb FROM obs.fiche WHERE idsite = :idsite ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idsite', $idsite);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
function sup_site($idsite)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM obs.site WHERE idsite = :idsite ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idsite', $idsite);
	$req->execute();
	$req->closeCursor();
} 
function verifcoord($idcoord)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT count(*) AS nb FROM obs.site WHERE idcoord = :idcoord ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idcoord', $idcoord);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
function sup_coord($idcoord)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM obs.coordonnee WHERE idcoord = :idcoord ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idcoord', $idcoord);
	$req->execute();
	$req->closeCursor();
} 

if(!empty($_POST['idgarde']) && !empty($_POST['idsup']))
{	
	$idgarde = $_POST['idgarde'];
	$idsup = $_POST['idsup'];
	
	$recupid = recupid($idgarde);
	$idsite = $recupid['idsite'];
	$idcoord = $recupid['idcoord'];
	
	if($idsite != '' && $idcoord != '')
	{
		$tab = explode(",", $idsup);
		foreach($tab as $n)
		{
			$supid = recupid($n);
			modif_fiche($n,$idsite,$idcoord);			
			$verifsite = verifsite($supid['idsite']);
			$retour['verifsite'] = $verifsite;
			if($verifsite == 0)
			{
				sup_site($supid['idsite']);
				$verifcoord = verifcoord($supid['idcoord']);
				if($verifcoord == 0)
				{
					sup_coord($supid['idcoord']);
				}
			}
		}
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
echo json_encode($retour);	
?>