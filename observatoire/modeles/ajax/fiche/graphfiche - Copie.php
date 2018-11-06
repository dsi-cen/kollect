<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';
function recherchestade($cdnom,$idval,$nomvar,$rang)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT DISTINCT stade.stade FROM obs.obs
							INNER JOIN obs.ligneobs USING(idobs)
							INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							WHERE (obs.cdref = :cdnom OR cdsup = :cdnom) AND idval = :idval ") or die(print_r($bdd->errorInfo()));
	}	
	elseif($rang == 'non')	
	{	
		$req = $bdd->prepare("SELECT DISTINCT stade.stade FROM obs.obs
							INNER JOIN obs.ligneobs USING(idobs)
							INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
							WHERE cdref = :cdnom AND idval = :idval ") or die(print_r($bdd->errorInfo()));
	}
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':idval', $idval);
	$req->execute();
	$stade = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	if(count($stade) == 1)
	{
		$lib = $stade[0]['stade'];
	}
	elseif(count($stade) > 1) 
	{
		foreach($stade as $n)
		{
			$tab1[] = $n['stade'];
		}
		$lib = implode(", ", $tab1);
	}
	else
	{
		$lib = null;
	}
	return $lib;
}
function recherchestade1($idval,$idstade)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT stade FROM referentiel.stade WHERE idval = :idval AND idstade IN ($idstade) ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idval', $idval);
	$req->execute();
	$stade = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	if(count($stade) == 1)
	{
		$lib = $stade[0]['stade'];
	}
	elseif(count($stade) > 1) 
	{
		foreach($stade as $n)
		{
			$tab1[] = $n['stade'];
		}
		$lib = implode(", ", $tab1);
	}
	else
	{
		$lib = null;
	}
	return $lib;	
}
/*
WITH sel AS (
								SELECT idobs FROM obs.obs 
								INNER JOIN obs.ligneobs using(idobs) 
								WHERE cdref = 53668 And idetatbio = 2 AND (validation = 1 OR validation = 2)
							)
							SELECT COUNT(sel.idobs) AS nb, iddecade, decade.decade FROM sel
							INNER JOIN obs.obs ON obs.idobs = sel.idobs
							INNER JOIN obs.fiche ON fiche.idfiche = obs.idfiche
							INNER JOIN referentiel.decade USING(decade)
							GROUP BY iddecade, decade.decade
							UNION 
							SELECT 0, iddecade, decade.decade FROM referentiel.decade
							WHERE iddecade NOT IN (SELECT DISTINCT iddecade FROM sel
							INNER JOIN obs.obs ON obs.idobs = sel.idobs
							INNER JOIN obs.fiche ON fiche.idfiche = obs.idfiche
							INNER JOIN referentiel.decade USING(decade))
							ORDER BY iddecade
*/
function phenologie($cdnom,$idval,$nomvar,$rang)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT COUNT(ligneobs.idobs) AS nb, iddecade, decade.decade FROM referentiel.decade
							INNER JOIN obs.fiche ON fiche.decade = decade.decade
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							INNER JOIN obs.ligneobs ON ligneobs.idobs = obs.idobs
							INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							WHERE (obs.cdref = :cdnom OR cdsup = :cdnom) AND idval = :idval And idetatbio = 2 AND (validation = 1 OR validation = 2)
							GROUP BY iddecade, decade.decade
							UNION 
							SELECT 0, iddecade, decade.decade FROM referentiel.decade
							WHERE iddecade NOT IN (SELECT DISTINCT iddecade FROM referentiel.decade
							INNER JOIN obs.fiche ON fiche.decade = decade.decade
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							INNER JOIN obs.ligneobs ON ligneobs.idobs = obs.idobs
							INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							WHERE (obs.cdref = :cdnom OR cdsup = :cdnom) AND idval = :idval And idetatbio = 2 AND (validation = 1 OR validation = 2))
							ORDER BY iddecade") or die(print_r($bdd->errorInfo()));		
	}
	elseif($rang == 'non')
	{
		$req = $bdd->prepare("SELECT COUNT(ligneobs.idobs) AS nb, iddecade, decade.decade FROM referentiel.decade
							INNER JOIN obs.fiche ON fiche.decade = decade.decade
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							INNER JOIN obs.ligneobs ON ligneobs.idobs = obs.idobs
							INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
							WHERE cdref = :cdnom AND idval = :idval And idetatbio = 2 AND (validation = 1 OR validation = 2)
							GROUP BY iddecade, decade.decade
							UNION 
							SELECT 0, iddecade, decade.decade FROM referentiel.decade
							WHERE iddecade NOT IN (SELECT DISTINCT iddecade FROM referentiel.decade
							INNER JOIN obs.fiche ON fiche.decade = decade.decade
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							INNER JOIN obs.ligneobs ON ligneobs.idobs = obs.idobs
							INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
							WHERE cdref = :cdnom AND idval = :idval And idetatbio = 2 AND (validation = 1 OR validation = 2))
							ORDER BY iddecade") or die(print_r($bdd->errorInfo()));
	}
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
	$rang = htmlspecialchars($_POST['rang']);
	$nomvar = $_POST['nomvar'];
	$json = file_get_contents('../../../../json/'.$nomvar.'.json');
	$rjson = json_decode($json, true);
	foreach($rjson['saisie']['stade'] as $n)
	{
		$tabidstade[] = $n;
	}
	$idstade = implode(", ", $tabidstade);
	
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
	$stade = recherchestade($cdnom,$idval,$nomvar,$rang);
	if($stade != '')
	{
		$adulte = $stade;
		$tabdec = phenologie($cdnom,$idval,$nomvar,$rang);
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
		$adulte = 'non';$tab2 = null;
	}
	$idval = 3; //stade jeune
	$stade = recherchestade($cdnom,$idval,$nomvar,$rang);
	if($stade != '')
	{
		$juv = $stade;
		$tabdec = phenologie($cdnom,$idval,$nomvar,$rang);
		foreach($tabdec as $n)
		{
			if($n['decade'] == $decade)
			{
				$retour['decade'] = $n['iddecade']-1.5;
			}
			$tab3[] = $n['nb'];
		}		
	}
	else
	{
		$juv = 'non'; $tab3 = null;
	}
	$idval = 6; //stade larve
	$stade = recherchestade($cdnom,$idval,$nomvar,$rang);
	if($stade != '')
	{
		$larve = $stade;
		$tabdec = phenologie($cdnom,$idval,$nomvar,$rang);
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
		$larve = 'non';$tab6 = null;
	}
	$idval = 13; //stade Nymphe
	$stade = recherchestade($cdnom,$idval,$nomvar,$rang);
	if($stade != '')
	{
		$nymphe = $stade;
		$tabdec = phenologie($cdnom,$idval,$nomvar,$rang);
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
		$nymphe = 'non';$tab13 = null;
	}
	$idval = 1; //stade indetermine
	$stade = recherchestade($cdnom,$idval,$nomvar,$rang);
	if($stade != '')
	{
		$ind = $stade;
		$tabdec = phenologie($cdnom,$idval,$nomvar,$rang);
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
		$ind = 'non';$tab1 = null;
	}
	$retour['serie2'] = $adulte;
	$retour['serie3'] = $juv;
	$retour['serie6'] = $larve;
	$retour['serie13'] = $nymphe;
	$retour['serie1'] = $ind;
	$retour['tab2'] = $tab2;
	$retour['tab3'] = $tab3;
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