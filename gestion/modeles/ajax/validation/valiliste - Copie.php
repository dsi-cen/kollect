<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
/*
SELECT CONCAT(string_agg(DISTINCT ligneobs.stade::text, ',')) AS ss, idobs, date1, to_char(date1, 'DD/MM/YYYY') AS datefr, liste.nom, nomvern, site, commune, observateur, rang, liste.cdnom, iddet, fiche.idobser, liste.vali, array_to_string(array(SELECT decision FROM vali.histovali WHERE histovali.idobs = obs.idobs), ' et ') AS decision FROM obs.fiche
						INNER JOIN referentiel.commune USING(codecom)
						LEFT JOIN obs.site USING(idsite)
						INNER JOIN referentiel.observateur USING(idobser)
						INNER JOIN obs.obs USING(idfiche)
						LEFT JOIN obs.ligneobs USING(idobs)
						--INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE validation = 6 AND observa = 'lepido'
						group by idobs, date1, liste.nom, nomvern, site, commune, observateur, rang, liste.cdnom, iddet, fiche.idobser, liste.vali

with sel as (
SELECT idobs, date1, to_char(date1, 'DD/MM/YYYY') AS datefr, stade.stade, nb, liste.nom, nomvern, site, commune, rang, liste.cdnom, iddet, fiche.idobser, liste.vali FROM obs.fiche
						INNER JOIN referentiel.commune USING(codecom)
						LEFT JOIN obs.site USING(idsite)
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.ligneobs USING(idobs)
						INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE validation = 6 AND observa = 'lepido'
						)
		SELECT idobs, datefr, sel.nom, observateur, CONCAT(string_agg(DISTINCT sel.stade::text, ',')) AS stade, array_to_string(array(SELECT decision FROM vali.histovali WHERE histovali.idobs = sel.idobs), ' et ') AS decision FROM sel
		INNER JOIN referentiel.observateur USING(idobser) 
		group by idobs, datefr, sel.nom, observateur
						
						
*/
function determinateur($iddet)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT observateur FROM referentiel.observateur WHERE idobser = :iddet ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':iddet', $iddet);
	$req->execute();
	$resultat = $req->fetchColumn();
	$req->closeCursor();
	return $resultat;
}	
function liste($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idligne, idobs, date1, to_char(date1, 'DD/MM/YYYY') AS datefr, liste.nom, nomvern, site, commune, observateur, stade.stade, nbmin, nbmax, rang, liste.cdnom, iddet, fiche.idobser, liste.vali, array_to_string(array(SELECT decision FROM vali.histovali WHERE histovali.idobs = obs.idobs), ' et ') AS decision FROM obs.fiche
						INNER JOIN referentiel.commune USING(codecom)
						LEFT JOIN obs.site USING(idsite)
						INNER JOIN referentiel.observateur USING(idobser)
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.ligneobs USING(idobs)
						INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE validation = 6 AND observa = :observa ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function listenouv($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT idligne, idobs, date1, to_char(date1, 'DD/MM/YYYY') AS datefr, liste.nom, nomvern, site, commune, observateur, stade.stade, nbmin, nbmax, rang, liste.cdnom, iddet, fiche.idobser, liste.vali FROM obs.fiche
						INNER JOIN referentiel.commune USING(codecom)
						LEFT JOIN obs.site USING(idsite)
						INNER JOIN referentiel.observateur USING(idobser)
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN obs.ligneobs USING(idobs)
						INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
						INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
						WHERE validation = 7 AND observa = :observa ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['sel']))
{
	$nomvar = $_POST['sel'];
	$nouv = $_POST['nouv'];
	
	$liste = ($nouv == 'non') ? liste($nomvar) : listenouv($nomvar);

	if($liste != false)
	{
		$l = '<table id="tblliste" class="table table-sm table-striped" cellspacing="0" width="100%">';
		$l .= '<thead><tr><th></th><th>Date</th><th>Nom</th><th>Nom fr</th><th>Site</th><th>Commune</th><th>Observateur & det</th><th>Stade</th><th>Nb</th></tr></thead>';
		$l .= '</table>';
		foreach($liste as $n)
		{
			$nb = $n['nbmin'].' - '.$n['nbmax'];
			$lien = ($n['rang'] == 'GN') ? '<a href="../observatoire/index.php?module=fiche&amp;action=ficheg&amp;d='.$nomvar.'&amp;id='.$n['cdnom'].'"><i class="fa fa-file-text-o text-primary"></i></a>' : '<a href="../observatoire/index.php?module=fiche&amp;action=fiche&amp;d='.$nomvar.'&amp;id='.$n['cdnom'].'"><i class="fa fa-file-text-o text-primary"></i></a>';
			$obs = '<a href="../index.php?module=observation&amp;action=detail&amp;idobs='.$n['idobs'].'" title="Voir observation"><i class="fa fa-eye text-primary"></i></a>';
			
			if($n['idobser'] != $n['iddet'])
			{
				$det = determinateur($n['iddet']);
				$obsdet = $n['observateur'].'/'.$det;
			}			
			else
			{
				$obsdet = $n['observateur'];
			}
			$vali = ($n['vali'] == 2) ? '<i class="fa fa-pencil curseurlien text-danger"></i>' : '<i class="fa fa-pencil curseurlien text-warning"></i>';
			$plus = '<i class="fa fa-plus curseurlien text-success detail" title="Détail filtre"></i>';
			$tridate = ['tri'=>$n['date1'],'date'=>$n['datefr']];
			$dec = ($nouv == 'non') ? $n['decision'] : 'Nouvelle espèce. Pas de passage par le filtre informatique';
			$data[] = [$vali.' '.$obs.' '.$lien.' '.$plus,$tridate,$n['nom'],$n['nomvern'],$n['site'],$n['commune'],$obsdet,$n['stade'],$nb,'dec'=>$dec,'DT_RowId'=>$n['idligne'].'-'.$n['idobs']];
		}
		$retour['data'] = $data;
	}	
	else
	{
		$l = 'Aucune observation à valider';
	}
	
	$retour['liste'] = $l;	
	$retour['statut'] = 'Oui';	
}
else
{
	$retour['statut'] = 'Non';
	$retour['mes'] = '<div class="alert alert-danger" role="alert">Aucun observatoire de choisit.</div>';
}
echo json_encode($retour);