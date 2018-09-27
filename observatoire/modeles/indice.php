<?php
function calc_m($choix,$nomvar,$valchoix,$maillage,$date)
{
	$code = ($maillage == 'l93') ? 'codel93' : 'codel935';
	$count = ($choix == 'obs') ? 'COUNT(idobs) AS nb' : 'COUNT(distinct cdref) AS nb';
	$strQuery = 'SELECT COUNT(nb) FROM (';
	$strQuery .= ' SELECT '.$code.', '.$count.' FROM obs.obs';
	$strQuery .= ' INNER JOIN obs.fiche USING(idfiche) INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord';
	$strQuery .= ' WHERE observa = :sel';
	if(!empty($date)) { $strQuery .= ' AND date1 >= :date'; }
	$strQuery .= ' GROUP BY codel93 ) AS s';
	$strQuery .= ' WHERE nb <= :mini';
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':sel', $nomvar);
	if(!empty($date)) { $req->bindValue(':date', $date); }
	$req->bindValue(':mini', $valchoix);
	$req->execute();
	$m = $req->fetchColumn();
	$req->closeCursor();
	return $m;
}
function calc($ir,$cr1,$cr2,$cr3,$cr4,$cr5,$cr6,$cr7,$crp1,$crp2,$crp3,$crp4,$crp5,$crp6,$crp7)
{
	if($ir >= $cr1) {$indice = 'Exceptionnelle';}
	elseif($ir >= $cr2 && $ir < $cr1) {$indice = 'Très rare';}
	elseif($ir >= $cr3 && $ir < $cr2) {$indice = 'Rare';}
	elseif($ir >= $cr4 && $ir < $cr3) {$indice = 'Assez rare';}
	elseif($ir >= $cr5 && $ir < $cr4) {$indice = 'Peu commune';}
	elseif($ir >= $cr6 && $ir < $cr5) {$indice = 'Assez commune';}
	elseif($ir >= $cr7 && $ir < $cr6) {$indice = 'Commune';}
	elseif ($ir < $cr7) {$indice = 'Très commune';}
	
	if($ir >= $crp1) {$indice = 'Exceptionnelle';}
	elseif($ir >= $crp2 && $ir < $crp1) {$indicep = 'Très rare';}
	elseif($ir >= $crp3 && $ir < $crp2) {$indicep = 'Rare';}
	elseif($ir >= $crp4 && $ir < $crp3) {$indicep = 'Assez rare';}
	elseif($ir >= $crp5 && $ir < $crp4) {$indicep = 'Peu commune';}
	elseif($ir >= $crp6 && $ir < $crp5) {$indicep = 'Assez commune';}
	elseif($ir >= $crp7 && $ir < $crp6) {$indicep = 'Commune';}
	elseif ($ir < $crp7) {$indicep = 'Très commune';}	
	
	return array($indice,$indicep);
}
/*function listeindice($nomvar,$date,$maillage)
{
	$strQuery = 'WITH sel AS (SELECT DISTINCT cdref FROM obs.obs WHERE observa = :observa), sel1 AS (';
	$strQuery .= ($maillage == 'l93') ? ' SELECT cdref, COUNT(DISTINCT codel93) AS nb FROM sel' : ' SELECT cdref, COUNT(DISTINCT codel935) AS nb FROM sel';
	$strQuery .= ' INNER JOIN obs.obs USING(cdref) INNER JOIN obs.fiche USING(idfiche) INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord';
	if(!empty($date)) { $strQuery .= ' WHERE date1 >= :date'; }
	$strQuery .= ' GROUP BY cdref)';
	$strQuery .= ' SELECT cdnom, nb, ir FROM referentiel.liste LEFT JOIN sel1 ON liste.cdnom = sel1.cdref INNER JOIN sel ON sel.cdref = liste.cdnom';
	$strQuery .= " WHERE observatoire = :observa AND (rang = 'ES' OR rang = 'SSES')";
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':observa', $nomvar);
	if(!empty($date)) { $req->bindValue(':date', $date); }
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function calc_obs($nomvar,$obs,$maillage)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($maillage == 'l93')
	{
		$req = $bdd->prepare("SELECT COUNT(nb) FROM (
								SELECT codel93, COUNT(idobs) AS nb FROM obs.obs
								INNER JOIN obs.fiche USING(idfiche)
								INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
								WHERE observa = :sel 
								GROUP BY codel93 ) AS s
							WHERE nb <= :mini ");
	}
	else
	{
		$req = $bdd->prepare("SELECT COUNT(nb) FROM (
								SELECT codel935, COUNT(idobs) AS nb FROM obs.obs
								INNER JOIN obs.fiche USING(idfiche)
								INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
								WHERE observa = :sel 
								GROUP BY codel935 ) AS s
							WHERE nb <= :mini ");
	}
	$req->bindValue(':mini', $obs);
	$req->bindValue(':sel', $nomvar);
	$req->execute();
	$m = $req->fetchColumn();
	$req->closeCursor();
	return $m;
}
function calc_es($nomvar,$es,$maillage)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($maillage == 'l93')
	{
		$req = $bdd->prepare("SELECT COUNT(nb) FROM (
							SELECT codel93, COUNT(distinct cdref) AS nb FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
							WHERE observa = :sel
							GROUP BY codel93 ) AS s
						WHERE nb <= :mini ");
	}
	else
	{
		$req = $bdd->prepare("SELECT COUNT(nb) FROM (
							SELECT codel935, COUNT(distinct cdref) AS nb FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
							WHERE observa = :sel
							GROUP BY codel935 ) AS s
						WHERE nb <= :mini ");		
	}
	$req->bindValue(':mini', $es);
	$req->bindValue(':sel', $nomvar);
	$req->execute();
	$m = $req->fetchColumn();
	$req->closeCursor();
	return $m;
}
function calc($ir,$cr1,$cr2,$cr3,$cr4,$cr5,$cr6,$cr7,$crp1,$crp2,$crp3,$crp4,$crp5,$crp6,$crp7)
{
	if($ir >= $cr1) {$indice = 'Exceptionnelle';}
	elseif($ir >= $cr2 && $ir < $cr1) {$indice = 'Très rare';}
	elseif($ir >= $cr3 && $ir < $cr2) {$indice = 'Rare';}
	elseif($ir >= $cr4 && $ir < $cr3) {$indice = 'Assez rare';}
	elseif($ir >= $cr5 && $ir < $cr4) {$indice = 'Peu commune';}
	elseif($ir >= $cr6 && $ir < $cr5) {$indice = 'Assez commune';}
	elseif($ir >= $cr7 && $ir < $cr6) {$indice = 'Commune';}
	elseif ($ir < $cr7) {$indice = 'Très commune';}
	
	if($ir >= $crp1) {$indice = 'Exceptionnelle';}
	elseif($ir >= $crp2 && $ir < $crp1) {$indicep = 'Très rare';}
	elseif($ir >= $crp3 && $ir < $crp2) {$indicep = 'Rare';}
	elseif($ir >= $crp4 && $ir < $crp3) {$indicep = 'Assez rare';}
	elseif($ir >= $crp5 && $ir < $crp4) {$indicep = 'Peu commune';}
	elseif($ir >= $crp6 && $ir < $crp5) {$indicep = 'Assez commune';}
	elseif($ir >= $crp7 && $ir < $crp6) {$indicep = 'Commune';}
	elseif ($ir < $crp7) {$indicep = 'Très commune';}	
	
	return array($indice,$indicep);
}
/*function listeindice($nomvar,$date)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("WITH sel AS (
							SELECT DISTINCT cdref FROM obs.obs
							WHERE observa = :observa
						), sel1 AS (
							SELECT cdref, COUNT(DISTINCT codel93) AS nb FROM sel
							INNER JOIN obs.obs USING(cdref)
							INNER JOIN obs.fiche USING(idfiche)
							INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
							WHERE date1 >= :date
							GROUP BY cdref
						)
						SELECT cdnom, nb, ir FROM referentiel.liste
						LEFT JOIN sel1 ON liste.cdnom = sel1.cdref
						INNER JOIN sel ON sel.cdref = liste.cdnom
						WHERE observatoire = :observa AND (rang = 'ES' OR rang = 'SSES') ");
	$req->bindValue(':observa', $nomvar);
	$req->bindValue(':date', $date);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}*/
/*function listeindice($nomvar,$date)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("WITH sel AS (
							SELECT cdref, COUNT(DISTINCT codel93) AS nb FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
							WHERE observa = :observa and date1 >= :date
							GROUP BY cdref
						)
						SELECT cdref, nb, ir FROM sel
						INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref
						WHERE rang = 'ES' OR rang = 'SSES' ");
	$req->bindValue(':observa', $nomvar);
	$req->bindValue(':date', $date);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function listeindice5($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("WITH sel AS (
							SELECT cdref, COUNT(DISTINCT codel935) AS nb FROM obs.fiche
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
							WHERE observa = :observa
							GROUP BY cdref
						)
						SELECT cdref, nb, ir FROM sel
						INNER JOIN referentiel.liste ON liste.cdnom = sel.cdref
						WHERE rang = 'ES' OR rang = 'SSES'") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function mod_indice($cdref,$indice)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE referentiel.liste SET ir = :indice WHERE cdnom = :cdref ");
	$req->bindValue(':cdref', $cdref);
	$req->bindValue(':indice', $indice);
	$req->execute();
	$req->closeCursor();
}*/
