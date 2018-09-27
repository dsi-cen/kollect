<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function chercheid($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idobsdeb, idobsfin FROM import.histo WHERE id = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function supligne($iddeb,$idfin)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM obs.ligneobs WHERE idobs >= :iddeb AND idobs <= :idfin ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':iddeb', $iddeb);
	$req->bindValue(':idfin', $idfin);
	$req->execute();
	$req->closeCursor();	
}
function supidentif($iddeb,$idfin)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM obs.identif WHERE idobs >= :iddeb AND idobs <= :idfin ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':iddeb', $iddeb);
	$req->bindValue(':idfin', $idfin);
	$req->execute();
	$req->closeCursor();	
}
function supobs($iddeb,$idfin)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM obs.obs WHERE idobs >= :iddeb AND idobs <= :idfin ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':iddeb', $iddeb);
	$req->bindValue(':idfin', $idfin);
	$req->execute();
	$req->closeCursor();	
}
function supfiche($iddeb,$idfin)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("WITH sel AS (
							SELECT idfiche FROM obs.fiche 
							INNER JOIN obs.obs USING(idfiche)
							WHERE idobs >= :iddeb AND idobs <= idfin
						),
						sel1 AS (
							SELECT idfiche FROM sel 
							WHERE not exists (select idfiche from obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							WHERE (idobs < :iddeb OR idobs > :idfin) AND sel.idfiche = fiche.idfiche)
							
						)
						DELETE FROM obs.fiche
						USING sel1
						WHERE sel1.idfiche = fiche.idfiche ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':iddeb', $iddeb);
	$req->bindValue(':idfin', $idfin);
	$req->execute();
	$req->closeCursor();	
}
function supplusobser($iddeb,$idfin)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("WITH sel AS (
							SELECT idfiche FROM obs.plusobser 
							INNER JOIN obs.obs USING(idfiche)
							WHERE idobs >= :iddeb AND idobs <= idfin
						),
						sel1 AS (
							SELECT idfiche FROM sel 
							WHERE not exists (select idfiche from obs.plusobser
							INNER JOIN obs.obs USING(idfiche)
							WHERE (idobs < :iddeb OR idobs > :idfin) AND sel.idfiche = plusobser.idfiche)
							
						)
						DELETE FROM obs.plusobser
						USING sel1
						WHERE sel1.idfiche = plusobser.idfiche ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':iddeb', $iddeb);
	$req->bindValue(':idfin', $idfin);
	$req->execute();
	$req->closeCursor();	
}
function suphisto($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM import.histo WHERE id = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id);
	$req->execute();
	$req->closeCursor();	
}

if(isset($_POST['id'])) 
{
	$id = $_POST['id'];
	$imp = chercheid($id);
	
	$iddeb = $imp['idobsdeb'];
	$idfin = $imp['idobsfin'];
	
	supligne($iddeb,$idfin);
	supidentif($iddeb,$idfin);
	supfiche($iddeb,$idfin);
	supplusobser($iddeb,$idfin);
	supobs($iddeb,$idfin);
	suphisto($id);
	
	$retour['statut'] = 'Oui';	
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);