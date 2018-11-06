<?php
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';
	
function liste($nomvar,$choix)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("WITH sel AS (
							SELECT cdref, MAX(date1) AS max, to_char(MAX(date1), 'DD/MM/YYYY') AS maxfr FROM obs.obs
							INNER JOIN obs.fiche USING(idfiche)
							WHERE observa = :observa
							GROUP BY cdref
						)
						SELECT sel.cdref, nom, liste.nomvern, famille.famille, maxfr, max FROM sel
						INNER JOIN $nomvar.liste ON liste.cdnom = sel.cdref
						INNER JOIN $nomvar.famille ON liste.famille = famille.cdnom
						WHERE max <= :choix AND (liste.rang = 'ES' OR liste.rang = 'SSES')
						ORDER BY nom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':observa', $nomvar);
	$req->bindValue(':choix', $choix);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['sel']))
{
	$nomvar = $_POST['sel'];
	$an = $_POST['choix'];
	
	$choix = $an.'-01-01';
	$liste = liste($nomvar,$choix);
	
	if($liste != false)
	{
		$nb = count($liste);
		$l = '<h2 class="h4">Espèces non revue depuis '.$an.' ('.$nb.')</h2>';
		$l .= '<table id="tblliste" class="table table-sm table-striped" cellspacing="0" width="100%">';
		$l .= '<thead><tr><th></th><th>Nom</th><th>Nom français</th><th>Famille</th><th>Dernière obs</th></tr></thead>';
		$l .= '</table>';
		foreach($liste as $n)
		{
			$lien = '<a href="index.php?module=fiche&amp;action=fiche&amp;d='.$nomvar.'&amp;id='.$n['cdref'].'"><i class="fa fa-file-text-o text-primary"></i></a>';
			$tridate = ['tri'=>$n['max'],'date'=>$n['maxfr']];
			$data[] = [$lien,$n['nom'],$n['nomvern'],$n['famille'],$tridate];
		}
		$retour['data'] = $data;
	}	
	else
	{
		$l = 'Aucune espèce non revue depuis '.$an;
		
	}
	
	$retour['liste'] = $l;	
	$retour['statut'] = 'Oui';	
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);