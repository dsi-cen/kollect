<?php
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function liste($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT liste.nom, liste.nomvern, famille.famille, to_char(MIN(date1), 'DD/MM/YYYY') AS min, to_char(MAX(date1), 'DD/MM/YYYY') AS max, COUNT(obs.cdref) AS nb, COUNT(DISTINCT idcoord) AS nbs, ir FROM $nomvar.liste
						INNER JOIN obs.obs ON obs.cdref = liste.cdnom
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN referentiel.liste AS l ON l.cdnom = liste.cdref
						INNER JOIN $nomvar.famille ON liste.famille = famille.cdnom
						WHERE liste.rang = 'ES' OR liste.rang = 'SSES'
						GROUP BY liste.nom, liste.nomvern, famille.famille, ir
						ORDER BY nom ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['sel']))
{
	$nomvar = htmlspecialchars($_POST['sel']);
	
	$liste = liste($nomvar);
	
	if($liste != false)
	{
		foreach($liste as $n)
		{
			$data[] = [$n['nom'],$n['nomvern'],$n['famille'],$n['min'],$n['max'],$n['nb'],$n['nbs'],$n['ir']];
		}
		$retour['data'] = $data;			
	}
	else
	{
		$retour['vide'] = '<p>Aucune espèce</p>';		
	}
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);