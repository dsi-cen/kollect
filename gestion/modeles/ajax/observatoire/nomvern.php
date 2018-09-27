<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function modif_listeobserva($nomvar,$id,$vern)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE $nomvar.liste SET nomvern =:vern WHERE cdnom = :id ");
	$req->bindValue(':id', $id);
	$req->bindValue(':vern', $vern);
	$vali = ($req->execute()) ? 'oui' : '';
	$req->closeCursor();
	return $vali;
}
function modif_liste($id,$vern)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE referentiel.liste SET nomvern =:vern WHERE cdnom = :id ");
	$req->bindValue(':id', $id);
	$req->bindValue(':vern', $vern);
	$vali = ($req->execute()) ? 'oui' : '';
	$req->closeCursor();
	return $vali;
}

if (isset($_POST['sel']) && isset($_POST['id']) && isset($_POST['vern']))
{	
	$nomvar = $_POST['sel'];	
	$id = $_POST['id'];
	$vern = $_POST['vern'];

	$vali = modif_listeobserva($nomvar,$id,$vern);
		
	if ($vali == 'oui')
	{
		$vali = modif_liste($id,$vern);
		if ($vali == 'oui')
		{
			$retour['statut'] = 'Oui';
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! Problème lors de la modification du nom vernaculaire '.$vern.' dans table liste du schéma referentiel.</p></div>';
		}
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! Problème lors de la modification du nom vernaculaire '.$vern.' dans table famille du schéma '.$nomvar.'.</p></div>';
	}		
	echo json_encode($retour);	
}