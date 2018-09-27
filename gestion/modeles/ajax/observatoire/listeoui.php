<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	

function liste($nomvar)
{
	$bdd = PDO2::getInstance();		
	$req = $bdd->query("SELECT DISTINCT nom, nomvern, rang FROM $nomvar.liste
						WHERE NOT EXISTS (SELECT * FROM obs.obs WHERE obs.cdref = liste.cdnom) AND locale = 'oui' AND cdref = cdnom AND (rang = 'ES' OR rang = 'SSES')
						ORDER BY nom ") or die(print_r($bdd->errorInfo()));
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}

if (isset($_POST['sel']))
{
	$nomvar = ($_POST['sel']);
	$liste = liste($nomvar);
	
	$l = '<ul>';
	foreach($liste as $n)
	{
		$l .= '<li>'.$n['nom'].' - '.$n['nomvern'].' - ('.$n['rang'].')</li>';		
	}
	$l .= '</ul>';
		
	$retour['liste'] = $l;
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';	
}
echo json_encode($retour);