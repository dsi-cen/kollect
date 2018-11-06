<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function vidertable()
{
	$bdd = PDO2::getInstance();		
	$bdd->exec("DELETE FROM import.impobser ");
}
function insere_impobservateurs($idor,$nom,$prenom,$idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO import.impobser (idobseror, nom, prenom, idobser) VALUES (:idor, :nom, :prenom, :idobser) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idor', $idor);
	$req->bindValue(':nom', $nom);
	$req->bindValue(':prenom', $prenom);
	$req->bindValue(':idobser', $idobser);
	$req->execute();
	$req->closeCursor();	
}
function listeobservateur()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT idobseror, nom, prenom FROM import.impobser WHERE idobser = 0 ") or die(print_r($bdd->errorInfo()));
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function insere_observateurs($nom,$prenoma,$observateur)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO referentiel.observateur (observateur, nom, prenom, idm) VALUES (:observateur, :nom, :prenom, :idm) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':nom', $nom);
	$req->bindValue(':prenom', $prenoma);
	$req->bindValue(':observateur', $observateur);
	$req->bindValue(':idm', null);
	$vali = ($req->execute()) ? $bdd->lastInsertId('referentiel.observateur_idobser_seq') : 0;
	$req->closeCursor();
	return $vali;	
}
function modimpobser($idobseror,$idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE import.impobser SET idobser = :id WHERE idobseror = :idor ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idor', $idobseror, PDO::PARAM_INT);
	$req->bindValue(':id', $idobser, PDO::PARAM_INT);
	$req->execute();
	$req->closeCursor();
}
if(isset($_POST['fichier'])) 
{
	$fichier = $_POST['fichier'];
	vidertable();
	
	if(($liste = fopen("../../../tmp/".$fichier, "r")) !== FALSE) 
	{
		$nbligne = 0;
		$i = 0;
		while(($data = fgetcsv($liste, 1000, ";")) !== FALSE) 
		{
			insere_impobservateurs($data[0],$data[1],$data[2],$data[3]);	
		}
	}
	
	$obser = listeobservateur();
	$nb = 0;
	foreach($obser as $n)
	{
		$idobseror = $n['idobseror'];
		$prenoma = mb_convert_case($n['prenom'], MB_CASE_TITLE, "UTF-8");
		$nom = mb_strtoupper($n['nom'], 'UTF-8');
		$observateur = $nom.' '.$prenoma;
		$idobser = insere_observateurs($nom,$prenoma,$observateur);
		if($idobser != 0)
		{
			modimpobser($idobseror,$idobser);
			$nb++;
		}		
	}
	$retour['statut'] = 'Oui';	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun fichier.</div>';
}
echo json_encode($retour);