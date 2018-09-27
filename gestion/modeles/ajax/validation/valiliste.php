<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';

function determinateur($iddet)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->prepare("SELECT observateur FROM referentiel.observateur WHERE idobser = :iddet ");
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
	$req = $bdd->prepare("WITH sel AS (
							SELECT idobs, date1, stade.stade, nb, liste.nom, liste.nomvern, site, commune, liste.rang, liste.cdnom, iddet, fiche.idobser, liste.vali FROM obs.fiche
							INNER JOIN referentiel.commune USING(codecom)
							LEFT JOIN obs.site USING(idsite)
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN obs.ligneobs USING(idobs)
							INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
							INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
							WHERE validation = 6 AND observa = :observa
						)
						SELECT sel.idobs, date1, to_char(date1, 'DD/MM/YYYY') AS datefr, sel.cdnom, sel.nom, sel.nomvern, famille.famille, site, commune, sel.rang, iddet, sel.idobser, observateur, photo.idobs AS photo, nb, vali, CONCAT(string_agg(DISTINCT sel.stade::text, ',')) AS stade, array_to_string(array(SELECT decision FROM vali.histovali WHERE histovali.idobs = sel.idobs), ' et ') AS decision FROM sel
						INNER JOIN referentiel.observateur USING(idobser)
						INNER JOIN $nomvar.liste AS l ON l.cdnom = sel.cdnom
						INNER JOIN $nomvar.famille on famille.cdnom = l.famille
						LEFT JOIN site.photo ON photo.idobs = sel.idobs 
						GROUP BY sel.idobs, date1, sel.cdnom, sel.nom, sel.nomvern, famille.famille, site, commune, sel.rang, iddet, sel.idobser, observateur, photo.idobs, nb, vali 
						ORDER BY date1 ");
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
	$req = $bdd->prepare("WITH sel AS (
							SELECT idobs, date1, stade.stade, nb, liste.nom, nomvern, site, commune, rang, liste.cdnom, iddet, fiche.idobser, liste.vali FROM obs.fiche
							INNER JOIN referentiel.commune USING(codecom)
							LEFT JOIN obs.site USING(idsite)
							INNER JOIN obs.obs USING(idfiche)
							INNER JOIN obs.ligneobs USING(idobs)
							INNER JOIN referentiel.stade ON stade.idstade = ligneobs.stade
							INNER JOIN referentiel.liste ON liste.cdnom = obs.cdref
							WHERE validation = 7 AND observa = :observa
						)
						SELECT sel.idobs, date1, to_char(date1, 'DD/MM/YYYY') AS datefr, sel.cdnom, sel.nom, sel.nomvern, famille.famille, site, commune, sel.rang, iddet, sel.idobser, observateur, photo.idobs AS photo, nb, vali, CONCAT(string_agg(DISTINCT sel.stade::text, ',')) AS stade, array_to_string(array(SELECT decision FROM vali.histovali WHERE histovali.idobs = sel.idobs), ' et ') AS decision FROM sel
						INNER JOIN referentiel.observateur USING(idobser)
						INNER JOIN $nomvar.liste AS l ON l.cdnom = sel.cdnom
						INNER JOIN $nomvar.famille on famille.cdnom = l.famille
						LEFT JOIN site.photo ON photo.idobs = sel.idobs 
						GROUP BY sel.idobs, date1, sel.cdnom, sel.nom, sel.nomvern, famille.famille, site, commune, sel.rang, iddet, sel.idobser, observateur, photo.idobs, nb, vali 
						ORDER BY date1 ");
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
		$l .= '<thead><tr><th></th><th>Date</th><th>Nom</th><th>Nom fr</th><th>famille</th><th>Site</th><th>Commune</th><th>Observateur & det</th><th>Stade</th><th>Nb</th><th>Photo</th></tr></thead>';
		$l .= '</table>';
		foreach($liste as $n)
		{
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
			$photo = (!empty($n['photo'])) ? 'oui' : 'non';
			$vali = ($n['vali'] == 2) ? '<i class="fa fa-pencil curseurlien text-danger"></i>' : '<i class="fa fa-pencil curseurlien text-warning"></i>';
			$plus = '<i class="fa fa-plus curseurlien text-success detail" title="Détail filtre"></i>';
			$tridate = ['tri'=>$n['date1'],'date'=>$n['datefr']];
			$dec = ($nouv == 'non') ? $n['decision'] : 'Nouvelle espèce. Pas de passage par le filtre informatique';
			$data[] = [$vali.' '.$obs.' '.$lien.' '.$plus,$tridate,$n['nom'],$n['nomvern'],$n['famille'],$n['site'],$n['commune'],$obsdet,$n['stade'],$n['nb'],$photo,'dec'=>$dec,'DT_RowId'=>$n['idobs']];
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