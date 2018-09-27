<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	

function liste()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("WITH sel AS (
							SELECT liste.cdnom, nom, observatoire FROM referentiel.liste
							WHERE rang = 'COM'
							ORDER BY cdnom
						)
						SELECT sel.cdnom, sel.nom, sel.observatoire, array_to_string(array(SELECT DISTINCT liste.nom FROM referentiel.similaire INNER JOIN referentiel.liste ON liste.cdnom = similaire.cdnom WHERE similaire.com = sel.cdnom), ' et ') AS sp FROM sel ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}

$liste = liste();

if($liste != false)
{
	$l = '<table class="table table-sm">';
	$l .= '<thead><tr><th>cdnom</th><th>Complexe</th><th>Observatoire</th><th>Espèces</th></tr></thead>';
	$l .= '<tbody>';
	foreach($liste as $n)
	{
		$l .= '<tr><td>'.$n['cdnom'].'</td><td>'.$n['nom'].'</td><td>'.$n['observatoire'].'</td><td>'.$n['sp'].'</td></tr>';	
	}
	$l .= '</tbody></table>';
}
else
{
	$l = 'Aucun complexe de créer'; 
}
$retour['liste'] = $l;
$retour['statut'] = 'Oui';

echo json_encode($retour);