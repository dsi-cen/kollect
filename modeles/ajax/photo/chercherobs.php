<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function cherche($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT liste.nom, nomvern, commune.codecom, commune, to_char(date1, 'DD/MM/YYYY') AS datefr, idm, plusobser, idfiche, liste.cdnom, observa FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN referentiel.commune ON commune.codecom = fiche.codecom
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
						WHERE idobs = :idobs ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function stade($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idstade, stade.stade FROM obs.ligneobs 
						INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
						WHERE idobs = :idobs ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function chercheobservateur($idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idobser FROM referentiel.observateur WHERE idm = :idm ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idm);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function cherche_observateur($idfiche,$idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT nom, prenom, idm  FROM obs.plusobser
						INNER JOIN referentiel.observateur ON observateur.idobser = plusobser.idobser
						WHERE idfiche = :idfiche AND idm = :idm ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
	$req->bindValue(':idm', $idm, PDO::PARAM_INT);
	$req->execute();
	$obsplus = $req->rowCount();
	$req->closeCursor();
	return $obsplus;
}
if(!empty($_POST['idobs']))
{
	$idobs = $_POST['idobs'];
	$idm = $_POST['idm'];
	
	$obser = (isset($_SESSION['virtobs'])) ? $idm : chercheobservateur($idm);
	$photo = cherche($idobs);
	$stade = stade($idobs);
	if($photo['idm'] == $idm || isset($_SESSION['virtuel']))
	{
		$ok = 'oui';
	}
	else
	{
		if($photo['plusobser'] == 'oui')
		{
			$verif = cherche_observateur($photo['idfiche'],$idm);
			$ok = ($verif == 1) ? 'oui' : 'non';
		}
		else
		{
			$ok = 'non';
		}
	}
	if($ok == 'oui')
	{
		$retour['er'] = 'Non';
		$retour['data'] = $photo;
		$retour['idobser'] = $obser;
	}
	else
	{
		$retour['er'] = 'Oui';
	}
	if(count($stade) > 0)
	{
		foreach($stade as $n)
		{
			$stadeval[] = $n['stade'];
			$stadeid[] = $n['idstade'];			
		}
		$stade = array_combine($stadeval, $stadeid);
		$retour['stade'] = $stade;
	}
	else
	{
		$retour['stade'] = '';
	}
	
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';	
}
echo json_encode($retour);	