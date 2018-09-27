<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function rechercher_auteur($nom,$prenom)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT nom, prenom FROM biblio.auteurs WHERE nom ILIKE :nom AND prenom ILIKE :prenom ");
	$req->bindValue(':nom', $nom);
	$req->bindValue(':prenom', $prenom);
	$req->execute();
	$nbresultats = $req->rowCount();
	$req->closeCursor();
	return $nbresultats;
}
function insere_auteur($nom,$prenom,$prenomab)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO biblio.auteurs (nom, prenom, prenomab) VALUES (:nom, :prenom, :prenomab) ");
	$req->bindValue(':nom', $nom);
	$req->bindValue(':prenom', $prenom);
	$req->bindValue(':prenomab', $prenomab);
	$vali = ($req->execute()) ? $bdd->lastInsertId('biblio.auteurs_idauteur_seq') : 'non';
	$req->closeCursor();
	return $vali;	
}
if(isset($_POST['nom']) AND isset($_POST['prenom']))
{
	if(!empty($_POST['nom']))
	{	
		$nom = mb_convert_case($_POST['nom'], MB_CASE_TITLE, "UTF-8");
		$prenom = mb_convert_case($_POST['prenom'], MB_CASE_TITLE, "UTF-8");
		$prenomab = $_POST['ab'];
		$doublon = $_POST['dble'];
		
		$nbresultats = ($doublon == 'non') ? rechercher_auteur($nom,$prenom) : 0;
		if($nbresultats == 0)
		{
			$vali = insere_auteur($nom,$prenom,$prenomab);
			$retour['statut'] = ($vali != 'non') ? 'Oui' : 'Erreur ! Problème lors insertion observateur';
			$retour['idauteur'] = $vali;
			$retour['prenom'] = $prenom;
			$retour['nom'] = $nom;
		}
		else
		{
			$retour['statut'] = 'Oui';
			$retour['doublon'] = 'oui';		
		}		
	}
	else
	{ $retour['statut'] = 'Le champ Nom n\'est pas remplis'; }
}
else 
{ $retour['statut'] = 'Tous les champs ne sont pas parvenus'; }
echo json_encode($retour);