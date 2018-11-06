<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';
	
function liste($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdnom, nom, nomvern, vali, ir, sensible FROM referentiel.liste
						LEFT JOIN referentiel.sensible USING(cdnom) 
						WHERE observatoire = :observa AND (rang = 'ES' OR rang = 'SSES') 
						ORDER BY nom ");
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function listechoix($nomvar,$choix)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdnom, nom, nomvern, vali, ir, sensible FROM referentiel.liste
						LEFT JOIN referentiel.sensible USING(cdnom) 
						WHERE observatoire = :observa AND (rang = 'ES' OR rang = 'SSES') AND vali = :vali
						ORDER BY nom ");
	$req->bindValue(':observa', $nomvar);
	$req->bindValue(':vali', $choix);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['sel']))
{
	$nomvar = $_POST['sel'];
	$choix = $_POST['choix'];
	
	if($choix == 'tous')
	{
		$liste = liste($nomvar);
	}
	else
	{
		$liste = listechoix($nomvar,$choix);
	}

	if($liste != false)
	{
		$l = '<p>Liste établie à partir des espèces notées comme présentes (observatoire -> gestion des taxons)</p>';
		$l .= '<table id="tblliste" class="table table-sm table-striped" cellspacing="0" width="100%">';
		$l .= '<thead><tr><th>Nom</th><th>Nom français</th><th>Type</th><th>Indice</th><th>Sensible</th></tr></thead>';
		$l .= '</table>';
		foreach($liste as $n)
		{
			if($n['sensible'] == 1) { $sensible = 'Commune'; }
			elseif($n['sensible'] == 2) { $sensible = 'Maille 10'; }
			elseif($n['sensible'] == 3) { $sensible = 'Département'; }
			else { $sensible = ''; }
			if($n['vali'] == 0 || $n['vali'] == null) { $type = 0; }
			elseif($n['vali'] == 1) { $type = 1; }
			elseif($n['vali'] == 2) { $type = 2; }
			
			$s = '<select class="vali">';
			$s .= '<option value="0"';
			$s .= ($type == 0) ? ' selected>NV</option>' : '>NV</option>';
			$s .= '<option value="1"';
			$s .= ($type == 1) ? ' selected>Auto</option>' : '>Auto</option>';
			$s .= '<option value="2"';
			$s .= ($type == 2) ? ' selected>Manuelle</option>' : '>Manuelle</option>';
			$s .= '</select>';
						
			$data[] = [$n['nom'],$n['nomvern'],$s,$n['ir'],$sensible,'DT_RowId'=>$n['cdnom']];
			$s = null;
		}
		$retour['data'] = $data;
	}	
	else
	{
		if($choix == 'tous') { $l = 'Aucune espèce pour cet observatoire'; }
		elseif($choix == 1) { $l = 'Aucune espèce en validation automatique'; }
		elseif($choix == 2) { $l = 'Aucune espèce en validation manuelle'; }
		elseif($choix == 0) { $l = 'Aucune espèce dont le type de validation est pas définit'; }
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