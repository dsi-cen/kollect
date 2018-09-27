<?php 
include '../../../global/configbase.php';
include '../../lib/pdo2.php';

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
}
else
{
	
}
?>