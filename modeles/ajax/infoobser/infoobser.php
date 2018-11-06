<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function nbobs_observa($idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("WITH sel AS (
							SELECT DISTINCT obs.idobs, observa FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							LEFT JOIN obs.plusobser USING(idfiche)
							WHERE fiche.idobser = :idobser OR plusobser.idobser = :idobser
						)
						SELECT COUNT(observa) AS nb, observa FROM sel
						GROUP BY observa");
	$req->bindValue(':idobser', $idobser, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function nbtotal()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT COUNT(obs.idobs) AS nb, COUNT(idphoto) AS nbphoto, observa FROM obs.obs
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						LEFT JOIN site.photo ON photo.idobs = obs.idobs
						GROUP BY observa ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function nbtotal_sp()
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("WITH sel AS (
							SELECT DISTINCT cdref, observatoire FROM obs.obs 
							INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
							WHERE rang = 'ES' OR rang = 'SSES'
						)
						SELECT COUNT(observatoire) AS nbsp, observatoire FROM sel
						GROUP BY observatoire ");
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function nbphoto($idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(idphoto) AS nb, observatoire AS observa FROM site.photo										
						WHERE idobser = :idobser 
						GROUP BY observa ");
	$req->bindValue(':idobser', $idobser, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function nb_taxons($idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("WITH sel AS (
							SELECT DISTINCT cdref, observatoire FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							LEFT JOIN obs.plusobser USING(idfiche)
							INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
							WHERE (fiche.idobser = :idobser OR plusobser.idobser = :idobser) AND (rang = 'ES' OR rang ='SSES')
						)
						SELECT COUNT(observatoire) AS nb, observatoire FROM sel
						GROUP BY observatoire");
	$req->bindValue(':idobser', $idobser, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}

if(isset($_POST['idobser'])) 
{
	$idobser = $_POST['idobser'];
	
	$nbobs = nbobs_observa($idobser);
	$nbtotal = nbtotal();
	$nbtotalsp = nbtotal_sp();
	$nbphoto = nbphoto($idobser);
	$nbtaxon = nb_taxons($idobser);
	
	foreach($nbphoto as $n)
	{
		$tmpph[$n['observa']] = $n['nb'];
	}
	foreach($nbtaxon as $n)
	{
		$tmptaxon[$n['observatoire']] = $n['nb'];
	}
	foreach($nbobs as $n)
	{
		$tmpobs[$n['observa']] = $n['nb'];
	}
	foreach($nbtotalsp as $n)
	{
		$tmpnbttaxon[$n['observatoire']] = $n['nbsp'];
	}
	
	foreach($nbtotal as $t)
	{
		$lnbph = (isset($tmpph[$t['observa']])) ? $tmpph[$t['observa']] : 0;
		$lnbtaxon = (isset($tmptaxon[$t['observa']])) ? $tmptaxon[$t['observa']] : 0;
		$lnbobs = (isset($tmpobs[$t['observa']])) ? $tmpobs[$t['observa']] : 0;
		$pnb = round($lnbobs / $t['nb'] * 100,2);
		$pnbsp = round($lnbtaxon / $tmpnbttaxon[$t['observa']] * 100,2);
		$pnbph = ($t['nbphoto'] > 0) ? round($lnbph / $t['nbphoto'] * 100,2) : 0;
		$tabnb[] = ['observa'=>$t['observa'],'nb'=>$lnbobs,'nbsp'=>$lnbtaxon,'nbph'=>$lnbph,'pnb'=>$pnb,'pnbsp'=>$pnbsp,'pnbph'=>$pnbph];				
	}
	
	$json_site = file_get_contents('../../../json/site.json');
	$rjson_site = json_decode($json_site, true);

	$l = '<table class="table table-sm table-hover"><thead><tr><th class="border-top-0"></th><th class="text-center">Observations</th><th class="text-center">Espèces</th><th class="text-center">Photos</th></tr></thead><tbody>';
	$nbobs1 = 0; $nbsp = 0; $nbph = 0;
	foreach($rjson_site['observatoire'] as $n)
	{
		foreach($tabnb as $a)
		{
			if($a['observa'] == $n['nomvar'] && $a['nb'] > 0)
			{
				$tab[] = ['nom'=>$n['nom']];
				$nbobs1 = $nbobs1 + $a['nb'];
				$nbsp = $nbsp + $a['nbsp'];
				$nbph = $nbph + $a['nbph'];
				$l .= '<tr>';
				$l .= '<th scope="row">'.$n['nom'].'</th><td class="text-center"><b>'.$a['nb'].'</b> - '.$a['pnb'].'%</td><td class="text-center"><b>'.$a['nbsp'].'</b> - '.$a['pnbsp'].'%</td><td class="text-center"><b>'.$a['nbph'].'</b> - '.$a['pnbph'].'%</td>';
				$l .= '</tr>';				
			}
		}
	}
	if(count($tab) > 1)
	{
		$nbt = 0; $nbspt = 0; $nbpht = 0;
		foreach($nbtotal as $n)
		{
			$nbt = $nbt + $n['nb'];
			$nbspt = $nbspt + $tmpnbttaxon[$n['observa']];
			$nbpht = $nbpht + $n['nbphoto'];
		}
		$pnbt = round($nbobs1 / $nbt * 100,2);
		$pnbspt = round($nbsp / $nbspt * 100,2);
		$pnbpht = round($nbph / $nbpht * 100,2);
		$l .= '<tr class="table-success">';
		$l .= '<th scope="row">Total</th><th class="text-center">'.$nbobs1.' - '.$pnbt.'%</th><th class="text-center">'.$nbsp.' - '.$pnbspt.'%</th><th class="text-center">'.$nbph.' - '.$pnbpht.'%</th>';
		$l .= '</tr>';
	}
	$l .= '</tbody></table>';
	
	
	$retour['bilan'] = $l;
	$retour['statut'] = 'Oui';		
}
else
{
	$retour['statut'] = 'Non';
}	
echo json_encode($retour);
?>