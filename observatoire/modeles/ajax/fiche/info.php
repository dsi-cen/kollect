<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';
/*
SELECT MIN(EXTRACT(month FROM date1)) AS mois, Max(EXTRACT(month FROM date1)) AS mois1, date1 FROM obs.fiche
INNER JOIN obs.obs USING(idfiche)
WHERE cdref = 247057
GROUP BY date1
*/
function plusobs($nomvar,$rang,$cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT t1.nb, to_char(date1, 'DD/MM/YYYY') AS datefr, observateur, idfiche, plusobser, idobs FROM obs.obs AS t1
							INNER JOIN obs.fiche USING(idfiche)
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
							INNER JOIN $nomvar.liste ON liste.cdnom = t1.cdref
							INNER JOIN (SELECT MAX(nb) AS nb FROM obs.obs 
								INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
								WHERE obs.cdref = :cdnom OR cdsup = :cdnom AND (validation = 1 OR validation = 2)) AS t2
							ON t2.nb = t1.nb
							WHERE t1.cdref = :cdnom OR cdsup = :cdnom AND (validation = 1 OR validation = 2)
							ORDER BY date1 DESC
							LIMIT 1 ");
	}
	elseif($rang == 'non')
	{
		$req = $bdd->prepare("SELECT t1.nb, to_char(date1, 'DD/MM/YYYY') AS datefr, observateur, idfiche, plusobser, idobs FROM obs.obs AS t1
							INNER JOIN obs.fiche USING(idfiche)
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
							INNER JOIN (SELECT MAX(nb) AS nb FROM obs.obs WHERE cdref = :cdnom AND (validation = 1 OR validation = 2)) AS t2
							ON t2.nb = t1.nb
							WHERE cdref = :cdnom AND (validation = 1 OR validation = 2)
							ORDER BY date1 DESC
							LIMIT 1 ");
	}
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$result = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function altitudemin($nomvar,$rang,$cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT t1.altitude, to_char(date1, 'DD/MM/YYYY') AS datefr, observateur, idfiche, plusobser, idobs FROM obs.coordonnee AS t1
							INNER JOIN obs.fiche USING(idcoord)
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							INNER JOIN (SELECT MIN(altitude) AS altitude FROM obs.obs 
								INNER JOIN obs.fiche USING(idfiche)
								INNER JOIN obs.coordonnee USING(idcoord)
								INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
								WHERE obs.cdref = :cdnom OR cdsup = :cdnom) AS t2
							ON t2.altitude = t1.altitude
							WHERE obs.cdref = :cdnom OR cdsup = :cdnom
							ORDER BY date1 DESC
							LIMIT 1 ");
	}
	elseif($rang == 'non')
	{
		$req = $bdd->prepare("SELECT t1.altitude, to_char(date1, 'DD/MM/YYYY') AS datefr, observateur, idfiche, plusobser, idobs FROM obs.coordonnee AS t1
							INNER JOIN obs.fiche USING(idcoord)
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
							INNER JOIN (SELECT MIN(altitude) AS altitude FROM obs.obs 
								INNER JOIN obs.fiche USING(idfiche)
								INNER JOIN obs.coordonnee USING(idcoord)
								WHERE cdref = :cdnom) AS t2
							ON t2.altitude = t1.altitude
							WHERE cdref = :cdnom
							ORDER BY date1 DESC
							LIMIT 1 ");
	}
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$result = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function altitudemax($nomvar,$rang,$cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT t1.altitude, to_char(date1, 'DD/MM/YYYY') AS datefr, observateur, idfiche, plusobser, idobs FROM obs.coordonnee AS t1
							INNER JOIN obs.fiche USING(idcoord)
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							INNER JOIN (SELECT MAX(altitude) AS altitude FROM obs.obs 
								INNER JOIN obs.fiche USING(idfiche)
								INNER JOIN obs.coordonnee USING(idcoord)
								INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
								WHERE obs.cdref = :cdnom OR cdsup = :cdnom) AS t2
							ON t2.altitude = t1.altitude
							WHERE obs.cdref = :cdnom OR cdsup = :cdnom
							ORDER BY date1 DESC
							LIMIT 1 ");
	}
	elseif($rang == 'non')
	{
		$req = $bdd->prepare("SELECT t1.altitude, to_char(date1, 'DD/MM/YYYY') AS datefr, observateur, idfiche, plusobser, idobs FROM obs.coordonnee AS t1
							INNER JOIN obs.fiche USING(idcoord)
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
							INNER JOIN (SELECT MAX(altitude) AS altitude FROM obs.obs 
								INNER JOIN obs.fiche USING(idfiche)
								INNER JOIN obs.coordonnee USING(idcoord)
								WHERE cdref = :cdnom) AS t2
							ON t2.altitude = t1.altitude
							WHERE cdref = :cdnom
							ORDER BY date1 DESC
							LIMIT 1 ");
	}		
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$result = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function moismin($nomvar,$rang,$cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT MIN(iddecade) AS iddecade FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN referentiel.decade ON decade.decade = fiche.decade
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						WHERE obs.cdref = :cdnom OR cdsup = :cdnom AND (validation = 1 OR validation = 2) ");
	}
	elseif($rang == 'non')
	{	
		$req = $bdd->prepare("SELECT MIN(iddecade) AS iddecade FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN referentiel.decade ON decade.decade = fiche.decade
							WHERE cdref = :cdnom AND (validation = 1 OR validation = 2) ");	
	}
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$result = $req->fetchColumn();
	$req->closeCursor();
	return $result;
}
function moismax($nomvar,$rang,$cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT MAX(iddecade) AS iddecade FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN referentiel.decade ON decade.decade = fiche.decade
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							WHERE obs.cdref = :cdnom OR cdsup = :cdnom AND (validation = 1 OR validation = 2) ");
	}
	elseif($rang == 'non')
	{
		$req = $bdd->prepare("SELECT MAX(iddecade) AS iddecade FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN referentiel.decade ON decade.decade = fiche.decade
							WHERE cdref = :cdnom AND (validation = 1 OR validation = 2) ");
	}
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$result = $req->fetchColumn();
	$req->closeCursor();
	return $result;
}
function joursmin($cdnom,$iddecade,$nomvar,$rang)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT MIN(EXTRACT(day FROM date1)) AS mois FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN referentiel.decade ON decade.decade = fiche.decade
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						WHERE (obs.cdref = :cdnom OR cdsup = :cdnom) AND iddecade = :iddecade AND (validation = 1 OR validation = 2) ");
	}
	elseif($rang == 'non')
	{
		$req = $bdd->prepare("SELECT MIN(EXTRACT(day FROM date1)) AS mois FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN referentiel.decade ON decade.decade = fiche.decade
						WHERE cdref = :cdnom AND iddecade = :iddecade AND (validation = 1 OR validation = 2) ");
	}
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':iddecade', $iddecade);
	$req->execute();
	$result = $req->fetchColumn();
	$req->closeCursor();
	return $result;
}
function joursmax($cdnom,$iddecade,$nomvar,$rang)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT MAX(EXTRACT(day FROM date1)) AS mois FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN referentiel.decade ON decade.decade = fiche.decade
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						WHERE (obs.cdref = :cdnom OR cdsup = :cdnom) AND iddecade = :iddecade AND (validation = 1 OR validation = 2) ");
	}
	elseif($rang == 'non')
	{
		$req = $bdd->prepare("SELECT MAX(EXTRACT(day FROM date1)) AS mois FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN referentiel.decade ON decade.decade = fiche.decade
						WHERE cdref = :cdnom AND iddecade = :iddecade AND (validation = 1 OR validation = 2) ");
	}	
	$req->bindValue(':cdnom', $cdnom);
	$req->bindValue(':iddecade', $iddecade);
	$req->execute();
	$result = $req->fetchColumn();
	$req->closeCursor();
	return $result;
}
function derniere($cdnom,$nomvar,$rang)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT to_char(date1, 'DD/MM/YYYY') AS datefr, observateur, idfiche, plusobser, idobs FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							WHERE obs.cdref = :cdnom OR cdsup = :cdnom AND (validation = 1 OR validation = 2)
							ORDER BY date1 DESC
							LIMIT 1 ");
	}
	elseif($rang == 'non')
	{
		$req = $bdd->prepare("SELECT to_char(date1, 'DD/MM/YYYY') AS datefr, observateur, idfiche, plusobser, idobs FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
							WHERE cdref = :cdnom AND (validation = 1 OR validation = 2)
							ORDER BY date1 DESC
							LIMIT 1 ");
	}
	$req->bindValue(':cdnom', $cdnom);
	$req->execute();
	$result = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $result;
}
function cherche_observateur($idfiche,$obser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT observateur FROM obs.plusobser
						INNER JOIN referentiel.observateur ON observateur.idobser = plusobser.idobser
						WHERE idfiche = :idfiche
						ORDER BY idplus ");
	$req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
	$req->execute();
	$obsplus = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	$obs2[] = $obser;
	foreach($obsplus as $n)
	{
		$obs2[] = $n['observateur'];
	}
	$observateur = implode(', ', $obs2);	
	return $observateur;
}
function recupmois($iddecade)
{
	switch ($iddecade)
	{
		case 1:$cmois = 'Janvier';break;case 2:$cmois = 'Janvier';break;case 3:$cmois = 'Février';break;
		case 4:$cmois = 'Février';break;case 5:$cmois = 'Février';break;case 6:$cmois = 'Février';break;
		case 7:$cmois = 'Mars';break;case 8:$cmois = 'Mars';break;case 9:$cmois = 'Mars';break;
		case 10:$cmois = 'Avril';break;case 11:$cmois = 'Avril';break;case 12:$cmois = 'Avril';break;
		case 13:$cmois = 'Mai';break;case 14:$cmois = 'Mai';break;case 15:$cmois = 'Mai';break;
		case 16:$cmois = 'Juin';break;case 17:$cmois = 'Juin';break;case 18:$cmois = 'Juin';break;
		case 19:$cmois = 'Juillet';break;case 20:$cmois = 'Juillet';break;case 21:$cmois = 'Juillet';break;
		case 22:$cmois = 'Août';break;case 23:$cmois = 'Août';break;case 24:$cmois = 'Août';break;
		case 25:$cmois = 'Septembre';break;case 26:$cmois = 'Septembre';break;case 27:$cmois = 'Septembre';break;
		case 28:$cmois = 'Octobre';break;case 29:$cmois = 'Octobre';break;case 30:$cmois = 'Octobre';break;
		case 31:$cmois = 'Novembre';break;case 32:$cmois = 'Novembre';break;case 33:$cmois = 'Novembre';break;
		case 34:$cmois = 'Décembre';break;case 35:$cmois = 'Décembre';break;case 36:$cmois = 'Décembre';break;
	}
	return $cmois;
}
function nbobs($nomvar,$rang,$cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT DISTINCT EXTRACT(YEAR FROM date1) AS annee, COUNT(idobs) AS nb FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							WHERE obs.cdref = :cdref OR cdsup = :cdref
							GROUP BY annee ") or die(print_r($bdd->errorInfo()));
	}
	elseif($rang == 'non')
	{
		$req = $bdd->prepare("SELECT DISTINCT EXTRACT(YEAR FROM date1) AS annee, COUNT(idobs) AS nb FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							WHERE cdref = :cdref
							GROUP BY annee ") or die(print_r($bdd->errorInfo()));
	}
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function etatbio($nomvar,$rang,$cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT COUNT(idobs) AS nb, idetatbio FROM obs.ligneobs
							INNER JOIN obs.obs USING(idobs)
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							WHERE liste.cdref = :cdref OR cdsup = :cdref
							GROUP BY idetatbio ") or die(print_r($bdd->errorInfo()));
	}
	elseif($rang == 'non')
	{
		$req = $bdd->prepare("SELECT COUNT(idobs) AS nb, idetatbio FROM obs.ligneobs
							INNER JOIN obs.obs USING(idobs)
							WHERE cdref = :cdref
							GROUP BY idetatbio ") or die(print_r($bdd->errorInfo()));
	}
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function prospection($nomvar,$rang,$cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT COUNT(idobs) AS nb, prospection FROM obs.ligneobs
							INNER JOIN obs.obs USING(idobs)
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							INNER JOIN referentiel.prospection USING(idpros)
							WHERE liste.cdref = :cdref OR cdsup = :cdref
							GROUP BY idpros, prospection ") or die(print_r($bdd->errorInfo()));
	}
	elseif($rang == 'non')
	{
		$req = $bdd->prepare("SELECT COUNT(idobs) AS nb, prospection FROM obs.ligneobs
							INNER JOIN obs.obs USING(idobs)
							INNER JOIN referentiel.prospection USING(idpros)
							WHERE cdref = :cdref
							GROUP BY idpros, prospection ") or die(print_r($bdd->errorInfo()));
	}
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function methode($nomvar,$rang,$cdnom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($rang == 'oui')
	{
		$req = $bdd->prepare("SELECT COUNT(idobs) AS nb, methode FROM obs.ligneobs
							INNER JOIN obs.obs USING(idobs)
							INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
							INNER JOIN referentiel.methode USING(idmethode)
							WHERE liste.cdref = :cdref OR cdsup = :cdref
							GROUP BY methode ") or die(print_r($bdd->errorInfo()));
	}
	elseif($rang == 'non')
	{
		$req = $bdd->prepare("SELECT COUNT(idobs) AS nb, methode FROM obs.ligneobs
							INNER JOIN obs.obs USING(idobs)
							INNER JOIN referentiel.methode USING(idmethode)
							WHERE cdref = :cdref
							GROUP BY methode ") or die(print_r($bdd->errorInfo()));
	}
	$req->bindValue(':cdref', $cdnom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
if(isset($_POST['cdnom'])) 
{
	$cdnom = htmlspecialchars($_POST['cdnom']);
	$rang = htmlspecialchars($_POST['rang']);
	$nomvar = htmlspecialchars($_POST['nomvar']);	
	//maxobservé	
	$obsmax = plusobs($nomvar,$rang,$cdnom);
	$observateur = ($obsmax['plusobser'] != 'oui') ? $obsmax['observateur'] : cherche_observateur($obsmax['idfiche'],$obsmax['observateur']);
	$retour['obsmax'] = '<b>'.$obsmax['nb'].'</b> le '.$obsmax['datefr']. ' ('.$observateur.')'. ' <a href="../index.php?module=observation&amp;action=detail&amp;idobs='.$obsmax['idobs'].'" title="Détail de l\'observation"><i class="fa fa-eye color1"></i></a>';
	//min et max date
	$iddecade = moismin($nomvar,$rang,$cdnom);
	$jours = joursmin($cdnom,$iddecade,$nomvar,$rang);
	$mois = recupmois($iddecade);
	$retour['extrememin'] = $jours.' '.$mois;
	$iddecade = moismax($nomvar,$rang,$cdnom);
	$jours = joursmax($cdnom,$iddecade,$nomvar,$rang);
	$mois = recupmois($iddecade);
	$retour['extrememax'] = $jours.' '.$mois;
	//derniere obs
	$derniere = derniere($cdnom,$nomvar,$rang);
	$observateur = ($derniere['plusobser'] != 'oui') ? $derniere['observateur'] : cherche_observateur($derniere['idfiche'],$derniere['observateur']);
	$retour['derniere'] = 'Le <b>'.$derniere['datefr']. '</b> ('.$observateur.')'. ' <a href="../index.php?module=observation&amp;action=detail&amp;idobs='.$derniere['idobs'].'" title="Détail de l\'observation"><i class="fa fa-eye color1"></i></a>';
	//graph evol	
	$nbobs = nbobs($nomvar,$rang,$cdnom);
	$json_site = file_get_contents('../../../../json/site.json');
	$rjson = json_decode($json_site, true);
	//altitude
	if(isset($rjson['fiche']['alt']) && $rjson['fiche']['alt'] == 'oui') 
	{
		$alt = altitudemin($nomvar,$rang,$cdnom);
		$observateur = ($alt['plusobser'] != 'oui') ? $alt['observateur'] : cherche_observateur($alt['idfiche'],$alt['observateur']);
		$retour['altimin'] = 'Min : <b>'.$alt['altitude'].' mètres</b> le '.$alt['datefr']. ' ('.$observateur.')'. ' <a href="../index.php?module=observation&amp;action=detail&amp;idobs='.$alt['idobs'].'" title="Détail de l\'observation"><i class="fa fa-eye color1"></i></a>';
		$alt = altitudemax($nomvar,$rang,$cdnom);
		$observateur = ($alt['plusobser'] != 'oui') ? $alt['observateur'] : cherche_observateur($alt['idfiche'],$alt['observateur']);
		$retour['altimax'] = 'Max : <b>'.$alt['altitude'].' mètres</b> le '.$alt['datefr']. ' ('.$observateur.')'. ' <a href="../index.php?module=observation&amp;action=detail&amp;idobs='.$alt['idobs'].'" title="Détail de l\'observation"><i class="fa fa-eye color1"></i></a>';
	}
	//$anneeencours = date('Y');
	if(isset($rjson['fiche']['classefiche'])) 
	{
		$nbclasse = count($rjson['fiche']['classefiche']);
		foreach($rjson['fiche']['classefiche'] as $n)
		{		
			if($n['classe'] == 'classe1') {}
			elseif($n['classe'] == 'classe2') {$an2 = $n['annee'];}
			elseif($n['classe'] == 'classe3') {$an3 = $n['annee'];}
			elseif($n['classe'] == 'classe4') {$an4 = $n['annee'];}
			elseif($n['classe'] == 'classe5') {$an5 = $n['annee'];}
			elseif($n['classe'] == 'classe6') {$an6 = $n['annee'];}
		}		
	}
	$nb1 = 0; $nb2 = 0; $nb3 = 0; $nb4 = 0; $nb5 = 0; $nb6 = 0;
	foreach($nbobs as $n)
	{
		if($nbclasse == 3)
		{
			if ($n['annee'] > $an2) {$nb1 = $n['nb'] + $nb1;}
			elseif ($n['annee'] <= $an3) {$nb2 = $n['nb'] + $nb2;}				
		}
		elseif($nbclasse == 4)
		{
			if ($n['annee'] > $an2) {$nb1 = $n['nb'] + $nb1;}
			elseif ($n['annee'] <= $an2 && $n['annee'] >= $an3) {$nb2 = $n['nb'] + $nb2;}
			elseif ($n['annee'] < $an4) {$nb3 = $n['nb'] + $nb3;}						
		}
		elseif($nbclasse == 5)
		{
			if ($n['annee'] > $an2) {$nb1 = $n['nb'] + $nb1;}
			elseif ($n['annee'] <= $an2 && $n['annee'] >= $an3) {$nb2 = $n['nb'] + $nb2;}
			elseif ($n['annee'] < $an3 && $n['annee'] >= $an4) {$nb3 = $n['nb'] + $nb3;}
			elseif ($n['annee'] < $an5) {$nb4 = $n['nb'] + $nb4;}				
		}
		elseif($nbclasse == 6)
		{
			if ($n['annee'] > $an2) {$nb1 = $n['nb'] + $nb1;}
			elseif ($n['annee'] <= $an2 && $n['annee'] >= $an3) {$nb2 = $n['nb'] + $nb2;}
			elseif ($n['annee'] < $an3 && $n['annee'] >= $an4) {$nb3 = $n['nb'] + $nb3;}
			elseif ($n['annee'] < $an4 && $n['annee'] >= $an5) {$nb4 = $n['nb'] + $nb4;}
			elseif ($n['annee'] < $an6) {$nb5 = $n['nb'] + $nb5;}				
		}
	}
	if($nbclasse == 3) 
	{
		$tabclass = array('Avant '.$an3, 'Après '.$an2);
		$tabnb = array($nb2,$nb1);
	}
	elseif($nbclasse == 4) 
	{
		$tabclass = array('Avant '.$an4, 'Entre '.$an3.' et '.$an2, 'Après '.$an2);
		$tabnb = array($nb3,$nb2,$nb1);
	}
	elseif($nbclasse == 5) 
	{
		$tabclass = array('Avant '.$an5, 'Entre '.$an4.' et '.$an3, 'Entre '.$an3.' et '.$an2, 'Après '.$an2);
		$tabnb = array($nb4,$nb3,$nb2,$nb1);		
	}
	elseif($nbclasse == 6) 
	{
		$tabclass = array('Avant '.$an6, 'Entre '.$an5.' et '.$an4, 'Entre '.$an4.' et '.$an3, 'Entre '.$an3.' et '.$an2, 'Après '.$an2);
		$tabnb = array($nb5,$nb4,$nb3,$nb2,$nb1);		
	}
	//graph etat bio
	$etat = etatbio($nomvar,$rang,$cdnom);
	foreach($etat as $n)
	{
		if($n['idetatbio'] == 1)
		{
			$tabetat['name'] = 'Non renseigné';
			$tabetat['y'] = $n['nb'];
			$tabetat['sliced'] = false;
		}
		elseif($n['idetatbio'] == 2)
		{
			$tabetat['name'] = 'Observé vivant';
			$tabetat['y'] = $n['nb'];
			$tabetat['sliced'] = true;
		}
		elseif($n['idetatbio'] == 3)
		{
			$tabetat['name'] = 'Trouvé mort';
			$tabetat['y'] = $n['nb'];
			$tabetat['sliced'] = false;
		}
		elseif($n['idetatbio'] == 0)
		{
			$tabetat['name'] = 'Inconu';
			$tabetat['y'] = $n['nb'];
			$tabetat['sliced'] = false;
		}
		$dataetat[] = $tabetat;
	}
	if(count($etat) > 1)
	{	
		$retour['dataetat'] = $dataetat;
		$retour['graphetat'] = 'oui';
	}
	else
	{
		$retour['dataetat'] = $dataetat[0]['name'];
	}
	//graph prospection
	$prospect = prospection($nomvar,$rang,$cdnom);
	foreach($prospect as $n)
	{
		$tabprospec['name'] = $n['prospection'];
		$tabprospec['y'] = $n['nb'];
		$dataprospect[] = $tabprospec;
	}	
	//graph methode
	$methode = methode($nomvar,$rang,$cdnom);
	if(count($methode) > 1)
	{
		foreach($methode as $n)
		{
			$tabmethode['name'] = $n['methode'];
			$tabmethode['y'] = $n['nb'];
			$datamethode[] = $tabmethode;
		}
		$retour['datamethode'] = $datamethode;
		$retour['graphmethode'] = 'oui';
	}
	else
	{
		$retour['datamethode'] = $methode[0]['methode'];	
	}
	
	$retour['dataprospect'] = $dataprospect;	
	$retour['tabclass'] = $tabclass;
	$retour['tabnb'] = $tabnb;
	$retour['statut'] = 'Oui';	
}
else
{
	$retour['statut'] = 'Non';
}	
//echo json_encode($retour);
echo json_encode($retour, JSON_NUMERIC_CHECK);
