<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	

function liste()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT idetude, etude, libelle, masquer FROM referentiel.etude ORDER BY idetude ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function insere_etude($id,$etu,$lib,$voir)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO referentiel.etude (idetude, etude, libelle, masquer) VALUES (:id, :etu, :lib, :voir) ");
	$req->bindValue(':id', $id);
	$req->bindValue(':etu', $etu);
	$req->bindValue(':lib', $lib);
	$req->bindValue(':voir', $voir);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function mod_protocole($id,$etu,$lib,$voir)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE referentiel.etude SET etude =:etu, libelle =:lib, masquer =:voir WHERE idetude = :id ");
	$req->bindValue(':id', $id);
	$req->bindValue(':etu', $etu);
	$req->bindValue(':lib', $lib);
	$req->bindValue(':voir', $voir);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;
}

if(isset($_POST['id']))
{
	$id = $_POST['id'];
	$etu = $_POST['etu'];
	$lib = $_POST['lib'];
	$voir = $_POST['voir'];
	$typeval = $_POST['typeval'];
	
	if($typeval == 'ajout')
	{
		$vali = insere_etude($id,$etu,$lib,$voir);
		if($vali == 'oui')
		{
			$etude = liste();
			$retour['statut'] = 'Oui';
			$retour['mes'] = '<div class="alert alert-success" role="alert">L\'étude '.$etu.' a été ajoutée</div>';
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! lors de l\'insertion.</div>';
		}		
	}
	elseif($typeval == 'mod')
	{
		$vali = mod_protocole($id,$etu,$lib,$voir);
		if($vali == 'oui')
		{
			$etude = liste();
			$retour['statut'] = 'Oui';
			$retour['mes'] = '<div class="alert alert-success" role="alert">L\'étude '.$etu.' a été modifiée</div>';
			
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! lors de la modification.</div>';
		}	
	}
	if(isset($etude))
	{
		$l = null;
		foreach($etude as $n)
		{
			$tabid[] = $n['idetude'];
			$l .= '<tr class="mod" id="'.$n['idetude'].'" data-etude="'.$n['etude'].'" data-lib="'.$n['libelle'].'" data-org="'.$n['organisme'].'" data-voir="'.$n['masquer'].'">';
			$l .= '<td><i class="fa fa-pencil curseurlien text-warning" title="modifier l\'étude"></i></td><td>'.$n['idetude'].'</td><td>'.$n['etude'].'</td><td>'.$n['libelle'].'</td><td>'.$n['organisme'].'</td><td>'.$n['masquer'].'</td>';
			$l .= '</tr>';			
		}
		$retour['maxid'] = max($tabid) + 1;
		$retour['liste'] = $l;
	}
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucune étude de choisit.</div>';
}
echo json_encode($retour);