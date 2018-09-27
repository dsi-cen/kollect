<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

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
function insere_observateurs($nom,$prenoma,$observateur)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO referentiel.observateur (observateur, nom, prenom) VALUES (:observateur, :nom, :prenom) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':nom', $nom);
	$req->bindValue(':prenom', $prenoma);
	$req->bindValue(':observateur', $observateur);
	if ($req->execute())
	{
		$vali = $bdd->lastInsertId('referentiel.observateur_idobser_seq');
	} 
	$req->closeCursor();
	return $vali;	
}
if(!empty($_POST['nom']) AND !empty($_POST['prenom']))
{
	$nom = mb_strtoupper($_POST['nom'], 'UTF-8');
	$prenom = $_POST['prenom'];
	$prenoma = mb_convert_case($prenom, MB_CASE_TITLE, "UTF-8");
	
	$observateur = ''.$nom.' '.$prenoma.'';
	$nbresultats = rechercher_observateur($observateur);
	if ($nbresultats == 0)
	{
		$vali = insere_observateurs($nom,$prenoma,$observateur);
		if ($vali != '')
		{
			$retour['statut'] = array("Ok"=>'Ok',"idobser"=>$vali);
		}
		else
		{
			$retour['statut'] = 'Un probleme est survenu';
		}
	}
	else
	{
		$retour['statut'] = 'Il existe déjà un observateur : '.$nom.' '.$prenoma.'';
	}	
}
else 
{ 
	$retour['statut'] = 'Tous les champs ne sont pas parvenus ou bien non renseignés';
}
echo json_encode($retour);