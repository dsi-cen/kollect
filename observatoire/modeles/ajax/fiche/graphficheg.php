<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';
function recherchestade($idval,$nomvar,$genre)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT stade.stade FROM obs.obs
						INNER JOIN obs.ligneobs USING(idobs)
						INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						WHERE genre = :genre AND idval = :idval ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idval', $idval);
	$req->bindValue(':genre', $genre);
	$req->execute();
	$stade = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $stade;	
}
function phenologie($cdnom,$idval,$nomvar,$genre)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(ligneobs.idobs) AS nb, iddecade, decade.decade FROM referentiel.decade
						INNER JOIN obs.fiche ON fiche.decade = decade.decade
						INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
						INNER JOIN obs.ligneobs ON ligneobs.idobs = obs.idobs
						INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						WHERE (genre = :genre OR obs.cdref = :cdnom) AND idval = :idval
						GROUP BY iddecade, decade.decade
						UNION 
						SELECT 0, iddecade, decade.decade FROM referentiel.decade
						WHERE iddecade NOT IN (SELECT DISTINCT iddecade FROM referentiel.decade
						INNER JOIN obs.fiche ON fiche.decade = decade.decade
						INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
						INNER JOIN obs.ligneobs ON ligneobs.idobs = obs.idobs
						INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						WHERE (genre = :genre OR obs.cdref = :cdnom) AND idval = :idval)
						ORDER BY iddecade") or die(print_r($bdd->errorInfo()));		
	$req->bindValue(':genre', $genre);
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':idval', $idval);
	$req->execute();
	$tabdec = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $tabdec;					
}

if(isset($_POST['cdnom'])) 
{
	$cdnom = $_POST['cdnom'];
	$nomvar = $_POST['nomvar'];
	$genre = $_POST['nom'];
			
	$date = date('d-m-Y');
	list($j,$m,$a) = explode("-",$date);
	switch ($m)
	{
		case 1:$DMois = "Ja";break;
		case 2:$DMois = "Fe";break;
		case 3:$DMois = "Ma";break;
		case 4:$DMois = "Av";break;
		case 5:$DMois = "M";break;
		case 6:$DMois = "Ju";break;
		case 7:$DMois = "Jl";break;
		case 8:$DMois = "A";break;
		case 9:$DMois = "S";break;
		case 10:$DMois = "O";break;
		case 11:$DMois = "N";break;
		case 12:$DMois = "D";break;
	}
	if ($j >= 1 && $j <= 10) {$Djrs = "1";}
	elseif ($j >= 11 && $j <= 20) {$Djrs = "2";}
	elseif ($j >= 21 && $j <= 31) {$Djrs = "3";}
	$decade = $DMois . $Djrs;
	
	$idval = 2; //stade adulte
	$stade = recherchestade($idval,$nomvar,$genre);
	if($stade['stade'] != '')
	{
		$adulte = $stade['stade'];
		$tabdec = phenologie($cdnom,$idval,$nomvar,$genre);
		foreach($tabdec as $n)
		{
			if($n['decade'] == $decade)
			{
				$retour['decade'] = $n['iddecade']-1.5;
			}
			$tab2[] = $n['nb'];
		}		
	}
	else
	{
		$adulte = 'Adulte';$tab2 = null;
	}
	$idval = 6; //stade larve
	$stade = recherchestade($idval,$nomvar,$genre);
	if($stade['stade'] != '')
	{
		$larve = $stade['stade'];
		$tabdec = phenologie($cdnom,$idval,$nomvar,$genre);
		foreach($tabdec as $n)
		{
			if($n['decade'] == $decade)
			{
				$retour['decade'] = $n['iddecade']-1.5;
			}
			$tab6[] = $n['nb'];
		}		
	}
	else
	{
		$larve = 'Larve';$tab6 = null;
	}
	$idval = 13; //stade Nymphe
	$stade = recherchestade($idval,$nomvar,$genre);
	if($stade['stade'] != '')
	{
		$nymphe = $stade['stade'];
		$tabdec = phenologie($cdnom,$idval,$nomvar,$genre);
		foreach($tabdec as $n)
		{
			if($n['decade'] == $decade)
			{
				$retour['decade'] = $n['iddecade']-1.5;
			}
			$tab13[] = $n['nb'];
		}		
	}
	else
	{
		$nymphe = 'Nymphe';$tab13 = null;
	}
	$idval = 1; //stade Nymphe
	$stade = recherchestade($idval,$nomvar,$genre);
	if($stade['stade'] != '')
	{
		$ind = $stade['stade'];
		$tabdec = phenologie($cdnom,$idval,$nomvar,$genre);
		foreach($tabdec as $n)
		{
			if($n['decade'] == $decade)
			{
				$retour['decade'] = $n['iddecade']-1.5;
			}
			$tab1[] = $n['nb'];
		}		
	}
	else
	{
		$ind = 'Indéterminé';$tab1 = null;
	}
	$retour['serie2'] = $adulte;
	$retour['serie6'] = $larve;
	$retour['serie13'] = $nymphe;
	$retour['serie1'] = $ind;
	$retour['tab2'] = $tab2;
	$retour['tab6'] = $tab6;
	$retour['tab13'] = $tab13;
	$retour['tab1'] = $tab1;
	$retour['statut'] = 'Oui';		
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour, JSON_NUMERIC_CHECK);
?>