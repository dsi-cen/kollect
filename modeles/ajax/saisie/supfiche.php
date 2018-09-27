<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function recupinfo($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT obs.idobs, cdhab, idcol, idphoto, idmort FROM obs.obs  
						INNER JOIN obs.fiche USING(idfiche)
						LEFT JOIN obs.obshab ON obshab.idobs = obs.idobs
						LEFT JOIN obs.obscoll ON obscoll.idobs = obs.idobs
						LEFT JOIN site.photo ON photo.idobs = obs.idobs
						LEFT JOIN obs.obsmort ON obsmort.idobs = obs.idobs
						WHERE fiche.idfiche = :idfiche ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function chercheligne($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idligne FROM obs.ligneobs
						INNER JOIN obs.obs USING(idobs)
						WHERE idfiche = :idfiche ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function supprime_ligne($idligne)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM obs.ligneobs WHERE idligne = :idligne ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idligne', $idligne);
	$req->execute();
	$req->closeCursor();
}
function supprime_obs($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM obs.obs WHERE idfiche = :idfiche ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idfiche', $idfiche);
	$req->execute();
	$req->closeCursor();
} 
function supprime_fiche($idfiche)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM obs.fiche WHERE idfiche = :idfiche") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idfiche', $idfiche);
	$req->execute();
	$req->closeCursor();
}
function supprime_habitat($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM obs.obshab WHERE idobs = :idobs ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$req->closeCursor();
}
function supprime_coll($idcol)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM obs.obscoll WHERE idcol = :idcol ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idcol', $idcol);
	$req->execute();
	$req->closeCursor();
}
function supprime_photo($idobs)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM site.photo WHERE idobs = :idobs ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs);
	$req->execute();
	$req->closeCursor();
}
function supprime_mort($idmort)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM obs.obsmort WHERE idmort = :idmort ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idmort', $idmort);
	$req->execute();
	$req->closeCursor();
}

if(isset($_POST['idfiche'])) 
{
	$idfiche = $_POST['idfiche'];
	
	$info = recupinfo($idfiche);
	foreach($info as $n)
	{
		if($n['idmort'] != '') { supprime_mort($n['idmort']); }
		if($n['cdhab'] != '') { supprime_habitat($n['idobs']); }
		if($n['idcol'] != '') { supprime_coll($n['idcol']); }
		if($n['idphoto'] != '') { supprime_photo($n['idobs']); }
	}
	
	$ligne = chercheligne($idfiche);
	foreach($ligne as $n)
	{
		supprime_ligne($n['idligne']);
	}
	supprime_obs($idfiche);
	supprime_fiche($idfiche);
		
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Problème';
}
echo json_encode($retour);
?>