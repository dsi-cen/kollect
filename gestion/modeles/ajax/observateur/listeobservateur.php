<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
session_start();

/*function liste_obser()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT observateur.idobser, nom, prenom, idm, COUNT(idobs) AS nb FROM referentiel.observateur
						LEFT JOIN obs.fiche ON fiche.idobser = observateur.idobser
						LEFT JOIN obs.obs ON obs.idfiche = fiche.idfiche
						GROUP BY observateur.idobser, nom, prenom, idm
						ORDER BY nom ");
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);	
}
select idobser, sum(nb) AS nb from (
							SELECT COUNT(idobs) AS nb, idobser FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							GROUP BY idobser
						union
							SELECT COUNT(idobs) AS nb, idobser FROM obs.obs
							INNER JOIN obs.plusobser USING(idfiche)
							GROUP BY idobser
							) x group by idobser
						)
						SELECT observateur.idobser, nom, prenom, idm, sel.nb, aff FROM referentiel.observateur
						LEFT JOIN sel ON sel.idobser = observateur.idobser 
						ORDER BY nom

select idobser, nom, prenom, idm, sum(nb) AS nb, aff from (
							SELECT COUNT(idobs) AS nb, idobser FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							GROUP BY idobser
						union
							SELECT COUNT(idobs) AS nb, idobser FROM obs.obs
							INNER JOIN obs.plusobser USING(idfiche)
							GROUP BY idobser
							) x 
left JOIN referentiel.observateur using(idobser)
							group by idobser, nom, prenom, idm, aff

*/
/*function liste_obser()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("WITH sel AS (
							SELECT COUNT(idobs) AS nb, idobser FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							GROUP BY idobser
						), sel1 AS (
							SELECT COUNT(idobs) AS nb2, idobser FROM obs.obs
							INNER JOIN obs.plusobser USING(idfiche)
							GROUP BY idobser
						)
						SELECT observateur.idobser, nom, prenom, idm, sel.nb, sel1.nb2, aff FROM referentiel.observateur
						LEFT JOIN sel ON sel.idobser = observateur.idobser 
						LEFT JOIN sel1 ON sel1.idobser = observateur.idobser
						ORDER BY nom ");
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);	
}*/
function liste_obser()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("WITH sel AS (
						SELECT idobser, SUM(nb) AS nb FROM (
							SELECT COUNT(idobs) AS nb, idobser FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							GROUP BY idobser
							UNION
							SELECT COUNT(idobs) AS nb, idobser FROM obs.obs
							INNER JOIN obs.plusobser USING(idfiche)
							GROUP BY idobser
							) x GROUP BY idobser
						)
						SELECT observateur.idobser, nom, prenom, idm, sel.nb, aff FROM referentiel.observateur
						LEFT JOIN sel ON sel.idobser = observateur.idobser 
						ORDER BY nom ");
	$req->execute();
	$nbresultats = $req->rowCount();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return array($nbresultats, $resultat);	
}

$liste = liste_obser();

$nbobser = $liste[0];

foreach($liste[1] as $n)
{
	//$nbobs = $n['nb'] + $n['nb2'];
	$nbobs = $n['nb'];
	$oeil = '<i class="fa fa-eye curseurlien text-primary" title="Se connecter en tant que" onclick="virtuel('.$n['idobser'].')"></i>';
	$mod = '<i class="fa fa-pencil curseurlien text-warning ml-3" title="Modifier/corriger" onclick="modifier('.$n['idobser'].')"></i>';
	if(isset($_SESSION['virtuel']))
	{
		$data[] = [$oeil,$n['idobser'],$n['nom'],$n['prenom'],$nbobs,$n['idm'],$n['aff']];
	}
	else
	{
		
		if($nbobs > 0)
		{
			$sup = '<i class="fa fa-trash curseurlien ml-3" title="Supprimer cet observateur" onclick="supobsern(id='.$n['idobser'].')"></i>';
		}
		else
		{
			$sup = '<i class="fa fa-trash curseurlien text-danger ml-3" title="Supprimer cet observateur" onclick="supobser(id='.$n['idobser'].')"></i>';
		}
		$data[] = [$oeil.$mod.$sup,$n['idobser'],$n['nom'],$n['prenom'],$nbobs,$n['idm'],$n['aff']];		
	}
}
$retour['nb'] = $nbobser;
$retour['liste'] = $data;
header('Content-Type: application/json');
echo json_encode($retour);