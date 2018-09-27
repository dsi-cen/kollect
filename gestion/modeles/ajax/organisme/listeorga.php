<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function liste()
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query("SELECT organisme.idorg, organisme, descri, COUNT(idfiche) AS nb FROM referentiel.organisme
						LEFT JOIN obs.fiche USING(idorg)
						GROUP BY organisme.idorg, organisme, descri ");
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);	
}

$liste = liste();

$nbobser = $liste[0];

foreach($liste[1] as $n)
{
	$mod = '<i class="fa fa-pencil curseurlien text-warning ml-3" title="Modifier/corriger" onclick="modifier('.$n['idorg'].')"></i>';
	
	if($n['nb'] > 0)
	{
		$sup = '<i class="fa fa-trash curseurlien text-danger ml-3"></i>';		
	}
	else
	{
		$sup = '<i class="fa fa-trash curseurlien ml-3" title="Supprimer cet organisme" onclick="sup(id='.$n['idorg'].')"></i>';
	}
	$data[] = [$mod.$sup,$n['idorg'],$n['organisme'],$n['descri'],$n['nb']];	
}

$retour['nb'] = $nbobser;
$retour['liste'] = $data;
header('Content-Type: application/json');
echo json_encode($retour);