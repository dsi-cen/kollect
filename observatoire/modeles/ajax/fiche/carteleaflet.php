<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function datemin($cdnom,$rang,$nomvar,$droit)
{
	$strQuery = "SELECT EXTRACT(YEAR FROM MIN(date1)) AS min, EXTRACT(YEAR FROM MAX(date1)) AS max FROM obs.fiche
				INNER JOIN obs.obs USING(idfiche)";
	$strQuery .= ($rang == 'oui') ? " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref" : ""; 
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND (date1 = date2) AND (validation = 1 OR validation = 2)" : " WHERE cdref = :cdref AND (date1 = date2) AND (validation = 1 OR validation = 2)";
	$strQuery .= ($droit == 'non') ? " AND floutage = 0 " : "";
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery) or die(print_r($bdd->errorInfo()));	
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}	
function rechercheobs($cdnom,$rang,$nomvar,$droit)
{
	$strQuery = "SELECT idfiche, to_char(date1, 'DD/MM/YYYY') AS datefr, idobser, idobs, plusobser, EXTRACT(YEAR FROM(date1)) as annee, lat, lng, observateur.nom, prenom FROM obs.fiche
				INNER JOIN obs.obs USING(idfiche)
				INNER JOIN obs.coordonnee USING(idcoord)
				INNER JOIN referentiel.observateur USING(idobser)";
	$strQuery .= ($rang == 'oui') ? " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref" : ""; 
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND (date1 = date2) AND (validation = 1 OR validation = 2) AND statutobs != 'No'" : " WHERE cdref = :cdref AND (date1 = date2) AND (validation = 1 OR validation = 2) AND statutobs != 'No'";
	$strQuery .= ($droit == 'non') ? " AND floutage = 0 " : "";
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery) or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function sensible($cdnom,$rang,$nomvar)	
{
	$strQuery = "WITH SEL AS (SELECT idfiche FROM obs.fiche 
					INNER JOIN obs.obs USING(idfiche)";
	$strQuery .= ($rang == 'oui') ? " INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref" : ""; 
	$strQuery .= ($rang == 'oui') ? " WHERE (obs.cdref = :cdref OR cdsup = :cdref) AND (date1 = date2)" : " WHERE cdref = :cdref AND (date1 = date2)";
	$strQuery .= " )";
	$strQuery .= " SELECT DISTINCT sel.idfiche FROM sel INNER JOIN obs.obs ON obs.idfiche = sel.idfiche LEFT JOIN referentiel.sensible ON sensible.cdnom = obs.cdref WHERE sensible >= 1";
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery) or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}

if(isset($_POST['cdnom']) && !empty($_POST['cdnom'])) 
{
	$cdnom = $_POST['cdnom'];
	$rang = $_POST['rang'];
	$nomvar = $_POST['nomvar'];
	
	$droit = (isset($_SESSION['droits']) && $_SESSION['droits'] >= 2) ? 'oui' : 'non';
	
	$minmax = datemin($cdnom,$rang,$nomvar,$droit);
	$liste = rechercheobs($cdnom,$rang,$nomvar,$droit);
	$nbobs = count($liste);
	if($nbobs > 0)
	{
		if($droit == 'non')
		{
			$sensible = sensible($cdnom,$rang,$nomvar);
			if($sensible != false)
			{
				foreach($sensible as $n)
				{
					$tabsensible[] = $n['idfiche'];
				}
				$tabsensible = array_flip($tabsensible);
			}
		}		
		foreach($liste as $n)
		{
			if(!isset($tabsensible[$n['idfiche']]))
			{
				$tab = array('dateobs'=>$n['datefr'], 'idobs'=>$n['idobs'], 'annee'=>$n['annee'], 'obser'=>$n['prenom'].' '.$n['nom'], 'geojson_point' => null);
				$tab['geojson_point'] = array('coordinates' => array(floatval($n['lng']), floatval($n['lat'])), 'type' => 'Point');
				$resultats[] = $tab;
			}							
		}
		$retour['nbobs'] = $nbobs;	
		$retour['point'] = (isset($resultats)) ? $resultats : null;		
		$json_emprise = file_get_contents('../../../../emprise/emprise.json');
		$rjson = json_decode($json_emprise, true);
		$retour['color'] = (isset($rjson['stylecontour2']['color'])) ? $rjson['stylecontour2']['color'] : null;
		$retour['weight'] = (isset($rjson['stylecontour2']['weight'])) ? $rjson['stylecontour2']['weight'] : null;
		$retour['lat'] = $rjson['lat'];
		$retour['lng'] = $rjson['lng'];	
		$retour['min'] = $minmax['min'];
		$retour['max'] = $minmax['max'];	
	}	
	
	$retour['dr'] = $droit;
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour, JSON_NUMERIC_CHECK);
?>