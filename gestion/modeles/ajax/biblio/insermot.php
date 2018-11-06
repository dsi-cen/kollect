<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function insere_mot($mot)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO biblio.motcle (mot) VALUES (:mot) ");
	$req->bindValue(':mot', $mot);
	$vali = ($req->execute()) ? $bdd->lastInsertId('biblio.motcle_idmc_seq') : 'non';
	$req->closeCursor();
	return $vali;	
}
if(isset($_POST['mot']))
{
	if(!empty($_POST['mot']))
	{	
		$mot = mb_convert_case($_POST['mot'], MB_CASE_TITLE, "UTF-8");
		$vali = insere_mot($mot);
		$retour['statut'] = ($vali != 'non') ? 'Oui' : 'Erreur ! Problème lors insertion du mot';
		$retour['idmc'] = $vali;
		$retour['mot'] = $mot;		
	}
	else
	{ $retour['statut'] = 'Aucun mot !'; }
}
else 
{ $retour['statut'] = 'Tous les champs ne sont pas parvenus'; }
echo json_encode($retour);