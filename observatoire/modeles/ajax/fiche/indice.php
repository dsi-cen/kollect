<?php 
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';
/*
SELECT EXTRACT(YEAR FROM date1) AS annee, COUNT(DISTINCT codel93) AS nb FROM obs.obs
INNER JOIN obs.fiche USING(idfiche) LEFT JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord 
WHERE obs.cdref = 159442
GROUP BY annee
*/
function calc_m($nomvar,$choix,$valchoix,$maillage,$d1,$d2,$d)
{
	$strQuery = "SELECT COUNT(nb) FROM ( ";
	$strQuery .= ($maillage == 'l93') ? "SELECT codel93, COUNT(idobs) AS nb FROM obs.obs" : "SELECT codel935, COUNT(idobs) AS nb FROM obs.obs";
	$strQuery .= " INNER JOIN obs.fiche USING(idfiche) INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord WHERE observa = :sel";
	if($d == 'sup') { $strQuery .= " AND date1 >= :d1"; }
	elseif($d == 'inf') { $strQuery .= " AND date1 <= :d1"; }
	elseif($d == 'int') { $strQuery .= " AND (date1 >= :d1 AND date1 < :d2)"; }
	$strQuery .= ($maillage == 'l93') ? " GROUP BY codel93 ) AS s" : " GROUP BY codel935 ) AS s";
	$strQuery .= " WHERE nb <= :mini";
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':mini', $valchoix);
	$req->bindValue(':sel', $nomvar);
	if($d == 'sup') { $req->bindValue(':d1', $d1.'-01-01'); }
	elseif($d == 'inf') { $req->bindValue(':d1', $d1.'-12-31'); }
	elseif($d == 'int') { $req->bindValue(':d1', $d1.'-01-01'); $req->bindValue(':d2', $d2.'-01-01'); }
	$req->execute();
	$m = $req->fetchColumn();
	$req->closeCursor();
	return $m;	
}
function nombre_maille($cdnom,$maillage,$d1,$d2,$d)
{
	$strQuery = ($maillage == 'l93') ? "SELECT COUNT(DISTINCT codel93) AS nb FROM obs.obs" : "SELECT COUNT(DISTINCT codel935) AS nb FROM obs.obs";
	$strQuery .= " INNER JOIN obs.fiche USING(idfiche) LEFT JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord WHERE obs.cdref = :cdnom";
	if($d == 'sup') { $strQuery .= " AND date1 >= :d1"; }
	elseif($d == 'inf') { $strQuery .= " AND date1 <= :d1"; }
	elseif($d == 'int') { $strQuery .= " AND (date1 >= :d1 AND date1 < :d2)"; }
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare($strQuery);
	$req->bindValue(':cdnom', $cdnom);
	if($d == 'sup') { $req->bindValue(':d1', $d1.'-01-01'); }
	elseif($d == 'inf') { $req->bindValue(':d1', $d1.'-12-31'); }
	elseif($d == 'int') { $req->bindValue(':d1', $d1.'-01-01'); $req->bindValue(':d2', $d2.'-01-01'); }
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;	
}
function calc_cr($cr1a,$M)
{
	$cr2a = $cr1a - 1; $cr3a = $cr2a - 2; $cr4a = $cr3a - 4; $cr5a = $cr4a - 8; $cr6a = $cr5a - 16; $cr7a = $cr6a - 32;
	$cr1 = round($cr1a+($M-($cr1a*$M/100)),1);
	$cr2 = round($cr2a+($M-($cr2a*$M/100)),1);
	$cr3 = round($cr3a+($M-($cr3a*$M/100)),1);
	$cr4 = round($cr4a+($M-($cr4a*$M/100)),1);
	$cr5 = round($cr5a+($M-($cr5a*$M/100)),1);
	$cr6 = round($cr6a+($M-($cr6a*$M/100)),1);
	$cr7 = round($cr7a+($M-($cr7a*$M/100)),1);
	return array($cr1,$cr2,$cr3,$cr4,$cr5,$cr6,$cr7);
}
function calc($ir,$cr)
{
	if($ir >= $cr[0]) {$indice = 'Exceptionnelle';}
	elseif($ir >= $cr[1] && $ir < $cr[0]) {$indice = 'Très rare';}
	elseif($ir >= $cr[2] && $ir < $cr[1]) {$indice = 'Rare';}
	elseif($ir >= $cr[3] && $ir < $cr[2]) {$indice = 'Assez rare';}
	elseif($ir >= $cr[4] && $ir < $cr[3]) {$indice = 'Peu commun';}
	elseif($ir >= $cr[5] && $ir < $cr[4]) {$indice = 'Assez commun';}
	elseif($ir >= $cr[6] && $ir < $cr[5]) {$indice = 'Commune';}
	elseif ($ir < $cr[6]) {$indice = 'Très commun';}
	
	return $indice;
}

if(isset($_POST['cdnom'])) 
{
	$cdnom = $_POST['cdnom'];
	$nomvar = $_POST['nomvar'];
	
	$json_site = file_get_contents('../../../../json/site.json');
	$rjsonsite = json_decode($json_site, true);
	
	$json_emprise = file_get_contents('../../../../emprise/emprise.json');
	$emprise = json_decode($json_emprise, true);
	
	if(isset($rjsonsite['indice'][$nomvar]))
	{
		$choix = $rjsonsite['indice'][$nomvar]['choix'];
		$valchoix = $rjsonsite['indice'][$nomvar]['valchoix'];
		$maillage = (isset($rjsonsite['indice'][$nomvar]['maillage'])) ? $rjsonsite['indice'][$nomvar]['maillage'] : 'l93';
		$ms = $rjsonsite['indice'][$nomvar]['ms'];
	}
	$mt = ($maillage == 'l93') ? $emprise['nbmaille'] : $emprise['nbmaille5'];
	if(isset($rjsonsite['fiche']['classefiche'])) 
	{
		$nbclasse = count($rjsonsite['fiche']['classefiche']);
		foreach($rjsonsite['fiche']['classefiche'] as $n)
		{		
			if($n['classe'] != 'classe1')
			{
				$tabclasse[] = $n['annee'];
			}
			if($n['classe'] == 'classe2') { $an2 = $n['annee']; }
			elseif($n['classe'] == 'classe3') {$an3 = $n['annee'];}
			elseif($n['classe'] == 'classe4') {$an4 = $n['annee'];}
			elseif($n['classe'] == 'classe5') { $an5 = $n['annee']; }
			elseif($n['classe'] == 'classe6') { $an6 = $n['annee']; }			
		}		
	}
	if($nbclasse == 3) 
	{
		$tabclass = ['Avant '.$an3, 'Après '.$an2];
	}
	elseif($nbclasse == 4) 
	{
		$tabclass = ['Avant '.$an4, 'Entre '.$an3.' et '.$an2, 'Après '.$an2];		
	}
	elseif($nbclasse == 5) 
	{
		$taban[] = ['c5'=>$an5,'c4'=>$an4,'c3'=>$an3,'c2'=>$an2];		
	}
	elseif($nbclasse == 6) 
	{
		$taban[] = ['c6'=>$an6,'c5'=>$an5,'c4'=>$an4,'c3'=>$an3,'c2'=>$an2];		
	}
	$l = '<ul>';
	//revoir avec nb classe	
	foreach($taban as $n)
	{
		if(isset($n['c2']))
		{
			$d = 'sup';
			$sp = nombre_maille($cdnom,$maillage,$n['c2'],$n['c2'],$d);
			if($sp > 0)
			{
				$m = calc_m($nomvar,$choix,$valchoix,$maillage,$n['c2'],$n['c2'],$d);			
				$M = round($m/$mt * 100,1);
				$cr1a = round(100 - ($ms/$mt) * 100,1);
				$ir = round(100 - ($sp/$mt) * 100,1);
				$cr = calc_cr($cr1a,$M);
				$indice = calc($ir,$cr);
				$l .= '<li>Après '.$n['c2'].' : '.$indice.'</li>';
			} else { $l .= '<li>Après '.$n['c2'].' : Aucune donnée</li>'; }
		}
		if(isset($n['c3']))
		{
			$d = 'int';
			$sp = nombre_maille($cdnom,$maillage,$n['c3'],$n['c2'],$d);
			if($sp > 0)
			{
				$m = calc_m($nomvar,$choix,$valchoix,$maillage,$n['c3'],$n['c2'],$d);			
				$M = round($m/$mt * 100,1);
				$cr1a = round(100 - ($ms/$mt) * 100,1);
				$ir = round(100 - ($sp/$mt) * 100,1);
				$cr = calc_cr($cr1a,$M);
				$indice = calc($ir,$cr);
				$l .= '<li>Entre '.$n['c3'].' et '.$n['c2'].' : '.$indice.'</li>';
			} else { $l .= '<li>Entre '.$n['c3'].' et '.$n['c2'].' : Aucune donnée</li>'; }
		}
		if(isset($n['c4']))
		{
			$d = 'int';
			$sp = nombre_maille($cdnom,$maillage,$n['c4'],$n['c3'],$d);
			if($sp > 0)
			{
				$m = calc_m($nomvar,$choix,$valchoix,$maillage,$n['c4'],$n['c3'],$d);				
				$M = round($m/$mt * 100,1);
				$cr1a = round(100 - ($ms/$mt) * 100,1);
				$ir = round(100 - ($sp/$mt) * 100,1);
				$cr = calc_cr($cr1a,$M);
				$indice = calc($ir,$cr);
				$l .= '<li>Entre '.$n['c4'].' et '.$n['c3'].' : '.$indice.'</li>';
			} else { $l .= '<li>Entre '.$n['c4'].' et '.$n['c3'].' : Aucune donnée</li>'; }
		}
		if(isset($n['c5']))
		{
			$d = 'int';
			$sp = nombre_maille($cdnom,$maillage,$n['c5'],$n['c4'],$d);
			if($sp > 0)
			{
				$m = calc_m($nomvar,$choix,$valchoix,$maillage,$n['c5'],$n['c4'],$d);			
				$M = round($m/$mt * 100,1);
				$cr1a = round(100 - ($ms/$mt) * 100,1);
				$ir = round(100 - ($sp/$mt) * 100,1);
				$cr = calc_cr($cr1a,$M);
				$indice = calc($ir,$cr);
				$l .= '<li>Entre '.$n['c5'].' et '.$n['c4'].' : '.$indice.'</li>';
			} else { $l .= '<li>Entre '.$n['c5'].' et '.$n['c4'].' : Aucune donnée</li>'; }
		}
		if(isset($n['c6']))
		{
			$d = 'inf';
			$sp = nombre_maille($cdnom,$maillage,$n['c6'],$n['c6'],$d);
			if($sp > 0)
			{
				$m = calc_m($nomvar,$choix,$valchoix,$maillage,$n['c6'],$n['c6'],$d);			
				$M = round($m/$mt * 100,1);
				$cr1a = round(100 - ($ms/$mt) * 100,1);
				$ir = round(100 - ($sp/$mt) * 100,1);
				$cr = calc_cr($cr1a,$M);
				$indice = calc($ir,$cr);
				$l .= '<li>Avant '.$n['c6'].' : '.$indice.'</li>';
			} else { $l .= '<li>Avant '.$n['c6'].' : Aucune donnée</li>'; }
		}
	}
	$l .= '</ul>';
	
	$retour['liste'] = $l;
	$retour['statut'] = 'Oui';		
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour);
?>