<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function graphespece($anneeune)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT EXTRACT(YEAR FROM date1) AS annee, COUNT(DISTINCT cdref) AS nb FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE EXTRACT(YEAR FROM date1) >= :annee AND (rang = 'ES' OR rang = 'SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY annee ");
	$req->bindValue(':annee', $anneeune);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function newespece($anneeune)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT annee, COUNT(annee) AS nb FROM (
						SELECT DISTINCT cdref, MIN(EXTRACT(YEAR FROM date1)) AS annee FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE EXTRACT(YEAR FROM date1) >= :annee AND (rang = 'ES' OR rang = 'SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)				
						GROUP BY cdref ) AS a
						GROUP BY annee ");
	$req->bindValue(':annee', $anneeune);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function graphobserva($anneeune)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT EXTRACT(YEAR FROM date1) AS annee, COUNT(DISTINCT cdref) AS nb, observa FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE EXTRACT(YEAR FROM date1) >= :annee AND (rang = 'ES' OR rang = 'SSES') AND statutobs != 'No' AND (validation = 1 OR validation = 2)
						GROUP BY annee, observa ");
	$req->bindValue(':annee', $anneeune);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['choix'])) 
{
	$anneeune = $_POST['anneeune'];
	$choix = $_POST['choix'];
	
	$annéeactuelle = date('Y');
	for($i=$anneeune;$i <= $annéeactuelle;$i++) 
	{ 
		$annee[] = $i;	 
	}
	$retour['annee'] = $annee;
	if($choix == 'espece')
	{
		$espece = graphespece($anneeune);
		$newespece = newespece($anneeune);
		foreach($espece as $n)
		{
			$tabannee[] = $n['annee'];
		}
		foreach($newespece as $n)
		{
			$tabannee1[] = $n['annee'];
		}
		foreach($annee as $a)
		{
			if(in_array($a, $tabannee))
			{
				foreach($espece as $n)
				{
					if($n['annee'] == $a)
					{
						$sp[] = $n['nb'];
					}			
				}
			}
			else
			{
				$sp[] = 0;
			}
			if(in_array($a, $tabannee1))
			{
				foreach($newespece as $n)
				{
					if($n['annee'] == $a)
					{
						$newsp[] = $n['nb'];
					}			
				}
			}
			else
			{
				$newsp[] = 0;
			}
		}		
		$retour['sp'] = $sp;
		$retour['newsp'] = $newsp;		
	}
	elseif($choix == 'observa')
	{
		$json = file_get_contents('../../../json/site.json');
		$rjson = json_decode($json, true);
		$especeobserva = graphobserva($anneeune);
		foreach($especeobserva as $n)	
		{
			$tabobserva[] = $n['observa'];
			$tabanneeobs[$n['observa']][] = $n['annee'];
		}
		foreach($rjson['observatoire'] as $o)
		{
			$observa = $o['nomvar'];
			$nomaff = $o['nom'];
			$color = $o['couleur'];
			$data[$observa] = array('name' => $nomaff, 'color' => $color, 'data' => array());			
			if(in_array($o['nomvar'], $tabobserva))
			{
				foreach($annee as $a)
				{
					if(in_array($a, $tabanneeobs[$o['nomvar']]))
					{
						foreach($especeobserva as $e)	
						{
							if($a == $e['annee'] && $e['observa'] == $observa)	
							{
								$data[$observa]['data'][] = $e['nb'];
							}						
						}
					}
					else
					{
						$data[$observa]['data'][] = 0;
					}
				}						
			}			
		}		
		$retour['observa'] = array_values($data);
	}
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour, JSON_NUMERIC_CHECK);
?>