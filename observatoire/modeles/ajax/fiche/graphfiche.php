<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function recherchedecade($decade)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT iddecade FROM referentiel.decade WHERE decade = :decade ");
	$req->bindValue(':decade', $decade);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function recherchestade($cdnom,$nomvar,$rang)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT DISTINCT stade.stade, idval FROM obs.obs
							INNER JOIN obs.ligneobs USING(idobs)
							INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							WHERE (obs.cdref = :cdnom OR cdsup = :cdnom) AND idetatbio = 2 AND (validation = 1 OR validation = 2) ");
	}	
	elseif($rang == 'non')	
	{	
		$req = $bdd->prepare("SELECT DISTINCT stade.stade, idval FROM obs.obs
							INNER JOIN obs.ligneobs USING(idobs)
							INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
							WHERE cdref = :cdnom AND idetatbio = 2 AND (validation = 1 OR validation = 2) ");
	}
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$stade = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $stade;
}
function phenologie($cdnom,$nomvar,$rang)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($rang == 'oui')
	{
		$req = $bdd->prepare("WITH sel AS (
								SELECT idobs FROM obs.obs 
								INNER JOIN obs.ligneobs using(idobs) 
								INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
								WHERE (obs.cdref = :cdnom OR cdsup = :cdnom) AND idetatbio = 2 AND (validation = 1 OR validation = 2)
							)
							SELECT COUNT(sel.idobs) AS nb, iddecade, decade.decade, idval FROM sel
							INNER JOIN obs.obs ON obs.idobs = sel.idobs
							INNER JOIN obs.fiche ON fiche.idfiche = obs.idfiche
							INNER JOIN obs.ligneobs ON ligneobs.idobs = sel.idobs
							INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
							INNER JOIN referentiel.decade USING(decade)
							GROUP BY iddecade, decade.decade, idval
							ORDER BY iddecade ");		
	}
	elseif($rang == 'non')
	{
		$req = $bdd->prepare("WITH sel AS (
								SELECT idobs FROM obs.obs 
								INNER JOIN obs.ligneobs using(idobs) 
								WHERE cdref = :cdnom AND idetatbio = 2 AND (validation = 1 OR validation = 2)
							)
							SELECT COUNT(sel.idobs) AS nb, iddecade, decade.decade, idval FROM sel
							INNER JOIN obs.obs ON obs.idobs = sel.idobs
							INNER JOIN obs.fiche ON fiche.idfiche = obs.idfiche
							INNER JOIN obs.ligneobs ON ligneobs.idobs = sel.idobs
							INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
							INNER JOIN referentiel.decade USING(decade)
							GROUP BY iddecade, decade.decade, idval
							ORDER BY iddecade ");
	}
	$req->bindValue(':cdnom', $cdnom);
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
	
	$tabiddecade = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36];
	
	$stade = recherchestade($cdnom,$nomvar,$rang);
	foreach($stade as $n)
	{
		if($n['idval'] == 2) { $tadulte[] = $n['stade']; }
		if($n['idval'] == 3) { $tjuv[] = $n['stade']; }
		if($n['idval'] == 6) { $tlarve[] = $n['stade']; }
		if($n['idval'] == 13) { $tnymphe[] = $n['stade']; }
		if($n['idval'] == 1) { $tind = $n['stade']; }
	}
	$adulte = (isset($tadulte)) ? implode(", ", $tadulte) : 'non';	
	$juv = (isset($tjuv)) ? implode(", ", $tjuv) : 'non';
	$larve = (isset($tlarve)) ? implode(", ", $tlarve) : 'non';
	$nymphe = (isset($nymphe)) ? implode(", ", $tnymphe) : 'non';
	$ind = (isset($tind)) ? $tind : 'non';
	
	foreach($tabiddecade as $n)
	{
		if($adulte != 'non') { $tab2[$n-1] = 0; }
		if($juv != 'non') { $tab3[$n-1] = 0; }
		if($larve != 'non') { $tab6[$n-1] = 0; }
		if($nymphe != 'non') { $tab13[$n-1] = 0; }
		if($ind != 'non') { $tab1[$n-1] = 0; }
	}
	
	$tabdec = phenologie($cdnom,$nomvar,$rang);
	foreach($tabdec as $n)
	{
		if($n['idval'] == 2 && $adulte != 'non') { $tab2[$n['iddecade']-1] = $n['nb']; }
		if($n['idval'] == 3 && $juv != 'non') { $tab3[$n['iddecade']-1] = $n['nb']; }
		if($n['idval'] == 6 && $larve != 'non') { $tab6[$n['iddecade']-1] = $n['nb']; }
		if($n['idval'] == 13 && $nymphe != 'non') { $tab13[$n['iddecade']-1] = $n['nb']; }
		if($n['idval'] == 1 && $ind != 'non') { $tab1[$n['iddecade']-1] = $n['nb']; }
	}		
	
	$retour['decade'] = recherchedecade($decade) - 1;
	$retour['serie2'] = $adulte;
	$retour['serie3'] = $juv;
	$retour['serie6'] = $larve;
	$retour['serie13'] = $nymphe;
	$retour['serie1'] = $ind;
	$retour['tab2'] = (isset($tab2)) ? $tab2 : null;
	$retour['tab3'] = (isset($tab3)) ? $tab3 : null;
	$retour['tab6'] = (isset($tab6)) ? $tab6 : null;
	$retour['tab13'] = (isset($tab13)) ? $tab13 : null;
	$retour['tab1'] = (isset($tab1)) ? $tab1 : null;
	$retour['statut'] = 'Oui';		
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour, JSON_NUMERIC_CHECK);
?>