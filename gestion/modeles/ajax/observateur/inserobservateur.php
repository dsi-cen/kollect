<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function rechercher_observateur($observateur)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT observateur FROM referentiel.observateur WHERE observateur = :observateur ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':observateur', $observateur);
	$req->execute();
	$nbresultats = $req->rowCount();
	$req->closeCursor();
	return $nbresultats;
}
function insere_observateurs($nom,$prenoma,$observateur,$idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO referentiel.observateur (observateur, nom, prenom, idm) VALUES (:observateur, :nom, :prenom, :idm) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':nom', $nom);
	$req->bindValue(':prenom', $prenoma);
	$req->bindValue(':observateur', $observateur);
	$req->bindValue(':idm', $idm);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
if(isset($_POST['nom']) AND isset($_POST['prenom']))
{
	$nom = mb_strtoupper($_POST['nom'], 'UTF-8');
	$prenom = $_POST['prenom'];
	$idm =  ($_POST['idm'] == '') ? null : $_POST['idm'];
	$doublon = $_POST['doublon'];
	$prenoma = mb_convert_case($prenom, MB_CASE_TITLE, "UTF-8");
	
	$observateur = ''.$nom.' '.$prenoma.'';
	$nbresultats = ($doublon == 'non') ? rechercher_observateur($observateur) : 0;
	if ($nbresultats == 0)
	{
		$vali = insere_observateurs($nom,$prenoma,$observateur,$idm);
		$retour['statut'] = ($vali == 'oui') ? 'Ok' : 'Erreur ! Problème lors insertion observateur';
	}
	else
	{
		$retour['statut'] = 'Ok';
		$retour['doublon'] = 'oui';		
	}
}
else 
{$retour['statut'] = 'Tous les champs ne sont pas parvenus';}
echo json_encode($retour);