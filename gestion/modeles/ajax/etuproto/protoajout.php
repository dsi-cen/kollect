<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	

function liste()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT idprotocole, protocole, libelle, url FROM referentiel.protocole ORDER BY idprotocole ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function insere_protocole($id,$proto,$lib,$url)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO referentiel.protocole (idprotocole, protocole, libelle, url) VALUES (:id, :proto, :lib, :url) ");
	$req->bindValue(':id', $id);
	$req->bindValue(':proto', $proto);
	$req->bindValue(':lib', $lib);
	$req->bindValue(':url', $url);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function mod_protocole($id,$proto,$lib,$url)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE referentiel.protocole SET protocole =:proto, libelle =:lib, url =:url WHERE idprotocole = :id ");
	$req->bindValue(':id', $id);
	$req->bindValue(':proto', $proto);
	$req->bindValue(':lib', $lib);
	$req->bindValue(':url', $url);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;
}

if(isset($_POST['id']))
{
	$id = $_POST['id'];
	$proto = $_POST['proto'];
	$lib = $_POST['lib'];
	$url = $_POST['url'];
	$typeval = $_POST['typeval'];
	
	if($typeval == 'ajout')
	{
		$vali = insere_protocole($id,$proto,$lib,$url);
		if($vali == 'oui')
		{
			$protocole = liste();
			$retour['statut'] = 'Oui';
			$retour['mes'] = '<div class="alert alert-success" role="alert">Le protocole '.$proto.' a été ajouté</div>';
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! lors de l\'insertion.</div>';
		}		
	}
	elseif($typeval == 'mod')
	{
		$vali = mod_protocole($id,$proto,$lib,$url);
		if($vali == 'oui')
		{
			$protocole = liste();
			$retour['statut'] = 'Oui';
			$retour['mes'] = '<div class="alert alert-success" role="alert">Le protocole '.$proto.' a été modifié</div>';
			
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! lors de la modification.</div>';
		}	
	}
	if(isset($protocole))
	{
		$l = null;
		foreach($protocole as $n)
		{
			$tabid[] = $n['idprotocole'];
			$l .= '<tr class="mod" id="'.$n['idprotocole'].'" data-proto="'.$n['protocole'].'" data-lib="'.$n['libelle'].'" data-url="'.$n['url'].'">';
			$l .= '<td><i class="fa fa-pencil curseurlien text-warning" title="modifier le protocole"></i></td><td>'.$n['idprotocole'].'</td><td>'.$n['protocole'].'</td><td>'.$n['libelle'].'</td><td>'.$n['url'].'</td>';
			$l .= '</tr>';			
		}
		$retour['maxid'] = max($tabid) + 1;
		$retour['liste'] = $l;
	}
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun protocole de choisit.</div>';
}
echo json_encode($retour);