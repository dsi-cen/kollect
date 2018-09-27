<?php 
include '../../global/configbase.php';
include '../lib/pdo2.php';

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
function listeindice($nomvar,$date,$maillage)
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
function mod_indice($cdref,$indice)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("UPDATE referentiel.liste SET ir = :indice WHERE cdnom = :cdref ");
	$req->bindValue(':cdref', $cdref);
	$req->bindValue(':indice', $indice);
	$req->execute();
	$req->closeCursor();
}
if(isset($_POST['observa'])) 
{
	$observa = $_POST['observa'];
	$json_emprise = file_get_contents('../../emprise/emprise.json');
	$emprise = json_decode($json_emprise, true);
	$json_obser = file_get_contents('../../json/'.$observa.'.json');
	$rjson = json_decode($json_obser, true);
	if(isset($rjson['indice']))
	{
		$date = (isset($rjson['indice']['date'])) ? $rjson['indice']['date'] : null;
		$m = calc_m($rjson['indice']['choix'],$observa,$rjson['indice']['valchoix'],$rjson['indice']['maillage'],$date);
		
		$mailletotal = ($rjson['indice']['maillage'] == 'l93') ? $emprise['nbmaille'] : $emprise['nbmaille5'];
		$M = round($m/$mailletotal*100,1);
		$cr1a = round(100-($rjson['indice']['ms']/$mailletotal)*100,1);
		$cr2a = $cr1a - 1; $cr3a = $cr2a - 2; $cr4a = $cr3a - 4; $cr5a = $cr4a - 8; $cr6a = $cr5a - 16; $cr7a = $cr6a - 32;
		$cr1 = round($cr1a+($M-($cr1a*$M/100)),1);
		$cr2 = round($cr2a+($M-($cr2a*$M/100)),1);
		$cr3 = round($cr3a+($M-($cr3a*$M/100)),1);
		$cr4 = round($cr4a+($M-($cr4a*$M/100)),1);
		$cr5 = round($cr5a+($M-($cr5a*$M/100)),1);
		$cr6 = round($cr6a+($M-($cr6a*$M/100)),1);
		$cr7 = round($cr7a+($M-($cr7a*$M/100)),1);
	
		$listeindice = listeindice($observa,$date,$rjson['indice']['maillage']);
		foreach($listeindice as $n)
		{
			if(!empty($n['nb']))
			{
				$ir = round(100 - ($n['nb']/$mailletotal) * 100,1);
				if($ir >= $cr1) { $indice = 'E'; }
				elseif($ir >= $cr2 && $ir < $cr1) { $indice = 'TR'; }
				elseif($ir >= $cr3 && $ir < $cr2) { $indice = 'R'; }
				elseif($ir >= $cr4 && $ir < $cr3) { $indice = 'AR'; }
				elseif($ir >= $cr5 && $ir < $cr4) { $indice = 'PC'; }
				elseif($ir >= $cr6 && $ir < $cr5) { $indice = 'AC'; }
				elseif($ir >= $cr7 && $ir < $cr6) { $indice = 'C'; }
				elseif ($ir < $cr7) { $indice = 'CC'; }
				if($n['ir'] != $indice) 
				{
					mod_indice($n['cdnom'],$indice);
				}
			}
			else
			{
				if($n['ir'] != 'D?') 
				{
					$indice = 'D?';
					mod_indice($n['cdnom'],$indice);
				}
			}
		}	
	}
}
?>