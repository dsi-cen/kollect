<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	
function verif($id)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->prepare("SELECT cdprotect FROM statut.libelle WHERE cdprotect = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function insere_statut($id,$article,$lib,$ref,$url,$type,$annee)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("INSERT INTO statut.libelle (cdprotect, article, intitule, arrete, url, type, annee) VALUES (:id, :article, :lib, :ref, :url, :type, :annee) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->bindValue(':article', $article);
	$req->bindValue(':lib', $lib);
	$req->bindValue(':ref', $ref);
	$req->bindValue(':url', $url);
	$req->bindValue(':type', $type);
	$req->bindValue(':annee', $annee);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function mod_statut($id,$article,$lib,$ref,$url,$annee)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE statut.libelle SET article =:article, intitule =:lib, arrete =:ref, url =:url, annee =:annee WHERE cdprotect = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->bindValue(':article', $article);
	$req->bindValue(':lib', $lib);
	$req->bindValue(':ref', $ref);
	$req->bindValue(':url', $url);
	$req->bindValue(':annee', $annee);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;
}

if (isset($_POST['sel']))
{
	$type = $_POST['sel'];
	$id = $_POST['id'];
	$article = $_POST['article'];
	$lib = $_POST['lib'];
	$ref = $_POST['ref'];
	$url = $_POST['url'];
	$annee = $_POST['annee'];
	$typeval = $_POST['typeval'];
	
	if($typeval == 'ajout')
	{
		$verif = verif($id);
		if(!empty($verif['cdprotect']))
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Cet id ('.$id.') existe déjà.</div>';
		}
		else
		{
			$vali = insere_statut($id,$article,$lib,$ref,$url,$type,$annee);
			if($vali == 'oui')
			{
				$retour['statut'] = 'Oui';
				$retour['mes'] = '<div class="alert alert-success" role="alert">Le statut a été ajouté</div>';
			}
			else
			{
				$retour['statut'] = 'Non';
				$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! lors de l\'insertion.</div>';
			}		
		}
	}
	elseif($typeval == 'mod')
	{
		$vali = mod_statut($id,$article,$lib,$ref,$url,$annee);
		if($vali == 'oui')
		{
			$retour['statut'] = 'Oui';
			$retour['mes'] = '<div class="alert alert-success" role="alert">Le statut a été modifié</div>';
		}
		else
		{
			$retour['statut'] = 'Non';
			$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! lors de la modification.</div>';
		}	
	}
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun type de choisit.</div>';
}
echo json_encode($retour);