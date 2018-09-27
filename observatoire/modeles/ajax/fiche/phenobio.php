<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';
function recherchestade($cdnom,$idval,$nomvar,$rang)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
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
	$stade = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $stade;	
}
function recherchestade1($idval,$idstade)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
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
function phenologie($cdnom,$idval,$nomvar,$rang,$idbio)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT COUNT(ligneobs.idobs) AS nb, iddecade, decade.decade FROM referentiel.decade
							INNER JOIN obs.fiche ON fiche.decade = decade.decade
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							INNER JOIN obs.ligneobs ON ligneobs.idobs = obs.idobs
							INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							INNER JOIN obs.biogeo ON biogeo.idcoord = fiche.idcoord
							WHERE (obs.cdref = :cdnom OR cdsup = :cdnom) AND idval = :idval AND idbiogeo = :idbio AND idetatbio = 2 AND (validation = 1 OR validation = 2)
							GROUP BY iddecade, decade.decade
							UNION 
							SELECT 0, iddecade, decade.decade FROM referentiel.decade
							WHERE iddecade NOT IN (SELECT DISTINCT iddecade FROM referentiel.decade
							INNER JOIN obs.fiche ON fiche.decade = decade.decade
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							INNER JOIN obs.ligneobs ON ligneobs.idobs = obs.idobs
							INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							INNER JOIN obs.biogeo ON biogeo.idcoord = fiche.idcoord
							WHERE (obs.cdref = :cdnom OR cdsup = :cdnom) AND idval = :idval AND idbiogeo = :idbio AND idetatbio = 2 AND (validation = 1 OR validation = 2))
							ORDER BY iddecade") or die(print_r($bdd->errorInfo()));		
	}
	if($rang == 'non')
	{
		$req = $bdd->prepare("SELECT COUNT(ligneobs.idobs) AS nb, iddecade, decade.decade FROM referentiel.decade
							INNER JOIN obs.fiche ON fiche.decade = decade.decade
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							INNER JOIN obs.ligneobs ON ligneobs.idobs = obs.idobs
							INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
							INNER JOIN obs.biogeo ON biogeo.idcoord = fiche.idcoord
							WHERE cdref = :cdnom AND idval = :idval AND idbiogeo = :idbio AND idetatbio = 2 AND (validation = 1 OR validation = 2)
							GROUP BY iddecade, decade.decade
							UNION 
							SELECT 0, iddecade, decade.decade FROM referentiel.decade
							WHERE iddecade NOT IN (SELECT DISTINCT iddecade FROM referentiel.decade
							INNER JOIN obs.fiche ON fiche.decade = decade.decade
							INNER JOIN obs.obs ON obs.idfiche = fiche.idfiche
							INNER JOIN obs.ligneobs ON ligneobs.idobs = obs.idobs
							INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
							INNER JOIN obs.biogeo ON biogeo.idcoord = fiche.idcoord
							WHERE cdref = :cdnom AND idval = :idval AND idbiogeo = :idbio AND idetatbio = 2 AND (validation = 1 OR validation = 2))
							ORDER BY iddecade") or die(print_r($bdd->errorInfo()));
	}
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':idval', $idval);
	$req->bindValue(':idbio', $idbio);
	$req->execute();
	$tabdec = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $tabdec;					
}

if(isset($_POST['cdnom']) && isset($_POST['id'])) 
{
	$cdnom = $_POST['cdnom'];
	$rang = $_POST['rang'];
	$nomvar = $_POST['nomvar'];
	$idbio = $_POST['id'];
	
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
	if($stade['stade'] != '')
	{
		$adulte = $stade['stade'];
		$tabdec = phenologie($cdnom,$idval,$nomvar,$rang,$idbio);
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
		$adulte = recherchestade1($idval,$idstade);$tab2 = null;
	}
	$idval = 6; //stade larve
	$stade = recherchestade($cdnom,$idval,$nomvar,$rang);
	if($stade['stade'] != '')
	{
		$larve = $stade['stade'];
		$tabdec = phenologie($cdnom,$idval,$nomvar,$rang,$idbio);
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
		$larve = recherchestade1($idval,$idstade);$tab6 = null;
	}
	$idval = 13; //stade Nymphe
	$stade = recherchestade($cdnom,$idval,$nomvar,$rang);
	if($stade['stade'] != '')
	{
		$nymphe = $stade['stade'];
		$tabdec = phenologie($cdnom,$idval,$nomvar,$rang,$idbio);
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
		$nymphe = recherchestade1($idval,$idstade);$tab13 = null;
	}
	$idval = 1; //stade indetermine
	$stade = recherchestade($cdnom,$idval,$nomvar,$rang);
	if($stade['stade'] != '')
	{
		$ind = $stade['stade'];
		$tabdec = phenologie($cdnom,$idval,$nomvar,$rang,$idbio);
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