<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
	
function ajour()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->exec("INSERT INTO vali.grille
						SELECT cdref, COUNT(idobs) AS nb, array_agg(DISTINCT codel93) AS codel93, array_agg(DISTINCT decade) AS decade, array_agg(DISTINCT idobser) AS obser FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
						INNER JOIN obs.ligneobs USING(idobs)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE (validation = 1 OR validation = 2) AND date1 = date2 AND idetatbio != 3 AND vali != 0
						GROUP BY cdref ");
	return $req;
}
function vidergrille()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->exec("DELETE FROM vali.grille ");
	return $req;
}

$vider = vidergrille();
$ajour = ajour();

$retour['vider'] = $vider;
$retour['ajour'] = $ajour;
$retour['statut'] = 'Oui';	

echo json_encode($retour);

