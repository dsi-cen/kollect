<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();
function cherche_membre($idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(idmembre) FROM site.prefmembre WHERE idmembre = :idm ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idm);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
function insere_prefmembre($idm,$latin,$sel,$flou,$contact,$couche,$typedon,$org)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("INSERT INTO site.prefmembre (idmembre,obser,latin,floutage,contact,couche,typedon,org)
						VALUES(:idm, :obser, :latin, :flou, :contact, :couche, :tdon, :org) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idm);
	$req->bindValue(':latin', $latin);
	$req->bindValue(':obser', $sel);
	$req->bindValue(':flou', $flou);
	$req->bindValue(':contact', $contact);
	$req->bindValue(':couche', $couche);
	$req->bindValue(':tdon', $typedon);
	$req->bindValue(':org', $org);
	if ($req->execute())
	{
		$ok = 'ok';
	}
	$req->closeCursor();
	return $ok;	
}
function modif_prefmembre($idm,$latin,$sel,$flou,$contact,$couche,$typedon,$org)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("UPDATE site.prefmembre SET latin = :latin, obser = :obser, floutage = :flou, contact = :contact, couche = :couche, typedon = :tdon, org = :org WHERE idmembre = :idm ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idm', $idm);
	$req->bindValue(':latin', $latin);
	$req->bindValue(':obser', $sel);
	$req->bindValue(':flou', $flou);
	$req->bindValue(':contact', $contact);
	$req->bindValue(':couche', $couche);
	$req->bindValue(':tdon', $typedon);
	$req->bindValue(':org', $org);
	if ($req->execute())
	{
		$ok = 'ok';
	}
	$req->closeCursor();
	return $ok;	
}
if(isset($_POST['idm']))
{
	$idm = $_POST['idm'];
	$latin = $_POST['latin'];
	$sel = $_POST['sel'];
	$flou = $_POST['flou'];
	$contact = $_POST['contact'];
	$couche = $_POST['couche'];
	$typedon = $_POST['typedon'];
	$org = $_POST['org'];
	
	$membre = cherche_membre($idm);
	if($membre == 1)
	{
		$ok = modif_prefmembre($idm,$latin,$sel,$flou,$contact,$couche,$typedon,$org);
		if ($ok == 'ok')
		{
			$_SESSION['latin'] = $latin;
			$_SESSION['obser'] = $sel;
			$_SESSION['flou'] = $flou;
			$_SESSION['couche'] = $couche;
			$_SESSION['typedon'] = $typedon;
			$_SESSION['idorg'] = $org;
			$retour['statut'] = 'Oui';
		}
	}
	else
	{
		$ok = insere_prefmembre($idm,$latin,$sel,$flou,$contact,$couche,$typedon,$org);
		if ($ok == 'ok')
		{
			$_SESSION['latin'] = $latin;
			$_SESSION['obser'] = $sel;
			$_SESSION['flou'] = $flou;
			$_SESSION['couche'] = $couche;
			$_SESSION['typedon'] = $typedon;
			$_SESSION['idorg'] = $org;
			$retour['statut'] = 'Oui';
		}
	}	
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);	