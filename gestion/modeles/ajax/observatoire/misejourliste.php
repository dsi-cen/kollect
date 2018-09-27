<?php

function supligne($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("DELETE FROM referentiel.liste 
						WHERE NOT EXISTS (SELECT * FROM $nomvar.liste AS l WHERE l.cdnom = liste.cdnom AND locale = 'oui') AND observatoire = :sel ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':sel', $nomvar);
	$vali = ($req->execute()) ? 'oui' : 'non';
	$req->closeCursor();
	return $vali;
}
function insere_listeref($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query ("INSERT INTO referentiel.liste (cdnom,nom,auteur,nomvern,observatoire,rang,vali) 
						SELECT cdnom, nom, auteur, nomvern, '$nomvar' AS observatoire, rang, 0 AS vali FROM $nomvar.liste AS l
						WHERE NOT EXISTS (SELECT * FROM referentiel.liste WHERE liste.cdnom = l.cdnom)
						AND cdnom = cdref AND locale = 'oui' ") or die(print_r($bdd->errorInfo()));
	$liste = $req->rowCount();
	$req->closeCursor();
	return $liste;		
}
function verifliste($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query ("SELECT $nomvar.liste.cdnom FROM $nomvar.liste
						INNER JOIN referentiel.liste ON referentiel.liste.cdnom = $nomvar.liste.cdnom") or die(print_r($bdd->errorInfo()));
	$liste = $req->rowCount();
	$req->closeCursor();
	return $liste;		
}
if (isset($_POST['sel']) && ($_POST['sel'] != 'NR'))
{
	include '../../../../global/configbase.php';
	include '../../../../lib/pdo2.php';	
	
	$nomvar = ($_POST['sel']);
	
	$vali = supligne($nomvar);
	if($vali == 'oui')
	{
		$liste = insere_listeref($nomvar);
		$retour['statut'] = 'Oui';		
	}	
	else
	{
		$retour['statut'] = 'Non';
		$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Erreur ! Problème lors de la reconstruction de la table liste dans referentiel.</p></div>';
	}
		
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert"><p>Aucun observatoire de choisit.</p></div>';
}
echo json_encode($retour);