<?php 
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function sup_niv6($id)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("DELETE FROM referentiel.eunis WHERE cdhab = :id OR cdhabsup = :id ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function sup_niv5($id)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("WITH sel AS (
							SELECT cdhab FROM referentiel.eunis WHERE cdhab = :id OR cdhabsup = :id
						)
						DELETE FROM referentiel.eunis
						USING sel
						WHERE sel.cdhab = eunis.cdhab OR sel.cdhab = eunis.cdhabsup") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function sup_niv4($id)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("WITH sel AS (
							SELECT cdhab FROM referentiel.eunis WHERE cdhab = :id OR cdhabsup = :id
						),
						sel2 AS (
							SELECT eunis.cdhab FROM sel 
							INNER JOIN referentiel.eunis ON eunis.cdhabsup = sel.cdhab OR eunis.cdhab = sel.cdhab
						)
						DELETE FROM referentiel.eunis
						USING sel2
						WHERE sel2.cdhab = eunis.cdhab OR sel2.cdhab = eunis.cdhabsup") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function sup_niv3($id)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("WITH sel AS (
							SELECT cdhab FROM referentiel.eunis WHERE cdhab = :id OR cdhabsup = :id
						),
						sel2 AS (
							SELECT eunis.cdhab FROM sel 
							INNER JOIN referentiel.eunis ON eunis.cdhabsup = sel.cdhab OR eunis.cdhab = sel.cdhab
						),
						sel3 AS (
							SELECT eunis.cdhab from sel2 
							INNER JOIN referentiel.eunis ON eunis.cdhabsup = sel2.cdhab OR eunis.cdhab = sel2.cdhab
						)
						DELETE FROM referentiel.eunis
						USING sel3
						WHERE sel3.cdhab = eunis.cdhab OR sel3.cdhab = eunis.cdhabsup") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function sup_niv2($id)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("WITH sel AS (
							SELECT cdhab FROM referentiel.eunis WHERE cdhab = :id OR cdhabsup = :id
						),
						sel2 AS (
							SELECT eunis.cdhab FROM sel 
							INNER JOIN referentiel.eunis ON eunis.cdhabsup = sel.cdhab OR eunis.cdhab = sel.cdhab
						),
						sel3 AS (
							SELECT eunis.cdhab from sel2 
							INNER JOIN referentiel.eunis ON eunis.cdhabsup = sel2.cdhab OR eunis.cdhab = sel2.cdhab
						),
						sel4 AS (
							SELECT eunis.cdhab FROM sel3 
							INNER JOIN referentiel.eunis ON eunis.cdhabsup = sel3.cdhab OR eunis.cdhab = sel3.cdhab
						)
						DELETE FROM referentiel.eunis
						USING sel4
						WHERE sel4.cdhab = eunis.cdhab OR sel4.cdhab = eunis.cdhabsup") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}
function sup_niv1($id)
{
	$bdd = PDO2::getInstance();
	$req = $bdd->prepare("WITH sel AS (
							SELECT cdhab FROM referentiel.eunis WHERE cdhab = :id OR cdhabsup = :id
						),
						sel2 AS (
							SELECT eunis.cdhab FROM sel 
							INNER JOIN referentiel.eunis ON eunis.cdhabsup = sel.cdhab OR eunis.cdhab = sel.cdhab
						),
						sel3 AS (
							SELECT eunis.cdhab from sel2 
							INNER JOIN referentiel.eunis ON eunis.cdhabsup = sel2.cdhab OR eunis.cdhab = sel2.cdhab
						),
						sel4 AS (
							SELECT eunis.cdhab FROM sel3 
							INNER JOIN referentiel.eunis ON eunis.cdhabsup = sel3.cdhab OR eunis.cdhab = sel3.cdhab
						),
						sel5 AS (
							SELECT eunis.cdhab FROM sel4 
							INNER JOIN referentiel.eunis ON eunis.cdhabsup = sel4.cdhab OR eunis.cdhab = sel4.cdhab
						)
						DELETE FROM referentiel.eunis
						USING sel5
						WHERE sel5.cdhab = eunis.cdhab OR sel5.cdhab = eunis.cdhabsup") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;	
}

if(isset($_POST['id']) && isset($_POST['niv']))
{	
	$id = $_POST['id'];
	$niv = $_POST['niv'];

	if($niv == 'n6')
	{
		$vali = sup_niv6($id);
	}
	elseif($niv == 'n5')
	{
		$vali = sup_niv5($id);
	}
	elseif($niv == 'n4')
	{
		$vali = sup_niv4($id);
	}
	elseif($niv == 'n3')
	{
		$vali = sup_niv3($id);
	}
	elseif($niv == 'n2')
	{
		$vali = sup_niv2($id);
	}
	elseif($niv == 'n')
	{
		$vali = sup_niv1($id);
	}
	
	if($vali == 'oui')
	{
		$retour['statut'] = 'Oui';	
	}
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! Problème lors de la suppression de '.$id.' (rang = '.$niv.').</div>';
	}	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Erreur ! Tous les paramètres ne sont pas définit.</div>';
}
echo json_encode($retour);	