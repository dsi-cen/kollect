<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function ligne($idligne)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idfiche, idligne, obs.idobs, obs.cdnom, obs.cdref, nom_cite, iddet, nb, rqobs, observa, statutobs, idprotocole, ligneobs.stade, ndiff, male, femelle, denom, tdenom, idcomp, nbmin, nbmax, idetatbio,
						idmethode, idpros, idstbio, liste.nom, nomvern, observateur AS det, cdhab, mort FROM obs.ligneobs
						INNER JOIN obs.obs USING(idobs)
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						LEFT JOIN referentiel.observateur ON observateur.idobser = obs.iddet
						LEFT JOIN obs.obshab ON obshab.idobs = obs.idobs
						LEFT JOIN obs.obsmort ON obsmort.idobs = obs.idobs
						WHERE idligne = :idligne ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idligne', $idligne, PDO::PARAM_INT);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function aves($idobs,$stade)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT code FROM obs.aves WHERE idobs = :idobs AND stade = :stade ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
	$req->bindValue(':stade', $stade);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function mort($idobs,$stade)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT mort FROM obs.obsmort WHERE idobs = :idobs AND stade = :stade ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
	$req->bindValue(':stade', $stade);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function col($idobs,$stade)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idcol, iddetcol, iddetgen, codegen, sexe, idprep, typedet FROM obs.obscoll WHERE idobs = :idobs AND stade = :stade ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobs', $idobs, PDO::PARAM_INT);
	$req->bindValue(':stade', $stade);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recup_obser($idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT observateur FROM referentiel.observateur WHERE idobser = :idobser ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':idobser', $idobser);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
function habitat($cdhab)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT lbcode, niveau FROM referentiel.eunis WHERE cdhab = :cdhab ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdhab', $cdhab);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function cdhab1($lbcode)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdhab FROM referentiel.eunis WHERE lbcode = :lbcode ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':lbcode', $lbcode);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}
function plante($idobs,$tablebota,$stade)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	if($tablebota == 'listebota')
	{
		$req = $bdd->prepare("SELECT obsplte.stade, nb, nom, nomvern, obsplte.cdnom FROM obs.obsplte
							INNER JOIN obs.ligneobs USING(idobs)
							INNER JOIN referentiel.listebota ON listebota.cdnom = obsplte.cdnom
							WHERE obsplte.idobs = :idobs AND obsplte.stade = :stade ") or die(print_r($bdd->errorInfo()));
	}
	else
	{
		$req = $bdd->prepare("SELECT obsplte.stade, nb, nom, nomvern, obsplte.cdnom FROM obs.obsplte
							INNER JOIN obs.ligneobs USING(idobs)
							INNER JOIN referentiel.liste ON liste.cdnom = obsplte.cdnom
							WHERE obsplte.idobs = :idobs AND obsplte.stade = :stade ") or die(print_r($bdd->errorInfo()));
	}	
	$req->bindValue(':idobs', $idobs);
	$req->bindValue(':stade', $stade);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}

if (isset($_POST['idligne']) && isset($_POST['sel']))
{	
	$idligne = $_POST['idligne'];
	$sel = $_POST['sel'];
	$ligne = ligne($idligne);

	$json = file_get_contents('../../../json/'.$sel.'.json');
	$rjson = json_decode($json, true);
	if(isset($rjson['saisie']['plteh']))
	{
		$tablebota = ($rjson['saisie']['listebota'] == 'aucune') ? 'listebota' : $rjson['saisie']['listebota'];
		$plante = plante($ligne['idobs'],$tablebota,$ligne['stade']);
		if(count($plante) > 0)
		{
			$tmpbota = null;
			foreach($plante as $n)
			{
				$tabbota[] = $n['cdnom'];
				$tabnb[] = $n['nb'];
				$tmpbota .= '<li>'.$n['nb'].' sur '.$n['nom'].'</li>';
			}
			$retour['cdnombota'] = implode(",", $tabbota);
			$retour['nbbota'] = implode(",", $tabnb);
			$retour['listebota'] = $tmpbota;
		}		
	}
	$aves = (isset($rjson['saisie']['aves'])) ? $rjson['saisie']['aves'] : 'non';
	if($aves == 'oui')
	{
		$rqaves = aves($ligne['idobs'],$ligne['stade']);
		if($rqaves['code'] != '')
		{
			$retour['aves'] = $rqaves['code'];
		}		
	}
	$col = (isset($rjson['saisie']['col'])) ? $rjson['saisie']['col'] : 'non';
	if($col == 'oui')
	{
		$rqcol = col($ligne['idobs'],$ligne['stade']);
		if($rqcol['idcol'] != '')
		{
			$retour['col'] = $rqcol;
			if($rqcol['iddetcol'] != '')
			{
				$colobser = recup_obser($rqcol['iddetcol']);
				$retour['colobser'] = $colobser;
				if($rqcol['idprep'] != '')
				{
					$retour['colprep'] = ($rqcol['idprep'] == $rqcol['idcol']) ? $colobser : recup_obser($rqcol['idprep']);
				}
				if($rqcol['iddetgen'] != '')
				{
					$retour['coldetgen'] = ($rqcol['iddetgen'] == $rqcol['idcol']) ? $colobser : recup_obser($rqcol['iddetgen']);
				}
			}
			elseif($rqcol['idprep'] != '')
			{
				$colprep = recup_obser($rqcol['idprep']);
				$retour['colprep'] = $colprep;
				if($rqcol['iddetgen'] != '')
				{
					$retour['coldetgen'] = ($rqcol['iddetgen'] == $rqcol['idprep']) ? $colprep : recup_obser($rqcol['iddetgen']);
				}
			}
		}
	}
	if(!empty($ligne['cdhab']))
	{
		$hab = habitat($ligne['cdhab']);
		if($hab['niveau'] > 1)
		{
			$cdhab = cdhab1($hab['lbcode'][0]);
			$retour['habitat2'] = 'oui';
			$retour['hab1'] = $cdhab;
			if($hab['niveau'] > 2)
			{
				$retour['habitat3'] = 'oui';
				$lbcode = substr($hab['lbcode'], 0, 2);
				$retour['hab2'] = cdhab1($lbcode);
			}
		}
	}	
	if(!empty($ligne['mort']))
	{
		$rqmort = mort($ligne['idobs'],$ligne['stade']);
		if($rqmort['mort'] != '')
		{
			$retour['mort'] = $rqmort['mort'];
		}
	}
	
	$retour['ligne'] = $ligne;	
	$retour['statut'] = 'Oui';
}
else
{
	$retour['statut'] = 'Non';	
}
echo json_encode($retour);	
?>