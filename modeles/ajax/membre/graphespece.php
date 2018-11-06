<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function graphobs($idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT EXTRACT(YEAR FROM date1) AS annee, COUNT(DISTINCT cdref) AS nb FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						LEFT JOIN obs.plusobser USING(idfiche)
						WHERE (fiche.idobser = :idobser OR plusobser.idobser = :idobser) AND (rang = 'ES' OR rang ='SSES')
						GROUP BY annee  ");
	$req->bindValue(':idobser', $idobser, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function graphnew($idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("WITH sel AS (SELECT MIN(date1) AS prem, cdref FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
							LEFT JOIN obs.plusobser USING(idfiche)
							WHERE (fiche.idobser = :idobser OR plusobser.idobser = :idobser) AND (rang = 'ES' OR rang ='SSES')
							GROUP BY cdref
						)
						SELECT COUNT(sel.cdref) AS nb, EXTRACT(YEAR FROM sel.prem) AS annee FROM sel
						GROUP BY annee  ");
	$req->bindValue(':idobser', $idobser, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}

if(isset($_POST['idobser'])) 
{
	$idobser = htmlspecialchars($_POST['idobser']);	
	$annéeactuelle = date('Y');
	
	$graphobs = graphobs($idobser);
	$new = graphnew($idobser);
	
	foreach($new as $n)
	{
		$tabnew[$n['annee']] = $n['nb'];
	}
	$retour['tabnew'] = $tabnew;
	foreach($graphobs as $n)
	{
		$tabannee[] = $n['annee'];
	}
	$anmin = min($tabannee);
	for($i=$anmin; $i <= $annéeactuelle; $i++) 
	{ 
		$annee[] = $i;	 
	} 
	$nbligne = (count($annee) > 25) ? 2 : 1;
	
	$tabannee = array_flip($tabannee);
	$nbcumul = 0;
	foreach($annee as $a)
	{
		if(isset($tabannee[$a]))
		{
			foreach($graphobs as $n)
			{
				if($n['annee'] == $a)
				{
					$nb[] = $n['nb'];
					if(isset($tabnew[$n['annee']]))
					{
						$obscumul[] = $nbcumul += $tabnew[$n['annee']];
					}
					else
					{
						$obscumul[] = $nbcumul += 0;
					}
				}			
			}
		}
		else
		{
			$nb[] = 0;
			$obscumul[] = $nbcumul += 0;
		}		
	}
	
	$retour['annee'] = $annee;
	$retour['nb'] = $nb;
	$retour['cumul'] = $obscumul;		
		
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour, JSON_NUMERIC_CHECK);
?>