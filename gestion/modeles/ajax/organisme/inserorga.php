<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function insere_orga($orga,$descri)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO referentiel.organisme (organisme, descri) VALUES (:orga, :descri) ");
	$req->bindValue(':orga', $orga);
	$req->bindValue(':descri', $descri);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
if(isset($_POST['orga']) AND isset($_POST['descri']))
{
	$orga = $_POST['orga'];
	$descri = $_POST['descri'];
	
	$vali = insere_orga($orga,$descri);
	
	$retour['statut'] = ($vali == 'oui') ? 'Ok' : 'Erreur ! Problème lors insertion observateur';
}
else 
{ $retour['statut'] = 'Tous les champs ne sont pas parvenus'; }
echo json_encode($retour);