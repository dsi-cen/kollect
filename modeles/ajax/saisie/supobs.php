<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
//A faire suppression commentaire et validation
function recupinfo($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idfiche, cdhab, idcol, idphoto, idmort FROM obs.obs 
						LEFT JOIN obs.obshab ON obshab.idobs = obs.idobs
						LEFT JOIN obs.obscoll ON obscoll.idobs = obs.idobs
						LEFT JOIN site.photo ON photo.idobs = obs.idobs
						LEFT JOIN obs.obsmort ON obsmort.idobs = obs.idobs
						WHERE obs.idobs = :idobs ");
	$req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function chercheligne($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(idligne) AS nb FROM obs.ligneobs WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function cherchenbor($idligne)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT nbmin, nbmax, nb, ndiff, male, femelle FROM obs.ligneobs
						INNER JOIN obs.obs USING(idobs)
						WHERE idligne = :idligne ");
	$req->bindValue(':idligne', $idligne, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function chercheobs($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(idobs) AS nb FROM obs.obs WHERE idfiche = :idfiche ");
	$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function modif_obs($idobs,$nbmod)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE obs.obs SET nb = :nb WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':nb', $nbmod);
	$req->execute();
	$req->closeCursor();
}
function supprime_ligne($idligne)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM obs.ligneobs WHERE idligne = :idligne");
	$req->bindValue(':idligne', $idligne);
	$req->execute();
	$req->closeCursor();
}
function supprime_lignet($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM obs.ligneobs WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$req->closeCursor();
} 
function supprime_obs($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM obs.obs WHERE idobs = :idobs");
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$req->closeCursor();
} 
function supprime_fiche($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM obs.fiche WHERE idfiche = :idfiche");
	$req->bindValue(':idfiche', $idfiche);
	$req->execute();
	$req->closeCursor();
}
function supprime_habitat($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM obs.obshab WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$req->closeCursor();
}
function supprime_coll($idcol)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM obs.obscoll WHERE idcol = :idcol ");
	$req->bindValue(':idcol', $idcol);
	$req->execute();
	$req->closeCursor();
}
function supprime_photo($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM site.photo WHERE idobs = :idobs ");
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$req->closeCursor();
}
function supprime_mort($idmort)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("DELETE FROM obs.obsmort WHERE idmort = :idmort ");
	$req->bindValue(':idmort', $idmort);
	$req->execute();
	$req->closeCursor();
}

if(isset($_POST['idobs']) && isset($_POST['idligne'])) 
{
	$idobs = $_POST['idobs'];
	$idligne = $_POST['idligne'];
	if($idligne == 'tous')	
	{
		$info = recupinfo($idobs);
		$nbobs = chercheobs($info['idfiche']);
		supprime_lignet($idobs);		
		if(!empty($info['cdhab'])) { supprime_habitat($idobs); }
		if(!empty($info['idcol'])) { supprime_coll($info['idcol']); }
		if(!empty($info['idphoto'])) { supprime_photo($idobs); }
		if(!empty($info['idmort'])) { supprime_mort($info['idmort']); }
		supprime_obs($idobs); // RLE : en dernier car sinon problème avec clés
		if($nbobs == 1) { supprime_fiche($info['idfiche']); }
	}	
	else
	{
		$nbligne = chercheligne($idobs);
		if($nbligne == 1)
		{
			$info = recupinfo($idobs);
			supprime_ligne($idligne);
			$nbobs = chercheobs($info['idfiche']);
			supprime_obs($idobs);		
			if($nbobs == 1)
			{
				supprime_fiche($info['idfiche']);
			}
			if(!empty($info['cdhab'])) { supprime_habitat($idobs); }
			if(!empty($info['idcol'])) { supprime_coll($info['idcol']); }
			if(!empty($info['idphoto'])) { supprime_photo($idobs); }
			if(!empty($info['idmort'])) { supprime_mort($info['idmort']); }
			$retour['nbligne'] = 1;
			$retour['nbobs'] = $nbobs;
		}
		elseif($nbligne > 1)
		{
			$nbor = cherchenbor($idligne);
			if(!empty($nbor['nbmin']) && !empty($nbor['nbmax']))
			{
				if($nbor['nbmin'] == $nbor['nbmax'])
				{
					$nbmod = $nbor['nb'] - $nbor['nbmin'];
				}
				else
				{
					$nbmod = $nbor['nb'] - (($nbor['nbmax'] - ($nbor['nbmin'] - 1)) / 2);
				}
			}
			else
			{
				$nbmod = $nbor['nb'] - ($nbor['ndiff'] + $nbor['male'] + $nbor['femelle']);
			}		
			modif_obs($idobs,$nbmod);
			supprime_ligne($idligne);
			$retour['nbligne'] = 'n';
		}
	}
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Problème';
}
echo json_encode($retour);
?>