<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function graphespece($anneeune,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT EXTRACT(YEAR FROM date1) AS annee, COUNT(DISTINCT cdref) AS nb FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						WHERE EXTRACT(YEAR FROM date1) >= :annee AND observa = :nomvar
						GROUP BY annee ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':annee', $anneeune);
	$req->bindValue(':nomvar', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function newespece($anneeune,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT annee, COUNT(annee) AS nb FROM (
						SELECT DISTINCT cdref, MIN(EXTRACT(YEAR FROM date1)) AS annee FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						WHERE EXTRACT(YEAR FROM date1) >= :annee AND observa = :nomvar			
						GROUP BY cdref ) AS a
						GROUP BY annee ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':annee', $anneeune);
	$req->bindValue(':nomvar', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function graphobserva($anneeune,$nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT EXTRACT(YEAR FROM date1) AS annee, COUNT(DISTINCT obs.cdref) AS nb, cat FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						INNER JOIN $nomvar.liste ON liste.cdref = obs.cdref
						INNER JOIN $nomvar.categorie ON categorie.famille = liste.famille
						WHERE EXTRACT(YEAR FROM date1) >= :annee AND observa = :nomvar
						GROUP BY annee, cat ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':annee', $anneeune);
	$req->bindValue(':nomvar', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['choix'])) 
{
	$anneeune = $_POST['anneeune'];
	$choix = $_POST['choix'];
	$nomvar = $_POST['nomvar'];
	
	$annéeactuelle = date('Y');
	for($i=$anneeune;$i <= $annéeactuelle;$i++) 
	{ 
		$annee[] = $i;	 
	}
	$retour['annee'] = $annee;
	if($choix == 'espece')
	{
		$espece = graphespece($anneeune,$nomvar);
		$newespece = newespece($anneeune,$nomvar);
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
	elseif($choix == 'cat')
	{
		$json = file_get_contents('../../../../json/'.$nomvar.'.json');
		$rjson = json_decode($json, true);
		$espececat = graphobserva($anneeune,$nomvar);
		foreach($espececat as $n)	
		{
			$tabcat[] = $n['cat'];
			$tabanneeobs[$n['cat']][] = $n['annee'];
		}
		foreach($rjson['categorie'] as $o)
		{
			$cat = $o['id'];
			$nomaff = $o['cat'];
			$data[$cat] = array('name' => $nomaff, 'data' => array());			
			if(in_array($o['id'], $tabcat))
			{
				foreach($annee as $a)
				{
					if(in_array($a, $tabanneeobs[$o['id']]))
					{
						foreach($espececat as $e)	
						{
							if($a == $e['annee'] && $e['cat'] == $cat)	
							{
								$data[$cat]['data'][] = $e['nb'];
							}						
						}
					}
					else
					{
						$data[$cat]['data'][] = 0;
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